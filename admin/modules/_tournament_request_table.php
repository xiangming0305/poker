<?php 
	require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";

    $tournamentRequests = PrivateTournament::pending($_GET['from_date'],$_GET['to_date'],$_GET['playername'],$_GET['invited_user'],$_GET['tournament_name'],$_GET['create_from_date'],$_GET['create_to_date']); 

	if (count($tournamentRequests ?? [])) {
    $userIds = [];
    $tmp = [];
	$ids = [];
	$i = 0;
    foreach ($tournamentRequests as $tournamentRequest) {
	$ids[$i] = $tournamentRequest['add_invited_user'].','.$tournamentRequest['invite_user_ids'];
        $userIds = array_merge($userIds, explode(',', $ids));
        $userIds[] = $tournamentRequest['user_id'];
		$i +=1;
    }
    $tmp2 = [];
    foreach ($userIds as $key => $value) {
        if (trim($value) != '' && !in_array($value, $tmp2)) {
            $tmp2[] = $value;
        }
    }
    $usersWithInvitationCount = PrivateTournament::countUsersInvitation($tmp2, $fromDate ?? '', $toDate ?? '',
        $createFromDate ?? '', $createToDate ?? ''); //lmao
    ?>
    <table style="width: 100%">
        <thead>
        <tr>
            <td>Game</td>
            <td>Tables</td>
            <td>Seats</td>
            <td>Start time</td>
            <td>Chips to pay</td>
            <td>User</td>
            <td>Invited users</td>
            <td>Tournament Name</td>
            <td>BuyIn</td>
            <td>Seat price</td>
            <td>Created at</td>
        </tr>
        </thead>

        <tbody>
        <?php
        $totalChips = 0;
        $totalSeats = 0;
        $totalTables = 0;
        $totalInvites = 0;
        $totalSeatPrice = 0;
		$totalTournametName = 0;
		$i = 0;
        foreach ($tournamentRequests as $item) {
            $seatPrice = $item['chips_to_pay'] / $item['table'] / $item['seat'];

            $totalSeatPrice += $seatPrice;
            $totalChips += $item['chips_to_pay'];
            $totalTables += $item['table'];
            $totalSeats += $item['seat'];
			if($item['name'] != null){
				$totalTournametName += 1;
			}
            $invitedUsers = UserMachine::getUsernameByIds($ids[$i]);
            $totalInvites += count($invitedUsers);
            ?>
            <tr>
                <td><?= $item['game'] ?></td>
                <td><?= $item['table'] ?></td>
                <td><?= $item['seat'] ?></td>
                <td>
                    <?= $item['start_time'] ?>
                </td>
                <td><?= $item['chips_to_pay'] ?></td>
                <td><?= $item['playername'] ?>
                    [<?= 'S' . ($usersWithInvitationCount[$item['playername']]['sent'] ?? 0) . ',R' . ($usersWithInvitationCount[$item['playername']]['received'] ?? 0) ?>]
                </td>
                <td>
                    <?php
                    foreach ($invitedUsers as $invitedUser) { ?>
                        <?= $invitedUser ?>[<?= 'S' . ($usersWithInvitationCount[$invitedUser]['sent'] ?? 0) . ',R' . ($usersWithInvitationCount[$invitedUser]['received'] ?? 0) ?>],
                    <?php }
                    ?>
                <td><?= $item['name'] ?></td>
                <td><?= $item['buyin'] ?></td>
                <td><?= $seatPrice ?></td>
                <td><?= $item['created_at'] ?></td>
            </tr>
        <?php $i +=1; }
        ?>
        </tbody>

        <tfoot class="text-bold">
        <tr>
            <td>Total</td>
            <td><?= $totalTables ?></td>
            <td><?= $totalSeats ?></td>
            <td></td>
            <td><?= $totalChips ?></td>
            <td></td>
            <td><?= $totalInvites ?></td>
            <td><?= $totalTournametName ?></td>
            <td></td>
            <td><?= $totalSeatPrice ?></td>
            <td></td>
        </tr>
        </tfoot>
    </table>

    <?php
    if ($_GET['invited_user'] ?? '') { ?>
        <div class="m-t-10">Total seats price of <span class="text-bold"><?=$_GET['invited_user'] ?></span>: <?=$totalSeatPrice?></div>
    <?php }
    ?>

<?php } else { ?>
    <p>No data</p>
<?php } ?>