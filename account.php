<!DOCTYPE html>
<html lang="en">
<?php 

require_once "config.php"; 
require_once "access.php";
checkUserAccess();

$uid = $_SESSION["uid"];

$is_dec = False;
if(isset($_GET['sort_by'])) {
    // get temporary value to obtain sort_by if it has been set
    $temp = $_GET['sort_by'];
}

if(isset($_GET['sort_by']) && substr($_GET['sort_by'], -3) == "dec"){
    $is_dec = True;
    $temp = substr($_GET['sort_by'], 0, -3);
}

// default sort by return date
$sort_by = "return_date";
if(isset($_GET["sort_by"])){
    $sort_by = $temp;
}


// $query = "SELECT c.*,
//     GROUP_CONCAT(g.genre_id, ':', g.name ORDER BY g.name separator ',' ) as genre, 
//     p.publisher_id, p.name as 'publisher_name', bp.publish_year
//     FROM (SELECT b.*, 
//         GROUP_CONCAT(a.author_id, ':', a.name ORDER BY a.name separator ',' ) as author
//         FROM (SELECT user_id, borrow_date, return_date, log_id, return_by_date, book.*
//             FROM book 
//             INNER JOIN log 
//             WHERE book.book_id = log.book_id
//             AND user_id = $uid) as b
//         LEFT JOIN book_author ba ON b.book_id = ba.book_id
//         LEFT JOIN author a ON ba.author_id = a.author_id
//         GROUP BY b.log_id) as c
//     LEFT JOIN book_genre bg ON c.book_id = bg.book_id
//     LEFT JOIN genre g ON bg.genre_id = g.genre_id 
//     LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
//     LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
//     GROUP BY c.log_id, p.publisher_id, bp.publish_year
//     ORDER BY c.borrow_date;";


