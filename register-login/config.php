<?php
$servername = "localhost:3306";
$dbusername = "bookperson";
$dbpassword = "genericpassword";
$dbname = "bookdb";


// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>