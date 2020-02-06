<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $user = new User($_POST['player']);
    $amount = $_POST['amount']*1;
    $type = $_POST['type'];
    
    if($type)
        $amount = 0+$amount;
    else
        $amount = 0-$amount;

    $sql = new SQLConnection;

    $temp = $sql->getArray("SELECT * FROM admin_affiliate_user WHERE `player_name`='{$user->playername}'");
    if($temp){
        $amount = $temp[0]['amount'] + $amount;
        $sql->query("UPDATE admin_affiliate_user SET amount = {$amount} WHERE  `player_name`='{$user->playername}'");
    }else{
         $sql->query("INSERT INTO  admin_affiliate_user(amount, player_name) VALUES ({$amount},'{$user->playername}')");
         // echo "INSERT INTO  admin_affiliate_user(amount, player_name) VALUES ({$amount},'{$_POST['player']}')";
    }
    die(json_encode(array("status"=>"OK","data"=>$user->getAffilateBalance() + $amount)));