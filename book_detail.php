<!DOCTYPE html>
<html lang="en">



<?php

require_once "config.php";
require_once "utils.php";

$book_id_query = isset($_GET["id"]) ? 'WHERE book.book_id = "'.$_GET["id"].'"' : 
"JOIN (SELECT CEIL(RAND() * (SELECT MAX(book.book_id) FROM book)) AS id) AS r WHERE book.book_id >= r.id";

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
GROUP BY c.book_id, p.publisher_id, bp.publish_year;',
$book_id_query);

$result = $conn->query($sql);
echo $conn->error;

$row = $result->fetch_assoc();

$json = file_get_contents('https://openlibrary.org/books/'.$row['original_key'].'.json');
$obj = json_decode($json, true);

$copiesError = false;
$fineError = false;

// $params = array_merge( $_GET, array( 'test' => 'testvalue' ) );
// $new_query_string = http_build_query( $params );

// ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' ) .
// ( empty( $_SERVER['HTTP_HOST'] ) ? $defaultHost : $_SERVER['HTTP_HOST'] ) .
// $_SERVER['REQUEST_URI'] = $new_query_string;

// $get_query = $_GET;
// $get_query['page'] = '1';
// $params = array_merge( $get_query, array( 'test' => 'testvalue' ) );
// $get_query_url = http_build_query($params);

// var_dump($_GET);

// var_dump($row);
if(isset($_POST["checkout"])) {
    $count = $row["count"];
    $book_id = $row["book_id"];
    $uid = $_SESSION['uid'];

    // update fine amounts
    $query = "CALL CALCULATE_FINES($uid);";
    $result = $conn->query($query);

    // get total fines of user
    $fine_query = "CALL GET_FINES_AMOUNT($uid);";
    $tot_out_fines = $conn -> query($fine_query);
    echo $conn->error; 

    $tot = $tot_out_fines->fetch_row()[0];
    $tot_out_fines->free(); $conn->next_result();

    if($tot <= 20) {
        // if fines are less than $20, can borrow books if they are available
        if($count > 0) {
            //update book table
            $count--;
            $checkout = "UPDATE book SET count = $count WHERE book_id = $book_id";
            $results = $conn -> query($checkout);
            //add into log table
            $cur_date = date("Y-m-d");
            $week = date("Y-m-d", strtotime($cur_date. ' + 7 days'));
            $log = "INSERT INTO log VALUES (NULL, '$uid', '$book_id', '$cur_date', NULL, '$week')";
            $results = $conn -> query($log);
            echo $conn->error;
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            $copiesError = true;
        }
    } else {
        // fines are greater than $20, cannot borrow books
        $fineError = true;
    }

        
}

?>

<script>

let sql = `<?php echo ($sql); ?>`;
let obj = `<?php print_r ($obj); ?>`;

console.log(sql);
console.log(obj);

</script>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Book Detail</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">