if(!$is_dec) {
    $query = "SELECT c.*,
    GROUP_CONCAT(g.genre_id, ':', g.name ORDER BY g.name separator ',' ) as genre, 
    p.publisher_id, p.name as 'publisher_name', bp.publish_year
    FROM (SELECT b.*, 
        GROUP_CONCAT(a.author_id, ':', a.name ORDER BY a.name separator ',' ) as author
        FROM (SELECT user_id, borrow_date, return_date, log_id, return_by_date, book.*
            FROM book 
            INNER JOIN log 
            WHERE book.book_id = log.book_id
            AND user_id = $uid) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id = a.author_id
        GROUP BY b.log_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id = g.genre_id 
    LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
    LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
    GROUP BY c.log_id, p.publisher_id, bp.publish_year
    ORDER BY c.$sort_by;";
} else {
    $query = "SELECT c.*,
    GROUP_CONCAT(g.genre_id, ':', g.name ORDER BY g.name separator ',' ) as genre, 
    p.publisher_id, p.name as 'publisher_name', bp.publish_year
    FROM (SELECT b.*, 
        GROUP_CONCAT(a.author_id, ':', a.name ORDER BY a.name separator ',' ) as author
        FROM (SELECT user_id, borrow_date, return_date, log_id, return_by_date, book.*
            FROM book 
            INNER JOIN log 
            WHERE book.book_id = log.book_id
            AND user_id = $uid) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id = a.author_id
        GROUP BY b.log_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id = g.genre_id 
    LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
    LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
    GROUP BY c.log_id, p.publisher_id, bp.publish_year
    ORDER BY c.$sort_by DESC;";
}

// $query = "";

$result = $conn -> query($query);

?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Book Detail</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">

    <script>

    let query = `<?php echo $query; ?>`;

    console.log(query);

    </script>

</head>
<body>

    <?php require "header.php"; ?>
    <!-- <div class="d-flex flex-row-reverse">
        <?php //echo "Sort By: "; ?>
        <select class="form-select form-select-sm" aria-label=".form-select-sm example" style="width=400px">
            <option selected>Borrow Date</option>
            <option value="1">Book ID</option>
            <option value="2">Date Returned</option>
            <option value="3">Return By Date</option>
            <option value="4">Book Title</option>
        </select>
    </div> -->

    <!-- <h1>Loan History</h1> -->
    <div class="wrapper container">
        <table class="table table-striped table-hover book-table">
        <thead>
            <tr>
            <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'book_id'){ ?>
                <th scope="col" style="width: 1.5%"><form action="account.php" method="get"><button name="sort_by" value="book_iddec" style="border:none; background-color:Transparent; font-weight:bold">#</button></form></th>
            <?php } else { ?>
                <th scope="col" style="width: 1.5%"><form action="account.php" method="get"><button name="sort_by" value="book_id" style="border:none; background-color:Transparent; font-weight:bold">#</button></form></th>
            <?php } ?>
            <th scope="col" style="width: 12%">Cover</th>
            <th scope="col" style="width: 40%">Book</th>
            <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'borrow_date'){ ?>
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="borrow_datedec" style="border:none; background-color:Transparent; font-weight:bold">Borrow Date</button></form></th>
            <?php } else { ?>
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="borrow_date" style="border:none; background-color:Transparent; font-weight:bold">Borrow Date</button></form></th>
            <?php } ?>
            <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'return_date'){ ?>
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="return_datedec" style="border:none; background-color:Transparent; font-weight:bold">Date Returned</button></form></th>
            <?php } else { ?> 
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="return_date" style="border:none; background-color:Transparent; font-weight:bold">Date Returned</button></form></th>
            <?php } ?>
            <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 'return_by_date'){ ?>
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="return_by_datedec" style="border:none; background-color:Transparent; font-weight:bold">Return By Date</button></form></th>
            <?php } else { ?> 
                <th scope="col" style="width: 15.5%; text-align:center"><form action="account.php" method="get"><button name="sort_by" value="return_by_date" style="border:none; background-color:Transparent; font-weight:bold">Return By Date</button></form></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>

    <?php
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<tr><th scope="row">';
            echo $row["book_id"];
            echo '</th><td><img id="cover-';
            echo $row["original_key"];
            echo '" class="cover-image" src="assets/no_cover.jpg">';
            echo '</td><td class="clickable-book-info"><span class="book-table-title"><a href="#" onclick="return title_clicked(';
            echo $row["book_id"];
            echo ')">';
            echo $row["title"];
            echo '</a></span><br><span class="book-table-bold">Author(s): </span>';
            $author_array = explode(',', $row["author"]);
            foreach($author_array as $i => $author) {

                    $author_array_array = explode(':', $author);

                    if(!isset($author_array_array[1])) {
                        continue;
                    }

                    if($i !== 0) {
                        echo ', ';
                    }

                    echo '<a href="#" onclick="return author_clicked(';
                    echo $author_array_array[0];
                    echo ')">';
                    echo $author_array_array[1];
                    echo '</a>';
                }
                echo '</td>';
                echo '<td style="text-align:center">';
                echo $row["borrow_date"];
                echo '</td>';
                echo '<td style="text-align:center">';
                if($row["return_date"] == NULL) {
                    echo "N/A";
                    echo '<form method="post">';
                    echo '<input name="book_id" value="';
                    echo $row["book_id"];
                    echo '" type="hidden"></input>';
                    echo '<input name="log_id" value="';
                    echo $row["log_id"];
                    echo '" type="hidden"></input>';
                    echo '<input name="count" value="';
                    echo $row["count"];
                    echo '" type="hidden"></input>';
                    echo '<input type="submit" name="return" value="Return" class="btn btn-primary p-2 m-4">';
                    echo '</form>';
                } else echo $row["return_date"];
                echo '</td>';
                echo '<td style="text-align:center">';
                echo $row["return_by_date"];
                if($row["return_date"] == NULL) {
                    echo '<form method="post">';
                    echo '<input name="book_id" value="';
                    echo $row["book_id"];
                    echo '" type="hidden"></input>';
                    echo '<input name="log_id" value="';
                    echo $row["log_id"];
                    echo '" type="hidden"></input>';
                    echo '<input name="old_date" value="';
                    echo $row["return_by_date"];
                    echo '" type="hidden"></input>';
                    echo '<input type="submit" name="renew" value="Renew" class="btn btn-success p-2 m-4">';
                    echo '</form>';
                }
                // echo '</td>';
            }
            echo '</td></tr>';
        }
        ?>
        <script src="js/book_table.js"></script>

        <?php
        if(isset($_POST["renew"])) {
            $book_id = $_POST['book_id'];
            $log_id = $_POST['log_id'];
            $date = $_POST["old_date"];
            $week = date("Y-m-d", strtotime($date. ' + 7 days'));
            $renew = "UPDATE log SET return_by_date = '$week' WHERE log_id = $log_id";
            $results = $conn -> query($renew);
            echo "<meta http-equiv='refresh' content='0'>";
        }

        if(isset($_POST["return"])) {
            $book_id = $_POST['book_id'];
            $log_id = $_POST['log_id'];
            $cur_date = date("Y-m-d");
            $renew = "UPDATE log SET return_date = '$cur_date' WHERE log_id = $log_id";
            $results = $conn -> query($renew);
            $checkout = "UPDATE book SET count = count + 1 WHERE book_id = $book_id";
            $results = $conn -> query($checkout);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>
        </tbody>
        </table>
    </div>
</body>
<?php require "footer.php"; ?>
</html>