<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";
$user = UserMachine::getCurrentUser();
/** @var User $user */
$user = UserMachine::refreshBalance($user);

if (!isset($_POST['game']) || !isset($_POST['tables']) || !isset($_POST['seats'])) {
    die(json_encode(['status' => 'ERROR', 'message' => 'You need input information']));
}
$game = addslashes($_POST['game']);
$tables = (int)$_POST['tables'];
$seats = (int)$_POST['seats'];
$start_time = $_POST['starttime'];
$buyin = (float) $_POST['buyin'];
$maxrebuys = $_POST['MaxRebuys'];

$status = PrivateTournament::STATUS_PENDING;
$user_id = $user->getId();

$chipsPerSeat = Poker_Variables::get('tournament_request_chips_per_seat');
$chipsToPay = $tables * $seats * $chipsPerSeat;

if ($user->balance < $chipsToPay) {
    die(json_encode(['status' => 'ERROR', 'message' => 'You dont have enough chips']));
}

$str_usernames = Core::escape(trim($_POST['invite_users']));
$usernames = explode(',', $str_usernames);
$tmp = [];
foreach ($usernames as $username) {
	if ($username == $user->playername) {
		die(json_encode(['status' => 'ERROR', 'message' => 'You cant invite yourself']));
	}
    $tmp[] = "'" . $username . "'";
}
$strtmp = implode(',', $tmp);
$sql = new SQLConnection();
$lstUsers = $sql->getAssocArray("select id from poker_users where name in ({$strtmp})");
foreach ($lstUsers as $lstUser) {
    $invite_user_ids[] = $lstUser['id'];
}
$invite_user_ids = implode(',', $invite_user_ids);

$requestId = PrivateTournament::request($game, $tables, $seats, $start_time, $status, $user_id, $invite_user_ids,
    $chipsToPay, $buyin);
//var_dump($requestId); die;

//create game
$adminConfigKeys = [];
foreach (PrivateTournament::adminConfigFields() as $adminConfigField) {
    $adminConfigKeys[] = PrivateTournament::genKey($adminConfigField['name']);
}

$adminConfig = Poker_Variables::getList($adminConfigKeys);
$clientRequestConfig = PrivateTournament::getById($requestId);

$adminParams = [];
foreach (PrivateTournament::adminConfigFields() as $adminConfigField) {
    $key = PrivateTournament::genKey($adminConfigField['name']);
    $adminParams[$adminConfigField['name']] = $adminConfig[$key]['value'] ?? '';
}

$clientParams = [
    'Game'      => $clientRequestConfig['game'],
    'Tables'    => $clientRequestConfig['table'],
    'Seats'     => $clientRequestConfig['seat'],
    'StartTime' => DateTime::createFromFormat('Y-m-d H:i:s', $clientRequestConfig['start_time'])->format('Y-m-d H:i'),
    'BuyIn'     => $clientRequestConfig['buyin'],
    'MaxRebuys' => $maxrebuys,
];

$tournamentIndex = (int)Poker_Variables::get('tournament_index');

$Name = Poker_Tournaments::PREFIX . $tournamentIndex;
$PW = Utils::randomPassword(7);

$params = [
    'Name'             => $Name,
    'Game'             => '',
    'MixedList'        => '',
    'Shootout'         => '',
    'Description'      => '',
    'Auto'             => '',
    'PW'               => $PW,
    'Private'          => 'YES',
    'PermRegister'     => '',
    'PermUnregister'   => '',
    'PermPlayerChat'   => '',
    'PermObserverChat' => '',
    'SuspendChatAllIn' => '',
    'Tables'           => '',
    'Seats'            => '',
    'StartFull'        => '',
    'StartMin'         => '',
    'StartCode'        => '',
    'RegMinutes'       => '',
    'MinPlayers'       => '',
    'RecurMinutes'     => '',
    'ResetSeconds'     => '',
    'NoShowMinutes'    => '',
    'BuyIn'            => '',
    'EntryFee'         => '',
    'Ticket'           => '',
    'TicketRequired'   => '',
    'TicketFunded'     => '',
    'PrizeBonus'       => '',
    'MultiplyBonus'    => '',
    'Chips'            => '',
    'AddOnChips'       => '',
    'TurnClock'        => '',
    'TurnWarning'      => '',
    'TimeBank'         => '',
    'BankSync'         => '',
    'BankReset'        => '',
    'DisProtect'       => '',
    'Level'            => '',
    'RebuyLevels'      => '',
    'Threshold'        => '',
    'MaxRebuys'        => '',
    'RebuyCost'        => '',
    'RebuyFee'         => '',
    'BreakTime'        => '',
    'BreakLevels'      => '',
    'StopOnChop'       => '',
    'BringInPercent'   => '',
    'Blinds'           => '',
    'Payout'           => '',
    'PayoutTickets'    => '',
    'UnregLogout'      => '',
];

$params = array_merge($params, $adminParams, $clientParams);

$fee = Poker_Variables::get('tournament_fee');
$params['EntryFee'] = $fee * $params['BuyIn'];
$params['RebuyCost'] = $params['BuyIn'];
$params['RebuyFee'] = $fee * $params['BuyIn'];
$params['Prize'] = $params['BuyIn'];
//$params['PrizeBonus'] = $params['BuyIn'];

foreach ($params as $key => $value) {
    if ($value == null || $value == '') {
        unset($params[$key]);
    }
}

$result = Poker_Tournaments::Add($params);

if ($result['Result'] == 'Ok') {

    Poker_Variables::set('tournament_index', $tournamentIndex + 1);

    PrivateTournament::updateColumns($requestId, ['name' => $Name]);

    //sub chips from user
    $rs2 = Poker_Accounts::DecBalance(['Player' => $user->playername, 'Amount' => $chipsToPay]);
    $user = UserMachine::refreshBalance($user);
    Poker_Transactions::chipsTransaction(null, $user, (-1) * $chipsToPay, false, Poker_Transactions::PRIVATE_TOURNAMENT, $Name);

    //put tournament online
    $rs3 = Poker_Tournaments::Online(['Name' => $Name]);

    /**
     * send information to creator of tournament
     */
    $message = new Message();
    $message->from_name = 'System';
    $message->message = addslashes('Your created tournament name: ' . $Name . '<br/> Password: ' . $PW);
    $message->to_name = $user->playername;
    $message->submitChanges();


    /**
     * send inbox to invited users
     */
    $invitedUsers = UserMachine::getUserByIds($clientRequestConfig['invite_user_ids']);

    $content = ""
        . "You have invitation from username: " . $user->playername . '<br/>'
        . "Tournament name: " . $Name . '<br/>'
        . "Password: " . $PW;

    foreach ($invitedUsers as $invitedUser) {
        $message = new Message(); //haha
        $message->from_name = 'System';
        $message->message = addslashes($content);
        $message->to_name = $invitedUser['name'];
        $message->submitChanges();

        $mail = new Mailer(); //hahaha
        $mail->send($invitedUser['email'], 'Tournament invitation', $content);
    }

    die(json_encode(['status' => 'OK', 'data' => $result['Message'] ?? 'Successfully created']));
}

Poker_Variables::set('tournament_index', $tournamentIndex + 1);

die(json_encode([
    'status'  => 'ERROR',
    'message' => $result['Error'] ?? 'Can not create tournament'
]));