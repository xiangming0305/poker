<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";

class Poker_Transactions{

    const PRIVATE_TOURNAMENT = 'Private tournament';
    
    /**
     * <User> $user, <User> $target, float $amount
     */
    public static function chipsTransaction($user, $target, $amount, $affiliate = false, $description = '', $tournamentName){
        $description = $description ? $description : 'Transferred by admin';
        $sql = new SQLConnection();
        if($user==NULL){
            if($affiliate){
                $sql->query("INSERT INTO poker_chips_transactions(`id`, `user`, `target`, `amount`, `balance`, `date`, `data` ) VALUES (default,'[[Referrals]]', '{$target->playername}', $amount, {$target->balance}, NOW(), 'Got chips from affiliate program')");
            }else{
                $sql->query("INSERT INTO poker_chips_transactions(`id`, `user`, `target`, `amount`, `balance`, `date`, `data`, `tournament_name` ) VALUES (default,'', '{$target->playername}', $amount, {$target->balance}, NOW(), '{$description}', '{$tournamentName}')");
            }
        }else{
            $sql->query("INSERT INTO poker_chips_transactions(`id`, `user`, `target`, `amount`, `balance`, `date`, `data` ) VALUES (default,'{$user->playername}', '{$target->playername}', $amount, {$user->balance}, NOW(), '')");
        }
        return mysqli_error() ? false : true;
    }
    
    public static function getChipsTransactions($fromDate, $toDate, $sendName, $receivedName){
	     $sql = new SQLConnection();
	if($fromDate != ''){
	$fromDate = "AND date > '$fromDate'";
	}
	if($toDate != ''){
	$toDate = "AND date < '$toDate'";
	}
	if($sendName != ''){
	$sendName = "AND user='$sendName'";
	}
	if($receivedName != ''){
	$receivedName = "AND target ='$receivedName'";
	}
   
		if($fromDate == '' && $toDate == '' && $sendName == '' && $receivedName == ''){
		return $sql->getAssocArray("SELECT * FROM poker_chips_transactions ORDER BY date DESC");
		}
		else{
		return $sql->getAssocArray("SELECT * FROM poker_chips_transactions where TRUE {$sendName} {$receivedName} {$toDate} {$fromDate} ORDER BY date DESC");
		}
    }
    
    public static $methods = [
            array("method"=>"Visa")
        ];
    
    const CASHOUT_PENDING = 1;
    const CASHOUT_ACCEPTED = 2;
    const CASHOUT_DECLINED = 3;
    
    public static function cashOut($user, $amount, $payment_method, $receive_by, $receive_by_account, $receive_by_country){
        $sql = new SQLConnection();
        if(!$user){
           
            return FALSE;
        }
        if($user->balance < $amount){
            
            return FALSE;
        }
		if($payment_method == ""){
            
            return FALSE;
        }
        $sql->query("INSERT INTO poker_cashout_transactions VALUES (default, '{$user->playername}', $amount, NOW(), '$payment_method', '$receive_by', '$receive_by_account', '$receive_by_country', ".self::CASHOUT_PENDING.", '')");
        
        
        if(mysqli_error()){
            return FALSE;
        }
        $user->balance -=$amount;
        Poker_Accounts::DecBalance(["Player"=>$user->playername, "Amount"=>$amount]);
        $user->submitChanges();
        return $user->balance;
        
    }
    
    public static function cashOutTransactions(){
        $sql = new SQLConnection();
        return $sql->getArray("SELECT * FROM poker_cashout_transactions ORDER BY status ASC, date DESC");
    }
    
    public static function cashOutTransactionStatus($id, $status){
        $sql = new SQLConnection();
        if($status == self::CASHOUT_DECLINED){
            $tmp = $sql->getArray("SELECT * FROM poker_cashout_transactions WHERE id=$id");
            $user = $tmp[0]['user'];
            $user = UserMachine::getUserByPlayerName($user);
            $user->balance += $tmp[0]['amount']*1;
            Poker_Accounts::IncBalance(["Player"=>$user->playername, "Amount"=>$tmp[0]['amount']*1]);
            $user->submitChanges();
        }
        $sql->query("UPDATE poker_cashout_transactions SET status=$status WHERE id=$id");
        echo mysqli_error();
    }
    
    public static function cashOutTransactionAmount($id, $amount){
        $sql = new SQLConnection;
        $amount = $amount*1;
        $sql->query("UPDATE poker_cashout_transactions SET amount=$amount WHERE id=$id AND status=".self::CASHOUT_PENDING);
        return (mysqli_error() ? FALSE : TRUE);
    }
    
    
    const DEPOSIT_PENDING = 1;
    const DEPOSIT_ACCEPTED = 2;
    const DEPOSIT_DECLINED = 3;
    
