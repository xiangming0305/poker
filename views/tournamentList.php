<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    Poker_Grabber::grabTournamentList();
    $user = UserMachine::getCurrentUser();
    $temp = Poker_Cache::getOpenEnabledTournaments();
    foreach($temp as $i=>$v){
        $accepted = UserMachine::isTournamentRegisteredLocal($v['name'],$user);
        $temp[$i]["accepted"] = $accepted ? 0 : 1;
        
        if(!$user){
            $temp[$i]["accepted"]=-1;
        }
        if($user){
            if($temp[$i]["accepted"]){
                // if($user->getPointBalance() < $temp[$i]["point_fee"]){
                //     $temp[$i]["accepted"] = -2;
                // }
                if(Poker_Calculations::isTournamentPlaying($temp['name'])){
                    if($temp[$i]['accepted']==0){
                        $temp[$i]['accepted'] = -3;    
                    }
                    
                }
            }
        }
    }
    
    die(json_encode(array("status"=>"OK","data"=>$temp)));