<?php

    date_default_timezone_set("Europe/Minsk");
    
    if(!$root) $root = $_SERVER['DOCUMENT_ROOT'];
    require_once $root."/classes/Classes.php";
    
    $users = [];
    $freeroll = [];
    $users_aff = [];
    $freeroll_aff = [];
    $users_aff2 = [];
    $freeroll_aff2 = [];
    $files = Poker_Cache::getTournamentResults();   
    // echo "<pre>";print_r($files);die();
    foreach($files as $file){
        $data = Poker_Calculations::getTournamentFeeArray($file);
        $dataFreeroll =  Poker_Calculations::getFreerollArray($file);
        if (strpos($file['buyin'], '+0')) {
            foreach ($dataFreeroll as $u => $v) {
                if (!isset($freeroll[$u])) {
                    $freeroll[$u] = 0;
                    $freeroll_aff[$u] = 0;
                }
                $freeroll[$u] += $v * 1;

                $user_obj = new User($u, 'name');
                $affiliate = $user_obj->getParentAffiliate();
                $affiliate2 = $affiliate->getParentAffiliate();
                if ($affiliate->isAffiliate() && $affiliate->getAffiliateStartDate() <= $file['stop']) $freeroll_aff[$u] += $v * 1;
                if ($affiliate2->isAffiliate() && $affiliate2->getAffiliateStartDate() <= $file['stop']) $freeroll_aff2[$u] += $v * 1;
            }
        }
        else {
            foreach ($data as $u => $v) {
                if (!isset($users[$u])) {
                    $users[$u] = 0;
                    $users_aff[$u] = 0;
                }
                $users[$u] += $v * 1;

                $user_obj = new User($u, 'name');
                $affiliate = $user_obj->getParentAffiliate();
                $affiliate2 = $affiliate->getParentAffiliate();
                if ($affiliate->isAffiliate() && $affiliate->getAffiliateStartDate() <= $file['stop']) $users_aff[$u] += $v * 1;
                if ($affiliate2->isAffiliate() && $affiliate2->getAffiliateStartDate() <= $file['stop']) $users_aff2[$u] += $v * 1;
            }
        }
    }
    $sql = new SQLConnection;
    
    $sql->query("UPDATE poker_users SET tournaments_fee = 0");
    foreach($users as $u=>$v){
        if (!isset($users_aff[$u])) $users_aff[$u] = 0;
        if (!isset($users_aff2[$u])) $users_aff2[$u] = 0;
        $sql->query("UPDATE poker_users SET tournaments_fee = ".$v.", tournaments_fee_aff = ".$users_aff[$u].", tournaments_fee_aff2 = ".$users_aff2[$u]." WHERE name='$u'");
    }

    $sql->query("UPDATE poker_users SET points_dec = 0");
    foreach($freeroll as $u=>$v){
        if (!isset($freeroll_aff[$u])) $freeroll_aff[$u] = 0;
        if (!isset($freeroll_aff2[$u])) $freeroll_aff2[$u] = 0;
        $sql->query("UPDATE poker_users SET points_dec = ".$v.", freeroll_fee_aff = ".$freeroll_aff[$u].", freeroll_fee_aff2 = ".$freeroll_aff2[$u]." WHERE name='$u'");
    }