<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/views/top.php";

    $cookie = "918462935623654682";

    $user = UserMachine::getCurrentUser();
    //var_dump($user);die();

    if ($_COOKIE['adssid']==$cookie && isset($_GET['player']) && !empty($_GET['player'])){
       $user = UserMachine::getUserByPlayerName(Core::escape($_GET['player']));
    }

    if($user==NULL){
        header("Location: /");
        die();
    }
    
    // $user = UserMachine::getCurrentUser();