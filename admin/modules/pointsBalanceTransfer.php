<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $user = new User($_POST['player']);
    $amount = $_POST['amount']*1;
    $type = $_POST['type'];
    
    if($type)
        $amount = 0+$amount;
    else
        $amount = 0-$amount;

    if ($user->points + $amount < 0) die(json_encode(array("status"=>"ERROR","message"=>'Not enough points in balance to widthdraw')));

    $sql = new SQLConnection;

    $sql->query("INSERT INTO  admin_points_transfer(amount, player_name) VALUES ({$amount},'{$user->playername}')");
    $sql->query("UPDATE poker_users SET points = points + {$amount} WHERE name = '{$user->playername}'");

    die(json_encode(array("status"=>"OK","data"=>$user->getPointBalance() + $amount)));