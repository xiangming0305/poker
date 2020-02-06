<?php 
	require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $chipTransaction = Poker_Transactions::getChipsTransactions($_POST['fromDate'] ?? '',$_POST['toDate'] ?? '',$_POST['sendName'] ?? '', $_POST['receivedName'] ?? ''); 
    ?>
    <table style="width: 100%">
        <thead>
        <tr>
            <td>User</td>
            <td>Target</td>
            <td>Amount</td>
            <td>Balance</td>
            <td>Date</td>
            <td>Data</td>
            <td>Tournament Name</td>
        </tr>
        </thead>

        <tbody>
        <?php
        $totalAmount = 0;

        foreach ($chipTransaction as $item) {
           
            $totalAmount += $item['amount'];
            ?>
            <tr>
                <td><?= $item['user'] ?></td>
                <td><?= $item['target'] ?></td>
                <td><?= $item['amount'] ?></td>
                <td>
                    <?= $item['balance'] ?>
                </td>
                <td><?= $item['date'] ?></td>
                <td><?= $item['data'] ?>
                </td>
                <td><?= $item['tournament_name'] ?></td>
            </tr>
        <?php }
        ?>
        </tbody>

        <tfoot class="text-bold">
        <tr>
            <td>Total Amount</td>
            <td></td>
            <td><?= $totalAmount ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
