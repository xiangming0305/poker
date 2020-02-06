<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $data = Core::escapeArray($_POST);
    /*
    playername, email, password, confirmpassword, realname, affiliatecode, confirmpassword, location, sex
    */

    include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false) {
        die('{"status":"ERROR","message":"You entered an incorrect code!"}');
    }

    
    if($data['password']!=$data['confirmpassword']){
        
        die('{"status":"ERROR","message":"Confirmed password is not equal to password!"}');
    }


    # Check, if user with such playername already exists
    if (UserMachine::isUserByPlayerName($data['playername'])){
        
        die(json_encode(array("status"=>"ERROR","message"=>"User with this player name already exists!")));
    }
    
    # Check if user with such email already exists
    if (UserMachine::isUserByEmail($data['email'])){
        die(json_encode(array("status"=>"ERROR","message"=>"User with this e-mail already exists!")));    
    }
    
    // echo "<pre>";var_dump($data);die();
    
    
    
    ## Check if user with this affiliatecode exists
    
    if(UserMachine::register($data)){
        
        echo json_encode(array("status"=>"OK","data"=>"Registration successfull."),JSON_UNESCAPED_UNICODE);
    }
    else{
        die(json_encode(array("status"=>"ERROR","message"=>mysqli_error())));
    }

