<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $user = UserMachine::getCurrentUser();
    $data = json_encode([
      'company' => Core::escape($_POST['company']),
        'position' => Core::escape($_POST['position']),
        'country' => Core::escape($_POST['country']),
        'hear_about_us' => Core::escape($_POST['hear_about_us']),
        'how_bring_players' => Core::escape($_POST['how_bring_players']),
        'how_many_players' => Core::escape($_POST['how_many_players']),
    ]);
    
    if(AffiliateRequests::hasRequest($user)){
        die(json_encode(array("status"=>"ERROR","message"=>"You have already sent a request!"),true));
    }
    
    if(AffiliateRequests::request($user, $data)){
        die(json_encode(array("status"=>"OK","data"=>"Request successfully sent!"),true));
    }else{
        die(json_encode(array("status"=>"ERROR","message"=>"An unexpected error happened. Please, try again a bit later. "),true));
    }