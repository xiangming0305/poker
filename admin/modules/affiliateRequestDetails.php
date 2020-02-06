<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
    $id = $_POST['id'];

    $affiliateRequest = AffiliateRequests::getUserRequestById($id);
    echo json_encode(array("status"=>"OK", "data"=>json_decode($affiliateRequest['data'], true)));