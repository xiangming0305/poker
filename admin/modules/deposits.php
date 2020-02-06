<hgroup>
    <h2>Deposit requests</h2>
    <p>Here are shown all deposit requests player do. Accept request to transfer him requested amount of chips </p>
</hgroup>
<?php require $_SERVER['DOCUMENT_ROOT']."/admin/modules/history.php"; ?>


<table id='deposits' style="width: 100%">
    <thead>
        <td>Date</td>
        <td>Player</td>
        <td>Amount</td>
        <td>Chips left</td>
        <td>Player rake</td>
        <td>Payment method</td>
        <td>Paid from name</td>
        <td>Paid from account</td>
        <td>Paid from country</td>
        <td>Transaction ID</td>
        <td>Status</td>
        <td style="text-align: center">Actions</td>
    </thead>    
    
    <tbody>
        <?php
            require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
            $tr = Poker_Transactions::getDepositTransactions();
            foreach($tr as $t){
                $user = UserMachine::getUserByPlayerName($t['user']);
                $btns = "";
				$user_id = $user->getId();
                $editbtn = "";
                switch ($t['status']){
                    case Poker_Transactions::DEPOSIT_PENDING:{
                        $status = "<span>Pending</span>";
                        $btns = "
							<span>Note:</span><input id='note' type='text' style='padding: 3px;border: 1px solid #777;' />
							<input type='hidden' id='user_id' value='$user_id' />
							<input type='button' class='accept' />
                            <input type='button' class='decline' />";
                            $editbtn = "<input type='button' class='editAmount' />";
                        break;
                    }
                    case Poker_Transactions::DEPOSIT_ACCEPTED:{
                        $status = "<span class='accepted'>Accepted</span>";
                        break;
                    }
                    case Poker_Transactions::DEPOSIT_DECLINED:{
                        $status = "<span class='declined'>Declined</span>";
                        break;
                    }
                }
				$payment = new Poker_PaymentMethod($t['payment_method']);
				$name = $payment->name;
				$name = $name."(".$t['payment_method'].")";
                echo "
                    <tr data-id='{$t['id']}'>
                        <td>{$t['date']}</td>
                        <td><span class='user'>{$t['user']}</span><input type='button' class='history' /></td>
                        <td><span class='number'>{$t['amount']}</span> $editbtn</td>
                        <td>{$user->balance}</td>
                        <td>{$user->getRakeValue()}</td>
                        <td>$name</td>
                        <td>{$t['paid_from']}</td>
                        <td>{$t['paid_from_account']}</td>
                        <td>{$t['paid_from_country']}</td>
                        <td>{$t['transaction_id']}</td>
                        <td class='stat'>$status</td>
                        <td class='buttons'>
                            $btns
                        </td>
                    </tr>
                ";
            }
        ?>
    </tbody>
</table>

    
    