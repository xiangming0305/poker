<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
?>

<div id="inbox">
<hgroup>
    <h2>Inbox / Outbox</h2>
    <p>You can reply here, if you want to send a message to a specific Affiliate, use the Manage affiliate page</p>
</hgroup>
<input type='button' class="button" value='Send message to everyone' id='send_everyone' />
<ul class='nav'>
    <li><a href='' class='inbox active'>Inbox</a></li>
    <li><a href='' class='outbox'>Outbox</a></li>
</ul>
<table id="tableInbox" style="width:100%">
    <thead>
    <td>From</td>
    <td>Date Received</td>
    <td>Message</td>
    <td>Attachment</td>
    <td></td>
    </thead>
    <tbody><?php
    foreach(Message::getInbox() as $msg){
        echo "<tr class='".($msg->mark_read?'':'message-unread')."'>
                     <td>{$msg->from_name}</td>
                    <td>{$msg->date}</td>
                    <td>".str_replace("\n", "<br/>", htmlentities($msg->message))."</td>
                    <td>" . ($msg->attachment ? "<a href='/uploads/{$msg->attachment}' target='_blank' >".$msg->attachment."</a>" : '')  . "</td>
                    <td><input type='button' class='mark_read' title='Mark as Read' data-id='{$msg->id}'>
                    <input type='button' class='reply' data-id='{$msg->id}' data-playername='{$msg->from_name}' title='Reply'>
                    </td>
                </tr>";
    }?>
    </tbody>
</table>
<table id="tableOutbox" style="display: none; width:100%">
    <thead>
    <td>To</td>
    <td>Date Sent</td>
    <td>Message</td>
    </thead>
    <tbody><?php
    foreach(Message::getOutbox() as $msg){
        echo "<tr>
                    <td>{$msg->to_name}</td>
                    <td>{$msg->date}</td>
                    <td>".str_replace("\n", "<br/>", htmlentities($msg->message))."</td>
                </tr>";
    }?>
    </tbody>
</table>

<div id='newMessage'>
    <div class='wrap'>
        <hgroup>
            <h3>Send a message</h3>
        </hgroup>
        <div class='container'>
            <form id='newMessage_form' method='post'>
                <textarea name="messageContent" required rows="20" style="width:100%" maxlength="2000" placeholder='Enter your message here'></textarea>
                <p class='buttons'>
                    <input type='submit' class="button" value='Submit' />
                </p>
                <p class='status'></p>
            </form>
        </div>
    </div>
</div>
</div>