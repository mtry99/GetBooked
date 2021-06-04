<?php
$servername = "localhost:3306";
$dbusername = "importperson";
$dbpassword = "genericpassword";
$dbname = "importDB";


// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<!-- Bootstrap CSS CDN -->
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../book.css">
</head>
<body>
    <?php require "../header.php";

    if(isset($_GET["bid"])) {
        $id = $_GET["bid"];
        // echo $id;
        $query = "SELECT * FROM book WHERE book_id = $id";
        $results = $conn -> query($query);
        $book = mysqli_fetch_assoc($results);
        $key = $book["original_key"];
        $count = $book['count'];
        echo '<title>Checkout</title>';
        echo '<div class="d-flex justify-content-center">';
            echo "<img src='http://covers.openlibrary.org/b/olid/$key.jpg' class='p-2'>";
            echo "Copies Left: $count";
        echo '</div>';
        
        echo '<div class="d-flex justify-content-center">';
            echo '<form method="post">';
                echo '<input type="submit" name="checkout" value="Checkout" class="btn btn-secondary p-2 m-4">';
            echo '</form>';
        echo '</div>';
        
        if(isset($_POST["checkout"])) {
            if($count > 0) {
                $count--;
                $checkout = "UPDATE book SET count = $count WHERE book_id = $id";
                $results = $conn -> query($checkout);
            } else {
                echo "Out of stock!";
            }
        }
    } else {
        echo "Missing book ID!";
    }
    ?>
</body>
</html>
