<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    if(!UserMachine::isUserByPlayerName(Core::escape($_POST['playername']))){
        die(json_encode(array("status"=>"ERROR","message"=>"No user with given player name found!"),true));
    }
    
    $user = UserMachine::getUserByPlayerName(Core::escape($_POST['playername']));
    
    if($user->password!=UserMachine::passwordHash($_POST['password'])){
        // print_r($user);
        // echo UserMachine::passwordHash($_POST['password']);
        die(json_encode(array("status"=>"ERROR","message"=>"Password is incorrect!"),true));
    }
    
    UserMachine::authUser($user);
    echo json_encode(array("status"=>"OK","data"=>"Authorization completed successfully."),true);