</head>
<body>

    <?php require "header.php"; ?>

	<div class="wrapper">
        
        <!-- Page Content Holder -->
        <div class="container pt-5">
            <div class="team-single">
                <div class="row">
                    <div class="col-lg-4 col-md-5 xs-margin-30px-bottom">
                        <div class="team-single-img">
                            <?php
                            echo '<img id="cover-';
                            echo $row["original_key"];
                            echo '" class="cover-image-big" src="assets/no_cover.jpg">';
                            ?>
                        </div>
                        <div class="bg-light-gray padding-30px-all md-padding-25px-all sm-padding-20px-all text-center">
                            <h4 class="margin-10px-bottom font-size24 md-font-size22 sm-font-size20 font-weight-600">Genre(s)</h4>
                            <p class="sm-width-95 sm-margin-auto">
                            <?php
                            $genre_array = explode(',', $row["genre"]);
                            foreach($genre_array as $i => $genre) {

                                $genre_array_array = explode(':', $genre);

                                if($i !== 0) {
                                    echo ' ';
                                }

                                echo '<a href="#" class="badge" onclick="return genre_clicked(';
                                echo $genre_array_array[0];
                                echo ')" style="background-color: ';
                                echo ColorHSLToRGB(($genre_array_array[0]*1049)%360/360,0.8,0.6);
                                echo ';">';
                                echo $genre_array_array[1];
                                echo '</a>';
                            }
                            ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="team-single-text padding-50px-left sm-no-padding-left">
                            <h4 class="font-size38 sm-font-size32 xs-font-size30">
                                <?php echo $row["title"]; ?>
                            </h4>
                            <?php 
                            echo '<span class="book-table-bold badge availability ';
                            if($row["count"] !== "0") {
                                echo 'available';
                                echo '">Available copies: ';
                                echo $row["count"];
                            } else {
                                echo '">OUT OF STOCK';
                            }
                            echo '</span>';
                            ?>
                            <p class="no-margin-bottom mt-2">
                                <?php 

                                $first = true;
                                if(array_key_exists("subtitle", $obj)) {
                                    if($first) {
                                        $first = false;
                                    } else {
                                        echo '<br><br>';
                                    }
                                    echo 'Subtitle: '.$obj["subtitle"]; 
                                } 
                                if(array_key_exists("description", $obj)) {
                                    if($first) {
                                        $first = false;
                                    } else {
                                        echo '<br><br>';
                                    }
                                    echo $obj["description"]["value"]; 
                                } 
                                if(array_key_exists("first_sentence", $obj)) { 
                                    if($first) {
                                        $first = false;
                                    } else {
                                        echo '<br><br>';
                                    }
                                    echo 'First sentence: '.$obj["first_sentence"]["value"];
                                }  
                                if(array_key_exists("other_titles", $obj)) {
                                    if($first) {
                                        $first = false;
                                    } else {
                                        echo '<br><br>';
                                    }
                                    echo 'Alternate title: '.$obj["other_titles"][0]; 
                                }  
                                if(array_key_exists("series", $obj)) {
                                    if($first) {
                                        $first = false;
                                    } else {
                                        echo '<br><br>';
                                    }
                                    echo 'Series: '.$obj["series"][0]; 
                                } 
                                if($first) {
                                    echo 'No description available.'; 
                                }
                                
                                ?>
                            </p>
                            <div class="contact-info-section margin-40px-tb">
                                <ul id="detail-values" class="list-style9 no-margin">
                                    <li>

                                        <div class="row">
                                            <div class="col-md-3 col-3">
                                                <i class="fas fa-book text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Id:</strong>
                                            </div>
                                            <div class="col-md-9 col-9">
                                                <p><?php 
                                                echo '<a href="#" onclick="return title_clicked(';
                                                echo $row["book_id"];
                                                echo ')">';
                                                echo $row["book_id"];
                                                echo '</a>';
                                                ?></p>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-3 col-3">
                                                <i class="fas fa-user text-pink"></i>
                                                <strong class="margin-10px-left xs-margin-four-left text-pink">Author(s):</strong>
                                            </div>
                                            <div class="col-md-9 col-9">
                                                <p>
                                                    <?php
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
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>

                                        <div class="row">
                                            <div class="col-md-3 col-3">
                                                <i class="fas fa-building text-yellow"></i>
                                                <strong class="margin-10px-left text-yellow">Publisher:</strong>
                                            </div>
                                            <div class="col-md-9 col-9">
                                                <p><?php 
                                                echo '<a href="#" onclick="return publisher_clicked(';
                                                echo $row["publisher_id"];
                                                echo ')">';
                                                echo $row["publisher_name"];
                                                echo '</a>';
                                                ?></p>
                                            </div>
                                        </div>

                                    </li>
                                    <li>

                                        <div class="row">
                                            <div class="col-md-3 col-3">
                                                <i class="fas fa-barcode text-green"></i>
                                                <strong class="margin-10px-left text-green">ISBN:</strong>
                                            </div>
                                            <div class="col-md-9 col-9">
                                                <p><?php echo $row["isbn"]; ?></p>
                                            </div>
                                        </div>

                                    </li>
                                    <li>

                                        <div class="row">
                                            <div class="col-md-3 col-3">
                                                <i class="fas fa-language text-purple"></i>
                                                <strong class="margin-10px-left xs-margin-four-left text-purple">Language:</strong>
                                            </div>
                                            <div class="col-md-9 col-9">
                                                <p><?php echo $language_map[$row["language"]]; ?></p>
                                            </div>
                                        </div>

                                    </li>
                                    
                                </ul>
                            </div>

                            <h5 class="font-size24 sm-font-size22 xs-font-size20">Values</h5>

                            <div class="sm-no-margin">
                                <div class="progress-text">
                                    <div class="row">
                                        <div class="col-7">Number of Pages</div>
                                        <div class="col-5 text-right"><?php echo $row["number_of_pages"]; ?></div>
                                    </div>
                                </div>
                                <div class="custom-progress progress">
                                    <div role="progressbar" style="width:<?php echo intval($row["number_of_pages"])*100/1500; ?>%" class="animated custom-bar progress-bar slideInLeft bg-sky"></div>
                                </div>
                                <div class="progress-text">
                                    <div class="row">
                                        <div class="col-7">Publishing Year</div>
                                        <div class="col-5 text-right"><?php echo $row["publish_year"]; ?></div>
                                    </div>
                                </div>
                                <div class="custom-progress progress">
                                    <div role="progressbar" style="width:<?php echo (intval($row["publish_year"])-1700)*100/(2021-1700); ?>%" class="animated custom-bar progress-bar slideInLeft bg-orange"></div>
                                </div>
                                <div class="progress-text">
                                    <div class="row">
                                        <div class="col-7">Number of Copies Available</div>
                                        <div class="col-5 text-right"><?php echo $row["count"]; ?></div>
                                    </div>
                                </div>
                                <div class="custom-progress progress">
                                    <div role="progressbar" style="width:<?php echo intval($row["count"])*100/10; ?>%" class="animated custom-bar progress-bar slideInLeft bg-green"></div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <form method="post">
                                    <!-- <label for="quantity">Quantity:</label> -->
                                    <!-- <input type="number" name="quantity" style="width: 100px;" min="1"> -->
                                    <!-- <button name="checkout" type="button" class="btn btn-secondary p-2 m-4">Checkout</button> -->
                                        <?php
                                        if(empty($_GET)) {
                                            ?>
                                            <script>
                                            var href = window.location.href;
                                            var regex = new RegExp("[&\\?]" + 'id' + "=");
                                            if(regex.test(href))
                                            {
                                                regex = new RegExp("([&\\?])" + 'id' + "=\\d+");
                                                window.location.href = href.replace(regex, "$1" + 'id' + "=" + <?php echo $row["book_id"] ?>);
                                            }
                                            else
                                            {
                                                if(href.indexOf("?") > -1)
                                                window.location.href = href + "&" + 'id' + "=" + <?php echo $row["book_id"] ?>;
                                                else
                                                window.location.href = href + "?" + 'id' + "=" + <?php echo $row["book_id"] ?>;
                                            }
                                            </script>
                                        <?php }
                                        // var_dump($_GET)
                                        ?>
                                        <input type="submit" name="checkout" value="Checkout" class="btn btn-secondary p-2 m-4">
                                    </form>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <?php
                                    if($copiesError) echo '<h6 style="color:red">Not Enough Copies Available!</p>';
                                    if($fineError) echo '<h6 style="color:red">Your fines exceed $20. Please pay your fines before borrowing more books.</p>';
                                    ?>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/book_detail.js"></script>
</body>
<?php require "footer.php"; ?>
</html>