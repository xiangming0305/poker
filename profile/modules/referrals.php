<?php
    
require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $id = Core::escape($_POST['id']);

    $current_user = UserMachine::getCurrentUser();
    $user = new User($id);
    $refs = $user->getReferrals();
    $sql = new SQLConnection;
    
    $temp = $sql->getArray("SELECT * FROM poker_player_rake");
    $rakeUser = [];
   	
   	if($temp){
        foreach ($temp as $key => $value) {
            $rakeUser[$value['player_name']] = $value['total_rake'];
        }
    }
    // echo "<pre>";var_dump($rakeUser);die();
    $result = array_map(function($el){
        global $rakeUser, $current_user;
        $ret = array("name"=>$el->playername, "id"=>$el->getId(), "tournaments_fee"=>$el->tournaments_fee, "points_dec"=>$el->points_dec,'hand_rake'=>$rakeUser[$el->playername]?$rakeUser[$el->playername]:0);
        if ($current_user->show_email) $ret["email"] = $el->email;
        if ($current_user->show_realname) $ret["realname"] = $el->realname;
        return $ret;
    },$refs);
    
    echo json_encode(array("status"=>"OK","data"=>$result));