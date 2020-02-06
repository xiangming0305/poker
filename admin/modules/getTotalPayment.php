<?php
    
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $res = Poker_Transactions::totalAmountPaymentMethod($_POST['ids'],$_POST['type']);
    if($res){
        die(json_encode(array("status"=>"OK","data"=>$res)));
    }else{
        die(json_encode(array("status"=>"ERROR","message"=>mysqli_error())));
    }