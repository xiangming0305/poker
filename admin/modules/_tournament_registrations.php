<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $tournamentRegistrations = Poker_Transactions::getTournamentRegistrations($_POST['fromDate'] ?? '',$_POST['toDate'] ?? '',$_POST['player'] ?? '', $_POST['name'] ?? '', $_POST['creator'] ?? '', $_POST['inviteduser'] ?? ''); 
    ?>
    <table style="width: 100%">
        <thead>
        <tr>
            <td>Name</td>
            <td>Player</td>
            <td>Time</td>
        </tr>
        </thead>

        <tbody>
        <?php
        $totalAmount = 0;

        foreach ($tournamentRegistrations as $item) {
           
            $totalTournament += 1;
            ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['player'] ?></td>
                <td><?= $item['time'] ?></td>
            </tr>
        <?php }
        ?>
        </tbody>

        <tfoot class="text-bold">
        <tr>
            <td>Total tournament played</td>
            <td colspan="2"><?= $totalTournament ?></td>
        </tr>
		 <tr>
            <td>Total price</td>
            <td colspan="2"><?= $totalTournament*Poker_Variables::get('tournament_request_chips_per_seat') ?></td>
        </tr>
        </tfoot>
    </table>
