<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";

$email = Core::escape(trim($_POST['email']));

if (!UserMachine::isUserByEmail($email)) {
    die(json_encode(array("status" => "ERROR", "message" => "No user with given email found!"), true));
}

$user = UserMachine::getUserByEmail($email);

$reset_password_token = md5($user->getId() . time());

$user->upateResetPasswordToken($reset_password_token);

$mailer = new Mailer();
$resetUrl = "http://{$_SERVER['HTTP_HOST']}/auth/reset_password.php?token={$reset_password_token}";
$content = "
Hello {$user->playername}
Click link below to reset your password: </br>
<a href='{$resetUrl}' target='_blank'>{$resetUrl}</a> <br/> <br/>
";

$result = $mailer->send($user->email, 'Reset password instruction', $content);

if ($result === true) {
    echo json_encode(["status"=>"OK","data"=>"We sent reset password link to your email!"],true);
} else {
    die(json_encode(["status"=>"ERROR","data"=> $result]));
}