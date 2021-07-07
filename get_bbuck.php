<?php

require_once "config.php";

$response = ["success" => false];
$response = ["message" => "failed"];

if(!isset($_SESSION["uid"])) {
    $response["message"] = "invalid user id";
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$uid = $_SESSION["uid"];

$rate = 1;

$sql = "UPDATE user 
        SET bbuck = bbuck + $rate * (ROUND(UNIX_TIMESTAMP(CURTIME(4)) * 1000) - bbuck_last_updated),
        bbuck_last_updated = ROUND(UNIX_TIMESTAMP(CURTIME(4)) * 1000)
        WHERE user_id = '$uid';";
$result = $conn->query($sql);

if(!$result) {
    $response["message"] = 'Query error: '.$inventory_query;
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$sql = "SELECT bbuck FROM user WHERE user_id = '$uid';";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$response["bbuck"] = $row["bbuck"];
$response["message"] = "success";

$response["success"] = true;

if(!isset($fromPhp)) {
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}