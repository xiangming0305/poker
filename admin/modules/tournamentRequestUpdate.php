<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";
$action = trim($_REQUEST['action']);

switch ($action) {
    case 'update':
        $id = $_POST['id'];
        $column = $_POST['key']; //lmao
        $value = Core::escape($_POST['value']);
        PrivateTournament::updateColumns($id, [$column => $value]);
        die(json_encode(['status' => 'OK', 'data' => '']));
        break;
    default:
        break;
}