<?php

require_once "config.php";
require_once "utils.php";

if(!isset($_GET["id"])) {
    header('Location: collection.php');
}

if(!isset($_SESSION["uid"])) {
    header('Location: login.php');
}

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

?>

<script>

let sql = `<?php echo ($sql); ?>`;
let obj = {<?php 
for ($i = 1; $i <= 5; $i++) {
    echo $i.':[';
    foreach($collection_books[$i] as $j => $book) {
        echo json_encode($book);
        echo ',';
    }
    echo '],';
}
?>};

console.log(sql);
console.log(obj);

</script>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Collection Detail</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/collection.css">
    <link rel="stylesheet" href="css/book3d_rarity_5.css">
    <link rel="stylesheet" href="css/book3d_rarity_4.css">
    <link rel="stylesheet" href="css/book3d_rarity_3.css">
    <link rel="stylesheet" href="css/book3d_rarity_2.css">
    <link rel="stylesheet" href="css/book3d_rarity_1.css">

</head>
<body>

    <?php require "header.php"; ?>

    <!-- Modal -->
    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">You Received:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body rarity_container" style="padding: 1rem 0;">
                <div id="modal-book-box" class="book_box">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="onDismissModal()" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <h4 class="font-size38 text-center mt-3 mb-1">
        <?php echo strtoupper($collection_row["name"]); ?>
    </h4>

	<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">
            <div class="rarity_container" style="padding-right: 0rem;">
                <div class="canvasContainer">
                    <canvas id="canvas"></canvas>
                    <button id="buttonOpen" class="btn btn-danger" onclick="onOpenClick()" type="button">OPEN</button>
                </div>
            </div>
            <?php
            $rarity_heights = [0, 5.0, 5.0, 7.0, 10.0, 15.0];
            for ($i = 5; $i >= 1; $i--) {
                if(empty($collection_books[$i])) continue;
                ?>
                <div class="rarity_container">
                    <?php
                    foreach($collection_books[$i] as $j => $book) {
                        ?>
                        <div class="book_box book_box_rarity_<?php echo $i; ?>">
                            <div id="book-container3d-<?php echo $i; ?>-<?php echo $j; ?>" class="book-container3d">
                                <div class="book3d">
                                    <div class="book3d-cover cover-title" id="cover-title-<?php echo $i; ?>-<?php echo $j; ?>"><?php echo $book["title"]; ?></div>
                                    <img class="book3d-cover" id="cover-<?php echo $i; ?>-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
                                    <div class="book3d-cover-back cover-title" id="cover-back-title-<?php echo $i; ?>-<?php echo $j; ?>"></div>
                                    <img class="book3d-cover-back" id="cover-back-<?php echo $i; ?>-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
                                </div>
                            </div>
                            <div class="star-container-container">
                                <div class="text-center star-container">
                                    <?php
                                    for ($k = $i; $k >= 1; $k--) {
                                        ?>
                                        <span class="fa fa-star"></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <script src="js/collection_detail.js"></script>

</body>
<?php require "footer.php"; ?>
</html>