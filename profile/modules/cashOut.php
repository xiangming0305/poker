<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $user = UserMachine::getCurrentUser();
    
    if(!$user){
        die(json_encode(array("status"=>"ERROR", "message"=>"You seem to have log out before request cash out!")));
    }
    
    $amount = Core::escape($_POST['sum'])*1;
    $payment_method = addslashes($_POST['payment_method']);
	$receive_by = addslashes($_POST['receive_by']);
	$receive_by_account = addslashes($_POST['receive_by_account']);
	$receive_by_country = addslashes($_POST['receive_by_country']);
	if($payment_method == ""){
		die(json_encode(array("status"=>"ERROR", "message"=>"Payment method is required")));
	}
    if($amount <=0) {
        die(json_encode(array("status"=>"ERROR", "message"=>"You can't cash out negative or zero amount of chips!")));
    }

    $user = UserMachine::refreshBalance($user);
    if($amount >$user->balance ) {
        die(json_encode(array("status"=>"ERROR", "message"=>"You can't cash out more than you have!")));
    }
    
    $res = Poker_Transactions::cashOut($user, $amount, $payment_method, $receive_by, $receive_by_account, $receive_by_country);
    if($res===FALSE){
        die(json_encode(array("status"=>"ERROR", "message"=>"An error occured while request!")));
    }else{
        die(json_encode(array("status"=>"OK", "data"=>$res)));
    }