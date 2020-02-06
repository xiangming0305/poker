<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";

    $id = $_POST["id"];
    $user = new User($id);

    $country = '';
    if ($user->country_geolocated) {
        try {
            $curl = curl_init('http://api.ipstack.com/' . $user->country_geolocated . '?access_key=2bbdbdaacdd91a048221a144c8c3d437');
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            $country = json_decode($response, true)['country_name'] . ' ('.$user->country_geolocated.')';
            Logger::addReport("Geolocation", Logger::STATUS_INFO, 'http://api.ipstack.com/' . $user->country_geolocated . ' : ' . $country . '?access_key=2bbdbdaacdd91a048221a144c8c3d437');
        } catch (Exception $e) {
        }
    }

    // echo "<pre>";var_dump($temp,$amount);
    $data = array(
        "realname"=>$user->realname
        ,"playername"=>$user->playername
        ,"email"=>$user->email
        ,"country_geolocated"=>$country
        ,"comission"=>$user->comission
        ,"level2_comission"=>$user->level2_comission
        ,"level2"=>$user->level2
        ,"link2_commission"=>$user->link2_commission
        ,"show_realname"=>$user->show_realname
        ,"show_email"=>$user->show_email
        ,"referrals"=>[]
        ,"balance"=>$user->balance*1
        ,'affiliateBalance'=>$user->getAffilateBalance()*1+$user->getAdminChangeAffiliate()
        ,'points_balance'=>$user->getPointBalance()*1
    );



    echo(json_encode(array("status"=>"OK","data"=>$data)));