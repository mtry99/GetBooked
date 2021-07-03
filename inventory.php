<?php

require_once "config.php";
require_once "utils.php";

if(!isset($_SESSION["uid"])) {
    header('Location: login.php');
}

$uid = $_SESSION["uid"];

$inventory_uid_query = 'WHERE i.user_id = "'.$uid.'"';

$sql = sprintf('
SELECT *
FROM user_inventory as i
LEFT JOIN book as b 
ON i.book_id = b.book_id
%s;',
$inventory_uid_query);

$result = $conn->query($sql);

$inventory_books = [];

while($row = $result->fetch_assoc()) {
    array_push($inventory_books, $row);
}

usort($inventory_books, function ($a, $b) {
    if($b["rarity"] == $a["rarity"]) {
        return $b["amount"] - $a["amount"];
    }
    return $b["rarity"] - $a["rarity"];
});

?>

<script>

let sql = `<?php echo ($sql); ?>`;
let inventory = [<?php 
foreach($inventory_books as $j => $book) {
    echo json_encode($book);
    echo ',';
}
?>];

console.log(sql);
console.log(inventory);

</script>

<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Inventory</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">
    <link rel="stylesheet" href="css/collection.css">
    <link rel="stylesheet" href="css/book3d_rarity_5.css">
    <link rel="stylesheet" href="css/book3d_rarity_4.css">
    <link rel="stylesheet" href="css/book3d_rarity_3.css">
    <link rel="stylesheet" href="css/inventory.css">

</head>
<body>

    <?php require "header.php"; ?>

    <h4 class="font-size38 text-center mt-3 mb-1">
        My Inventory
    </h4>

	<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">
            <div class="inventory_container">
                <?php
                foreach($inventory_books as $j => $book) {
                    $rarity = $book["rarity"];
                    ?>
                    <div class="book_box book_box_rarity_<?php echo $rarity; ?>">
                        <div id="book-container3d-<?php echo $j; ?>" class="book-container3d">
                            <div class="book3d">
                                <div class="book3d-cover cover-title" id="cover-title-<?php echo $j; ?>"><?php echo $book["title"]; ?></div>
                                <img class="book3d-cover" id="cover-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
                                <div class="book3d-cover-back cover-title" id="cover-back-title-<?php echo $j; ?>"></div>
                                <img class="book3d-cover-back" id="cover-back-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
                            </div>
                        </div>
                        <div class="star-container-container">
                            <div class="text-center star-container">
                                <?php
                                for ($k = $rarity; $k >= 1; $k--) {
                                    ?>
                                    <span class="fa fa-star"></span>
                                    <?php
                                }
                                ?>
                            </div>
                            <div id="book-popover-<?php echo $j; ?>" class="book-popover"
                                data-container="body" 
                                data-html="true" 
                                data-toggle="popover" 
                                data-placement="bottom" 
                                data-trigger="manual">
                            </div>
                        </div>
                        <?php if($book["amount"] != 1) { ?>
                        <div class="amount-container">
                            <div class="text-center amount">
                                <?php
                                    echo '×'.$book["amount"];
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div id="book-popover-content" class="hidden">
        <div>
        </div>
    </div>
    
    <script src="js/inventory.js"></script>

</body>
<?php require "footer.php"; ?>
</html>