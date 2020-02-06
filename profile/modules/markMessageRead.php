<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $user = UserMachine::getCurrentUser();

    if(!isset($_POST['message'])){
        die(json_encode(array("status"=>"ERROR","message"=>"Message is empty!"),true));
    }

    $message = new Message(Core::escape($_POST['message']));
    if ($user->playername != $message->from_name && !UserMachine::isAdmin()) die(json_encode(array("status"=>"ERROR","message"=>"You don't have permission!"),true));

    $message->mark_read = 1;
    $message->submitChanges();
    die(json_encode(array("status"=>"OK","data"=>"Message successfully read!"),true));