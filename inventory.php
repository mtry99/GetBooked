<?php

require_once "config.php";
require_once "utils.php";

if(!isset($_SESSION["uid"])) {
    header('Location: login.php');
}

$uid = $_SESSION["uid"];

$remove_sql = "";
$trade_sql = "";

function processTradeUp() {
    global $conn, $uid;

    $trade_up_books = explode(',', $_SESSION['postdata']["trade_up"]);

    if(count($trade_up_books) != 5) return false;
    
    $trade_up_books_query = "";
    for($i = 0; $i < 5; $i++) {
        $trade_up_books_query = $trade_up_books_query."'$trade_up_books[$i]',";
    }
    $trade_up_books_query = rtrim($trade_up_books_query, ", ");

    $sql = "SELECT rarity
            FROM book
            WHERE book_id IN ($trade_up_books_query)
            GROUP BY rarity";

    $result = $conn->query($sql);

    $row_cnt = $result->num_rows;

    if($row_cnt != 1) return false;

    $row = $result->fetch_assoc();

    $rarity = $row["rarity"] + 1;

    $trade_sql = "SELECT book_id
            FROM (SELECT collection_id 
                    FROM collection_book 
                    WHERE book_id IN ($trade_up_books_query)
                    GROUP BY collection_id) c
            NATURAL JOIN collection_book
            NATURAL JOIN book
            WHERE rarity = '$rarity'
            GROUP BY book_id";
    $result = $conn->query($trade_sql);

    $possible_books = [];

    while($row = $result->fetch_assoc()) {
        array_push($possible_books, $row["book_id"]);
    }

    if(count($possible_books) == 0) return false;

    $remove_sql = "";
    for($i = 0; $i < 5; $i++) {
        $remove_sql = "DELETE FROM user_inventory WHERE amount = 1 AND user_id = '$uid' AND book_id = '$trade_up_books[$i]';";
        $result = $conn->query($remove_sql);
        
        $remove_sql = "UPDATE user_inventory SET amount=amount-1 WHERE user_id = '$uid' AND book_id = '$trade_up_books[$i]';";
        $result = $conn->query($remove_sql);
    }

    $book_idx = rand(0, count($possible_books) - 1);

    // add to inventory

    $inventory_query = '("'.$uid.'","'.$possible_books[$book_idx].'","1")';

    $sql = sprintf('
    INSERT INTO user_inventory (user_id,book_id,amount) 
    VALUES %s
    ON DUPLICATE KEY UPDATE amount=amount+1;',
    $inventory_query);

    $result = $conn->query($sql);
    
    return $possible_books[$book_idx];
}

$menu = "default";
$showModal = false;
$modalIdx = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if(isset($_SESSION['postdata'])) {
    $tradeResults = processTradeUp();
    $_SESSION['postdata'] = null;
    $menu = "trade-up";
    if($tradeResults != false) {
        $showModal = true;
        $modalIdx = $tradeResults;
    }
}

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

if($showModal) {
    foreach($inventory_books as $j => $book) {
        if($book["book_id"] == $modalIdx) {
            $modalIdx = $j;
            break;
        }
    }
}

?>

<script>

console.log(`<?php echo ($remove_sql); ?>`);
console.log(`<?php echo ($trade_sql); ?>`);

let menu = `<?php echo $menu; ?>`;
let showModal = `<?php echo $showModal; ?>`;
let modalIdx = `<?php echo $modalIdx; ?>`;

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

    <?php if($showModal) { ?>
    <script>
    $( document ).ready(function() {
        $('#bookModal').modal('show');
    });
    </script>
    <?php } ?>

</head>
<body>

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
                <?php 
                if($showModal) { 
                    $j = $modalIdx;
                    $rarity = $inventory_books[$j]["rarity"];
                    $book = $inventory_books[$j];
                ?>
                <div id="modal-book-box" class="book_box book_box_rarity_<?php echo $rarity; ?> modal-book-box-<?php echo $rarity; ?>">
                    <div id="modal-book-box-<?php echo $j; ?>" class="book_box book_box_rarity_<?php echo $rarity; ?>" style="order: <?php echo $j; ?>;">
                        <div id="modal-book-container3d-<?php echo $j; ?>" class="book-container3d">
                            <div class="book3d">
                                <div class="book3d-cover cover-title" id="modal-cover-title-<?php echo $j; ?>"><?php echo $book["title"]; ?></div>
                                <img class="book3d-cover" id="modal-cover-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
                                <div class="book3d-cover-back cover-title" id="modal-cover-back-title-<?php echo $j; ?>"></div>
                                <img class="book3d-cover-back" id="modal-cover-back-<?php echo $j; ?>" src="assets/no_cover.jpg"/>
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
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="onDismissModal()" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <?php require "header.php"; ?>

    <h4 class="font-size38 text-center mt-3 mb-1">
        My Inventory
    </h4>

	<div class="wrapper container">

        <!-- Page Content Holder -->
        <div id="content">
            <div class="d-flex justify-content-between align-items-center inventory-menu mb-2">
                <div class="inventory-menu-item form-group row">
                    <label for="sort-select" class="col-sm-5 col-form-label">Sort by:</label>
                    <select id="sort-select" class="col-sm-7 custom-select" onchange="onSortChanged()">
                        <option selected value="rarity">Rarity</option>
                        <option value="amount">Amount</option>
                    </select>
                </div>
                <div id="default-menu" class="inventory-menu-item form-group row" <?php if($menu == "trade-up") echo 'style="display: none;"'; ?>>
                    <button id="btn-trade-up" type="button" class="btn btn-success" onclick="tradeUpClicked()">Trade Up</button>
                    <button id="btn-edit-deck" type="button" class="btn btn-primary" onclick="editDeckClicked()">Edit Deck</button>
                </div>
                <div id="trade-up-menu" class="inventory-menu-item form-group row" <?php if($menu == "default") echo 'style="display: none;"'; ?>>
                    <button id="btn-trade-up-fill-3" type="button" class="btn btn-primary" onclick="tradeUpFill3Clicked()">Autofill 3★</button>
                    <button id="btn-trade-up-fill-4" type="button" class="btn btn-primary" onclick="tradeUpFill4Clicked()">Autofill 4★</button>
                    <button id="btn-trade-up-init" type="button" class="btn btn-success" onclick="tradeUpInitClicked()" disabled>Initiate Trade Up</button>
                    <button id="btn-trade-up-cancel" type="button" class="btn btn-danger" onclick="tradeUpCancelClicked()">Cancel Trade Up</button>
                </div>
            </div>

            <div class="collapse <?php if($menu == "trade-up") echo 'show'; ?>" id="trade-up-collapse">
                <div class="card card-body">
                    <div id="trade_up_container" class="inventory_container trade_up_container">
                    </div>
                </div>
            </div>

            <div class="inventory_container">
                <?php
                foreach($inventory_books as $j => $book) {
                    $rarity = $book["rarity"];
                    ?>
                    <div id="book-box-<?php echo $j; ?>" class="book_box book_box_rarity_<?php echo $rarity; ?>" style="order: <?php echo $j; ?>;">
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
                        <div class="amount-container" <?php if($book["amount"] == 1) echo 'style="display:none;"'; ?>>
                            <div class="text-center amount">
                                ×<span id="book-amount-<?php echo $j; ?>"><?php echo $book["amount"]; ?></span>
                            </div>
                        </div>
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