<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    $user = UserMachine::getCurrentUser();

    if(!isset($_POST['message'])){
        die(json_encode(array("status"=>"ERROR","message"=>"Message is empty!"),true));
    }

    $sendToEveryone = false;
    $message = new Message();
    if (isset($_POST['to_name'])) {
        if (!UserMachine::isAdmin()) die(json_encode(array("status"=>"ERROR","message"=>"You are not an administrator!"),true));
        if ($_POST['to_name'] == '$EVERYONE$') $sendToEveryone = true;
        else $message->to_name = $_POST['to_name'];
    }
    else $message->from_name = $user->playername;
    $message->message = Core::escape($_POST['message']);
    if (isset($_POST['answer_from']) && $_POST['answer_from']>0) $message->answer_from = Core::escape($_POST['answer_from']);

    if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0) {
        $imageFileType = strtolower(pathinfo($_FILES["attachment"]["name"],PATHINFO_EXTENSION));
        if ($_FILES["attachment"]["size"] > 15 * 1024 * 1024) {
            die(json_encode(['status' => 'ERROR', 'message' => 'Your file can not larger than 15MB']));
        }

        if (!in_array($imageFileType, ['jpg', 'gif', 'png', 'jpeg', 'pdf', 'doc', 'docx', 'ppt', 'pptx'])) {
            die(json_encode(['status' => 'ERROR', 'message' => 'Your file type is not allowed']));
        }

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
        $newFileName = time() . rand(10,999) . '.' . $imageFileType;
        $target_file = $target_dir . '/' . $newFileName;

        move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);

        $message->attachment = $newFileName;
    } else {
        $message->attachment = '';
    }

if ($sendToEveryone) {
        $count = $message->sendToEveryone();
        die(json_encode(array("status" => "OK", "data" => "$count messages successfully sent!"), true));
    }
    else {
        $message->submitChanges();
        die(json_encode(array("status" => "OK", "data" => "Message successfully sent!"), true));
    }