<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
$rq = $_POST['id']*1;
$st = $_POST['status']*1;

switch($st){
    case 1:{
        $status = Poker_Transactions::DEPOSIT_ACCEPTED;
        $result = "<span class='accepted'>Accepted</span>";
        break;
    }
    case -1:
    default:{
        $status = Poker_Transactions::DEPOSIT_DECLINED;
        $result = "<span class='declined'>Declined</span>";
    }
}
    Poker_Transactions::depositTransactionStatus($rq, $status);
    echo mysqli_error();

	if (isset($_POST['note']) && isset($_POST['user'])){
		$user = new User($_POST['user']);
		$message = new Message();
		$message->to_name = $user->playername;
		$message->attachment = '';
		$message->message = Core::escape($_POST['note']);
		$message->submitChanges();
	}

    die(json_encode(array("status"=>"OK", "data"=>$result)));
