<?php

    date_default_timezone_set("Asia/Beirut");
    
    if(!$root) $root = $_SERVER['DOCUMENT_ROOT'];
    require_once $root."/classes/Classes.php";
    
    
    $sql = new SQLConnection();


    
    if(strtolower($_POST['Event'])=='tourneyfinish'){
        $files = Poker_Grabber::putTournaments();
        $name = $_POST['Name'];
        $number = $_POST['Number'];
        $time = $_POST['Time']; 
        $temp = $sql->getArray("SELECT * FROM poker_cache_tournaments WHERE name='$name' AND (restart_time>0 OR lateregminutes>0 OR entryfee>0)");

        $params = ["Name"=>$name];
        #creating same tournament via API
        if($temp[0]['restart_time']){
            $time = date("Y-m-d H:i",strtotime($time)+60*$temp[0]['restart_time']);
            $params['StartTime'] = $time;
        }

        if($temp[0]['lateregminutes']){
            
            $params['LateRegMinutes'] = $temp[0]['lateregminutes'];
        }
        if($temp[0]['entryfee']){
            
            $params['EntryFee'] = $temp[0]['entryfee'];
        }

        file_put_contents("log","\n---\n". ": delete tournament {$res['Name']} "."\n ",FILE_APPEND);
        if (strpos($name, Poker_Tournaments::PREFIX) === 0) {
            PrivateTournament::delete($name);
        } else {
            $res = print_r(Poker_Tournaments::Offline(["Name" => $name, "Now" => "Yes"]), true) . "\n";
            $res .= print_r(Poker_Tournaments::Edit($params), true) . "\n";
            $res .= print_r(Poker_Tournaments::Online(["Name" => $name]), true) . "\n";
        }

        file_put_contents("log","\n---\n".$_POST['Time'].": Restarted tournament $name on $time ".$res." \n".mysqli_error()."\n ",FILE_APPEND);



        if(Poker_Tickets::isTicketTournament($name)){
            $files = Poker_Grabber::putTournaments();

            foreach($files as $f){
                if($f['Tournament'] != $name || $f['Number'] != $number || $f['Date'] !=date('Y-m-d',strtotime($_POST['Time'])))
                    continue;
                $tickets = Poker_Tickets::getTicketsOf($name);            
                foreach($tickets as $t){
                    $places = $t['places']*1;
                    $target = $t['tournament_for'];
                    $players = $f['places'];
                    
                    for($i=1; $i<=$places; $i++){
                        preg_match("/([^\s]*)\s.*/",$players[$i],$pname);
                        $pname=$pname[1];
                        
                        $res = Poker_Tournaments::Register(["Name"=>$target, "Player"=>$pname]);
                        $u=UserMachine::getUserByPlayerName($tpname);
                        UserMachine::trySendMessage($u, $m);
                        
                        // print_r($res);
                        file_put_contents("log","\n".date("Y-m-d H:i:s")."\nRegistered at ticket tournament $target player {$pname}", FILE_APPEND);
                    }
                }
            }
        }
         require "tournament_fees.php";

    }
    
    // foreach($files as $f){
    //     # For each new tournament result line, check if it must restart 
    //     $name = $f["Name"];
    //     file_put_contents("log","\n---\n".date("Y-m-d H:i:s").": Tournament ".json_encode($f)." \n",FILE_APPEND);
    //     $temp = $sql->getArray("SELECT * FROM poker_cache_tournaments WHERE name='$name' AND (restart_time>0 OR lateregminutes>0)");
    //     if(count($temp)){
    //         $params = ["Name"=>$name];
    //         #creating same tournament via API
    //         if($temp[0]['restart_time']){
    //             $time = date("Y-m-d H:i", time()+60*$temp[0]['restart_time']);
    //             $params['StartTime'] = $time;
    //         }

    //         if($temp[0]['lateregminutes']){
                
    //             $params['LateRegMinutes'] = $temp[0]['lateregminutes'];
    //         }
    //         echo "<p>before update</p>";
    //         echo "<pre>";print_r(Poker_Tournaments::GET(["Name"=>$name]));
    //         echo "<p>after update</p>";
    //         $res = print_r(Poker_Tournaments::Offline(["Name"=>$name,"Now"=>"Yes"]),true)."\n";
    //         $res.= print_r(Poker_Tournaments::Edit($params),true)."\n";
    //         $res.= print_r(Poker_Tournaments::Online(["Name"=>$name]),true)."\n";
          

    //         echo "<pre>";print_r(Poker_Tournaments::GET(["Name"=>$name]));
    //         echo "<p>Restarted tournament $name. </p>";
    //         file_put_contents("log","\n---\n".date("Y-m-d H:i:s").": Restarted tournament $name on $time ".$res." \n".mysqli_error()."\n ",FILE_APPEND);
    //     }
        
        
    //     # For each new tournament result line, check if it is ticket tournament and register in target tournament first N places.
    //     if(Poker_Tickets::isTicketTournament($name)){
    //         $tickets = Poker_Tickets::getTicketsOf($name);
            
    //         // print_r($tickets);
            
    //         foreach($tickets as $t){
    //             $places = $t['places']*1;
    //             $target = $t['tournament_for'];
    //             $players = $f['places'];
                
    //             for($i=1; $i<=$places; $i++){
    //                 preg_match("/([^\s]*)\s.*/",$players[$i],$pname);
    //                 $pname=$pname[1];
                    
    //                 $res = Poker_Tournaments::Register(["Name"=>$target, "Player"=>$pname]);
    //                 $u=UserMachine::getUserByPlayerName($tpname);
    //                 $m="You received a ticket to take part in tournament '$target'";
    //                 UserMachine::trySendMessage($u, $m);
                    
    //                 // print_r($res);
    //                 file_put_contents("log","\n".date("Y-m-d H:i:s")."\nRegistered at ticket tournament $target player {$pname}", FILE_APPEND);
    //                 echo "<p>"."Registered at ticket tournament $target player {$pname}"."</p>";
    //             }
    //         }
    //     }
    // }
    #launching re-parsing to get new tournaments
   // $tournaments = Poker_Grabber::grabTournamentList();
    
   
?>