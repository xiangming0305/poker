<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $user = UserMachine::getCurrentUser();

    if (!UserMachine::tournamentRegisterLocal($_POST['tournament'], $user)) {
        if(UserMachine::isTournamentRegisteredLocal($_POST['tournament'], $user)) die(json_encode(array("status"=>"error", "message"=>"You already registered!")));
        else die(json_encode(array("status"=>"error", "message"=>"You do not have enough points!")));
    }

    $res = Poker_Tournaments::Register(["Name"=>$_POST['tournament'], "Player"=>$user->playername, "Negative"=>"Allow"]);

    
    // require_once $_SERVER['DOCUMENT_ROOT']."/cron/tournament.php";
    
    if(strtolower($res['Result'])=="error"){
        die(json_encode(array("status"=>"error", "message"=>"You already registered!")));    
    }
    die(json_encode(array("status"=>"OK", "data"=>$res)));