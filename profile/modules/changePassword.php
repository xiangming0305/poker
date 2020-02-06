<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";

    $data = Core::escapeArray($_POST);

    $user = $data['token'] ? UserMachine::getUserByResetPasswordToken($data['token']) : UserMachine::getCurrentUser();

    if($data['password']!=$data['confirmpassword']){

        die('{"status":"ERROR","message":"Confirmed password is not equal to password!"}');
    }

    $api_result = Poker_Accounts::Edit(["Player"=>$user->playername, "PW"=>$data['password']]);
    if ($api_result['Result'] != 'Ok') {
        die(json_encode(array("status"=>"ERROR","message"=>'Could not modify password : ' . $api_result['Error'])));
    }

    $sql = new SQLConnection();
    $q = $data['token']
        ? "UPDATE poker_users SET password='{$data['password']}', reset_password_token = NULL WHERE name='".$user->playername."'"
        : "UPDATE poker_users SET password='{$data['password']}' WHERE name='".$user->playername."'";

    $sql -> query($q);

    if($sql->error()) die(json_encode(array("status"=>"ERROR","message"=>"An unexpected error happened. Please, try again a bit later : ".$sql->error()),true));
    else die(json_encode(array("status"=>"OK","data"=>"Password successfully changed!"),true));