    public static function depositTransaction($user, $amount, $paymentMethod = '', $paidFrom = '', $transactionId = '', $paihFromAccount = '', $paidFromCountry = ''){
        $sql = new SQLConnection();
        if(!$user){
            return FALSE;
        }
        $sql->query("INSERT INTO poker_deposit_transactions
          VALUES (default, '{$user->playername}', $amount, NOW(), ".self::DEPOSIT_PENDING.",  '', '{$paymentMethod}', '{$paidFrom}', '{$transactionId}', '{$paihFromAccount}', '{$paidFromCountry}')");
        if(mysqli_error()){
            return FALSE;
        }
        return TRUE;
    }
    
		public static function getDepositTransactions(){
        $sql = new SQLConnection();
        return $sql->getArray("SELECT * FROM poker_deposit_transactions ORDER BY status ASC, date DESC");
    }
    
    public static function depositTransactionStatus($id, $status){
        $sql = new SQLConnection();
        if($status == self::DEPOSIT_ACCEPTED){
            $tmp = $sql->getArray("SELECT * FROM poker_deposit_transactions WHERE id=$id");
            $user = $tmp[0]['user'];
            $rate = Poker_Variables::get('deposit_rate');
            $amount = $tmp[0]['amount'] * $rate;
            $user = UserMachine::getUserByPlayerName($user);
            $user->balance += $amount;
            Poker_Accounts::IncBalance(["Player"=>$user->playername, "Amount"=> $amount]);
            $user->submitChanges();
        }
        $sql->query("UPDATE poker_deposit_transactions SET status=$status WHERE id=$id");
        echo mysqli_error();
    }
    
    public static function depositTransactionAmount($id, $amount){
        $sql = new SQLConnection;
        $amount = $amount*1;
        $sql->query("UPDATE poker_deposit_transactions SET amount=$amount WHERE id=$id AND status=".self::DEPOSIT_PENDING);
        return (mysqli_error() ? FALSE : TRUE);
    }
    
    public static function IOTransactionList($user){
        $sql = new SQLConnection();
        $name = $user->playername;
        
        $income = $sql->getArray("
        
            SELECT id, '[[Deposit]]' AS subject, amount, date 
                FROM poker_deposit_transactions 
                WHERE user='$name' AND status='".self::DEPOSIT_ACCEPTED."' 
            UNION
            SELECT id, '[[Admin]]' as subject, amount, date 
                FROM poker_chips_transactions 
                WHERE target='$name' AND amount>0 AND user!='[[Referrals]]' 
            UNION
            SELECT id, user as subject, amount, date 
                FROM poker_chips_transactions 
                WHERE target='$name' AND user!=''
            ORDER BY date DESC 
        "); 
        
        
        $outcome = $sql->getArray("
        SELECT id, target as subject, amount, date 
            FROM poker_chips_transactions 
            WHERE user='$name'
        UNION
        SELECT id, '[[Admin]]' as subject, -amount, date 
            FROM poker_chips_transactions 
            WHERE target='$name' AND user='' AND amount<0 AND `data` != '".self::PRIVATE_TOURNAMENT."'
        UNION
        SELECT id, tournament_name as subject, -amount, date 
            FROM poker_chips_transactions 
            WHERE target='$name' AND user='' AND amount<0 AND `data` = '".self::PRIVATE_TOURNAMENT."'
        UNION
        SELECT id, '[[CashOut]]' as subject, amount, date 
            FROM poker_cashout_transactions 
            WHERE status!='".self::CASHOUT_DECLINED."' AND user='$name'
        ORDER BY date DESC");
        
        
        return [
                "income"=>$income
                ,"outcome"=>$outcome
            ];
    }
	public static function totalAmountPaymentMethod($ids,$type){
		$sql = new SQLConnection;
		if($type == "deposit")
			return  $sql->getArray("select a.* , sum(c.amount) as total from poker_payment_methods a  left join poker_deposit_transactions c on c.payment_method = a.id and c.status = '2' where a.id IN ({$ids}) GROUP BY  a.id");
		else
			return  $sql->getArray("select a.* , sum(c.amount) as total from poker_payment_methods a  left join poker_cashout_transactions c on c.payment_method = a.id and c.status = '2' where a.id IN ({$ids}) GROUP BY  a.id");
    }

	 public static function getTournamentRegistrations($fromDate, $toDate, $player, $name,$creator,$inviteduser){
	     $sql = new SQLConnection();
		if($fromDate != ''){
		$fromDate = "AND a.time > '$fromDate'";
		}
		if($toDate != ''){
		$toDate = "AND a.time < '$toDate'";
		}
		if($player != ''){
		$player = "AND a.player='$player'";
		}
		if($name != ''){
		$name = "AND a.name='$name'";
		}
		if($creator != ''){
		$creator = "AND a.player='$creator'";
		}
		if($inviteduser != ''){
		$inviteduser = "AND a.player='$inviteduser'";
		}
		if($fromDate == '' && $toDate == '' && $player == '' && $name == '' && $creator == '' && $inviteduser == ''  ){
		return $sql->getAssocArray("SELECT a.* FROM poker_new_tournament_registrations a left join poker_tournament_requests c on c.name = a.name left join poker_users d on d.id = c.user_id ORDER BY time DESC");
		}
		else{
			if($player != ''){
			return $sql->getAssocArray("SELECT a.* FROM poker_new_tournament_registrations a left join poker_tournament_requests c on c.name = a.name left join poker_users d on d.id = c.user_id where d.name <> a.player {$fromDate} {$toDate} {$player} {$name} ORDER BY time DESC");
		
			}else if($creator != ''){
			return $sql->getAssocArray("SELECT a.* FROM poker_new_tournament_registrations a left join poker_tournament_requests c on c.name = a.name left join poker_users d on d.id = c.user_id where d.name = a.player {$fromDate} {$toDate} {$name} {$creator} ORDER BY time DESC");
			}
			else {
			return $sql->getAssocArray("SELECT a.* FROM poker_new_tournament_registrations a  left join poker_tournament_requests c on c.name = a.name left join poker_tournament_request_invited_users pt on pt.tournament_request_id = c.id left join poker_users d on concat(pt.user_id,',', c.invite_user_ids)  LIKE concat('%', d.id,'%')  where d.name = a.player {$fromDate} {$toDate} {$name} {$inviteduser} GROUP by a.id ORDER BY time DESC");
			}
		
		}
    }

}