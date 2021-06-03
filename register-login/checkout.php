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

<h1>Checkout</h1>
<form method="post">
    <input type="submit" name="checkout" value="Checkout">
</form>

<?php
if(isset($_POST["checkout"]) && isset($_GET["bid"])) {
    $id = $_GET["bid"];
    // echo $id;
    $queryTest = "SELECT count FROM book WHERE book_id = $id";
    $results = $conn -> query($queryTest);
    $row = mysqli_fetch_assoc($results);
    $count = $row['count'];

    if($count > 0) {
        $count--;
        $checkout = "UPDATE book SET count = $count WHERE book_id = $id";
        $results = $conn -> query($checkout);
    } else {
        echo "Out of stock!";
    }
}
?>
