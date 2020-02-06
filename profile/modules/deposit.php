<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";
$user = UserMachine::getCurrentUser();
$amount = Core::escape($_POST['amount']) * 1;
$paymentMethod = addslashes($_POST['payment_method']);
$paidFrom = addslashes($_POST['paid_from']);
$paidFromAccount = addslashes($_POST['paid_from_account']);
$paidFromCountry = addslashes($_POST['paid_from_country']);
$transactionId = addslashes($_POST['transaction_id']);

$res = Poker_Transactions::depositTransaction($user, $amount, $paymentMethod, $paidFrom, $transactionId, $paidFromAccount, $paidFromCountry);

if ($res) {
    die(json_encode(array("status" => "OK", "data" => $user->balance)));
} else {
    echo mysqli_error();
    die(json_encode(array("status" => "ERROR", "message" => "An error occured!")));
}