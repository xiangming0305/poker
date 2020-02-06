<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
$tr = $_POST['id']*1;
$st = $_POST['status']*1;
switch($st){
    case 1:{
        $status = Poker_Transactions::CASHOUT_ACCEPTED;
        $result = "<span class='accepted'>Accepted</span>";
        break;
    }
    case -1:
    default:{
        $status = Poker_Transactions::CASHOUT_DECLINED;
        $result = "<span class='declined'>Declined</span>";
    }
}
    Poker_Transactions::cashOutTransactionStatus($tr, $status, $note);
	
	if (isset($_POST['note']) && isset($_POST['user'])){
		$user = new User($_POST['user']);
		$message = new Message();
		$message->to_name = $user->playername;
		$message->attachment = '';
		$message->message = Core::escape($_POST['note']);
		$message->submitChanges();
	}
    die(json_encode(array("status"=>"OK", "data"=>$result)));
