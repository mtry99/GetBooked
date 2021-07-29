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

$sql = "SELECT b.rarity, sum(i.amount) as amount
        FROM user_inventory as i
        LEFT JOIN book as b 
        ON i.book_id = b.book_id 
        WHERE user_id = '$uid'
        GROUP BY b.rarity
        ORDER BY b.rarity;";
$result = $conn->query($sql);
if(!$result) {
    $response["message"] = 'Inventory query error: '.$inventory_query;
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$rate = 0;
while($row = $result->fetch_assoc()) {
    $rate += pow(6, $row["rarity"]) * $row["amount"];
}

$sql = "UPDATE user 
        SET bbuck = bbuck + $rate * (ROUND(UNIX_TIMESTAMP(CURTIME(4)) * 1000) - bbuck_last_updated) / 1000,
        bbuck_last_updated = ROUND(UNIX_TIMESTAMP(CURTIME(4)) * 1000)
        WHERE user_id = '$uid';";
$result = $conn->query($sql);

if(!$result) {
    $response["message"] = 'User query error: '.$inventory_query;
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$sql = "SELECT bbuck FROM user WHERE user_id = '$uid';";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$response["bbuck"] = $row["bbuck"];
$response["rate"] = $rate;
$response["message"] = "success";

$response["success"] = true;

if(!isset($fromPhp)) {
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}