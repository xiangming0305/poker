<hgroup>
    <h2>Cash out requests</h2>
    <p>Here are shown all cash out requests. You should accept request when deposite is done or decline if you don't want to withdraw chips for this player.</p>
</hgroup>
<?php require $_SERVER['DOCUMENT_ROOT']."/admin/modules/history.php"; ?>
<table id='cashouts'>
    <thead>
        <td>Date</td>
        <td>Player</td>
        <td>Amount</td>
		<td>Price</td>
        <td>Chips left</td>
        <td>Player rake</td>
		<td>Payment method</td>
        <td>Receiver name</td>
        <td>Receiver country</td>
        <td>Receiver account</td>
        <td>Status</td>
        <td style="text-align: center">Actions</td>
    </thead>    
    
    <tbody>
        <?php
            require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
            $tr = Poker_Transactions::cashOutTransactions();
            foreach($tr as $t){
                $user = UserMachine::getUserByPlayerName($t['user']);
                $btns = "";
				$user_id = $user->getId();
                $editbtn = "";
                switch ($t['status']){
                    case Poker_Transactions::CASHOUT_PENDING:{
                        $status = "<span>Pending</span>";
                        $btns = "<span>Note:</span><input id='note' type='text' style='padding: 3px;border: 1px solid #777;' />
							<input type='hidden' id='user_id' value='{$user_id}' />
							<input type='button' class='accept' />
                            <input type='button' class='decline' />";
                            $editbtn = "<input type='button' class='editAmount' />";
                        break;
                    }
                    case Poker_Transactions::CASHOUT_ACCEPTED:{
                        $status = "<span class='accepted'>Accepted</span>";
                        break;
                    }
                    case Poker_Transactions::CASHOUT_DECLINED:{
                        $status = "<span class='declined'>Declined</span>";
                        break;
                    }
                }
				$price = $t['amount']/Poker_Variables::get('deposit_rate');
				$payment = new Poker_PaymentMethod($t['payment_method']);
				$name = $payment->name;
				$name = $name."(".$t['payment_method'].")";
                echo "
                    <tr data-id='{$t['id']}'>
                        <td>{$t['date']}</td>
                        <td><span class='user'>{$t['user']}</span><input type='button' class='history' /></td>
                        <td><span class='number'>{$t['amount']}</span> $editbtn</td>
						<td>$price</td>
                        <td>{$user->balance}</td>
                        <td>{$user->getRakeValue()}</td>
						<td class='stat'>$name</td>
						<td class='stat'>{$t['receive_by']}</td>
						<td class='stat'>{$t['receive_by_country']}</td>
						<td class='stat'>{$t['receive_by_account']}</td>
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


<?php
    

    
    