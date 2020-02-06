<?php

if (isset($_POST['count_message'])) {
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";

    $unread_messages = 0;
    if (UserMachine::getCurrentUser() != null) {
        $unread_messages = count(Message::getInbox(UserMachine::getCurrentUser()->playername, true));

    }
    echo $unread_messages; die;
}

require_once $_SERVER['DOCUMENT_ROOT']."/profile/auth.php";
$user = UserMachine::getCurrentUser();

$cookie = "918462935623654682";

if ($_COOKIE['adssid']==$cookie && isset($_GET['player']) && !empty($_GET['player'])){
    $user = UserMachine::getUserByPlayerName(Core::escape($_GET['player']));
}

?>
<!doctype html>
<html>
<head>
    <?php require_once $_SERVER['DOCUMENT_ROOT']."/views/head.php";?>

    <title>Contacts</title>
    <link rel='stylesheet' href='/css/profile.css' />
    <script src='/js/contact.js'></script>
</head>

<body>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/views/header.php";?>
<main id="contacts" class="content">
    <div class='hello'>
        <h2>Contact an administrator</h2>
        <p>In this page, you can see your received messages and send new ones.</p>
    </div>
    <a href='' id="newMessage_button" class='button'>Send a message</a>

    <ul class='nav'>
        <li><a href='' class='inbox active'>Inbox</a></li>
        <li><a href='' class='outbox'>Outbox</a></li>
    </ul>
    <table id="tableInbox" style="width:100%">
        <thead>
        <td>Date Received</td>
        <td>Message</td>
        <td>Attachment</td>
        <td></td>
        </thead>
        <tbody><?php
        foreach(Message::getInbox($user->playername) as $msg){
            echo "<tr class='".($msg->mark_read?'':'message-unread')."'>
                    <td>{$msg->date}</td>
                    <td>".str_replace("\n", "<br/>", $msg->message)."</td>
                    <td>" . ($msg->attachment ? "<a href='/uploads/{$msg->attachment}' target='_blank' >".$msg->attachment."</a>" : '')  . "</td>
                    <td><input type='button' class='mark_read' title='Mark as Read' data-id='{$msg->id}'>
                    <input type='button' class='reply' data-id='{$msg->id}' title='Reply'>
                    </td>
                </tr>";
        }?>
        </tbody>
    </table>
    <table id="tableOutbox" style="display: none; width:100%">
        <thead>
        <td>Date Sent</td>
        <td>Message</td>
        <td>Attachment</td>
        </thead>
        <tbody><?php
        foreach(Message::getOutbox($user->playername) as $msg){
            echo "<tr>
                    <td>{$msg->date}</td>
                    <td>".str_replace("\n", "<br/>", htmlentities($msg->message))."</td>
                    <td>" . ($msg->attachment ? "<a href='/uploads/{$msg->attachment}' target='_blank' >".$msg->attachment."</a>" : '')  . "</td>
                </tr>";
        }?>
        </tbody>
    </table>

    <div id='newMessage'>
        <div class='wrap'>
            <hgroup>
                <h3>Send a message to an administrator</h3>
            </hgroup>
            <div class='container'>
                <form id='newMessage_form' method='post' enctype="multipart/form-data" action="">
                    <textarea name="message" required rows="20" style="width:100%" maxlength="2000" placeholder='Enter your message here'></textarea>
                    <span for="">Attachment: </span>
                    <input type="file" name="attachment" id="attachment"><br>
                    Only file types jpeg, jpg, png, bmp, doc, docx, pdf, ppt, pptx allowed
                    <p class='buttons'>
                        <input type='submit' class="button" value='Submit' />
                    </p>
                    <p class='status'></p>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/views/footer.php";?>
</body>
</html>