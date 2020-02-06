<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/views/top.php";

$token = Core::escape(trim($_GET['token'] ?? null));
if (!$token) header('Location: /');

$validToken = false;
$user = UserMachine::getUserByResetPasswordToken($token);

if ($user) {
    $validToken = true;
}

if (!$user) header('')
?>
<!doctype html>
<html>
<head>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/head.php"; ?>

    <title>My Profile</title>
    <link rel='stylesheet' href='/css/profile.css'/>
    <script src='/js/profile.js'></script>
</head>

<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/header.php"; ?>
<main>

    <div class="text-center">
        <?php
        if ($validToken) { ?>

            <div class='container'>
                <form id='change_password_form' method='POST' class="common">
                    <fieldset>
                        <label>
                            <span>New password</span>
                            <input type='password' name='password' id='change_password-password' required/>
                        </label>
                        <label>
                            <span>Confirm password</span>
                            <input type='password' required name='confirmpassword' id='change_password-confirmpassword'
                                   required/>
                        </label>
                        <input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
                        <input type='submit' id='changePasswordSubmit' class="button" value='Change password'/>
                        <input type='button' id='closeForm' class="button" value='Cancel'
                               style="float: right; margin-right: 50%; background-color: #ccc;color: #000;"/>
                        <p class='status'></p>
                    </fieldset>
                </form>
            </div>
        <?php } else { ?>
            <p>You request is not valid!</p>
        <?php } ?>

    </div>

</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/views/footer.php"; ?>
</body>
</html>