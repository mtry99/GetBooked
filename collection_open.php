<?php

require_once "config.php";

$response = ["success" => false];
$response = ["message" => "failed"];

if(!isset($_GET["id"])) {
    $response["message"] = "invalid collection id";
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

if(!isset($_SESSION["uid"])) {
    $response["message"] = "invalid user id";
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$uid = $_SESSION["uid"];

$collection_id = $_GET["id"];

$collection_id_query = 'WHERE collection.collection_id = "'.$collection_id.'"';

$collection_sql = sprintf('
SELECT * FROM collection
%s
LIMIT 1;',
$collection_id_query);

$collection_result = $conn->query($collection_sql);
$collection_row = $collection_result->fetch_assoc();

$sql = sprintf('
SELECT *
FROM collection_book as collection
LEFT JOIN book as b 
ON collection.book_id = b.book_id
%s;',
$collection_id_query);

$result = $conn->query($sql);

$collection_books = [];

for ($i = 1; $i <= 5; $i++) {
    $collection_books[$i] = [];
}

while($row = $result->fetch_assoc()) {
    array_push($collection_books[$row['rarity']], $row);
}

$chance = [0,256,128,9,3,1];
$total = 0;
for($r = 1; $r <= 5; $r++) {
    if(count($collection_books[$r]) != 0) {
        $total += $chance[$r];
        $chance[$r] = $total - $chance[$r];
    }
}

$book_r = 0;

$rng = rand(0, $total - 1);
for($r = 5; $r >= 1; $r--) {
    if($rng >= $chance[$r]) {
        $book_r = $r;
        break;
    }
}

if($book_r == 0) {
    $response["message"] = "invalid book id";
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

$book_idx = rand(0, count($collection_books[$book_r]) - 1);

// add to inventory

$inventory_query = '("'.$uid.'","'.$collection_books[$book_r][$book_idx]["book_id"].'","1")';

$sql = sprintf('
INSERT INTO user_inventory (user_id,book_id,amount) 
VALUES %s
ON DUPLICATE KEY UPDATE amount=amount+1;',
$inventory_query);

$result = $conn->query($sql);

if(!$result) {
    $response["message"] = "error inserting into inventory";
    header('Content-type: application/json');
    echo json_encode($response);
    die();
}

// get book detail

$book_id_query = 'WHERE book.book_id = "'.$collection_books[$book_r][$book_idx]["book_id"].'"';

$sql = sprintf('
SELECT c.*,
GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, 
p.publisher_id, p.name as "publisher_name", bp.publish_year
FROM (SELECT b.count, b.original_key, b.isbn, b.number_of_pages, 
     b.language, b.book_id, b.title, 
     GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
     FROM (SELECT * FROM book 
          %s
          ORDER BY book.book_id ASC
          LIMIT 1) as b
     LEFT JOIN book_author ba ON b.book_id = ba.book_id
     LEFT JOIN author a ON ba.author_id = a.author_id
     GROUP BY b.book_id) as c
LEFT JOIN book_genre bg ON c.book_id = bg.book_id
LEFT JOIN genre g ON bg.genre_id = g.genre_id 
LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
GROUP BY c.book_id;',
$book_id_query);

$result = $conn->query($sql);

$row = $result->fetch_assoc();

$json = file_get_contents('https://openlibrary.org/books/'.$row['original_key'].'.json');
$obj = json_decode($json, true);

$response["book"] = $row;
$response["book_detail"] = $obj;
$response["message"] = "success";

$response["success"] = true;
header('Content-type: application/json');
echo json_encode($response);
die();