<div class='affiliateRequests'>
<h2 style="padding-top: 50px">Admin Widthraw Requests</h2>
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
$sql = new SQLConnection;
    $systemBalance = Poker_System::Balance(array());
$totalHanRake = $systemBalance['Rake'];
    $totaTournamentFee = $systemBalance['EntryFee'];
$transferHistorySql =  $sql->getArray("SELECT * FROM poker_users_transfer");
/*
    $totalHanRakeSql = $sql->getArray('SELECT SUM(`total_rake`) AS total_hand_rake FROM `poker_player_rake`');


    $totalHanRake = $totalHanRakeSql[0]['total_hand_rake'];

    $totalUserSql = $sql->getArray('SELECT SUM(`tournaments_fee`) AS total_tournaments_fee,  SUM(`points_dec`) AS freeroll_fee  FROM `poker_users`');
    $totaTournamentFee = $totalUserSql[0]['total_tournaments_fee'];
    $totaFreeroll = $totalUserSql[0]['freeroll_fee'];
*/
    $listUser = UserMachine::getAllUsers();
    $totalAffiliate=0;
    foreach ($listUser as $key => $user) {
        // $user = UserMachine::getUserByPlayerName($value['name']);
        $totalAffiliate+= $user->getAffilateBalance();
        $totalAffiliate+= $user->getAdminChangeAffiliate();
    }

    $totalAffiliateWithdraw = $sql->getArray("SELECT SUM(`amount`) AS total FROM `poker_users_transfer` WHERE status=1 AND playername <> 'admin'")[0]['total'];
    $totalAdminWithdraw = $sql->getArray("SELECT SUM(`amount`) AS total FROM `poker_users_transfer` WHERE status=1 AND playername = 'admin'")[0]['total'];
$totalBalance = $totalHanRake +  $totaTournamentFee - $totalAffiliate - $totalAffiliateWithdraw - $totalAdminWithdraw;
?>

<p>Total hand rake: <?= $totalHanRake ?></p>
<p>Total tournament fee: <?= $totaTournamentFee ?></p>
    <p>Total Affiliate balance: <?= $totalAffiliate ?></p>
    <p>Total Affiliate withdraw: <?= $totalAffiliateWithdraw ?></p>
    <p>Total Admin withdraw: <?= $totalAdminWithdraw ?></p>


<h2 style="font-weight:bold">Your balance: <?= $totalBalance ?></h2>
<h3>Your history withdraw</h3>
<table>
    <thead>
        <td>Player name</td>
        <td>Request datetime</td>
        <td>Amount</td>
    </thead>
    
    <tbody>
<?php
    foreach ($transferHistorySql as $v) {
        if($v['playername']!='admin')
            continue;
       
        echo "<tr data-id='{$v['id']}'>
                <td>{$v['playername']}</td>
                <td>{$v['created_time']}</td>
                <td>{$v['amount']}</td>
            </tr>";
    }
?>
    </tbody>
</table>
<br/><br/>
<hgroup>
    <h3>Withdraw Money</h3>
</hgroup>
<form class="transferAffiliate" id="transferAffiliate">
    <label>
        <span>Amount:</span>
        <input type='number' step="0.01" min='1' max='<?= $totalBalance ?>' id="affiliate_amount" list="players" required="" style="border: 1px solid">
    </label>
    <input type="submit" class="button" value="Send">
    <p id='affiliate-transfer-status' class='status'></p>
</form>


</div>
<?php

    