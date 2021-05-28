<?php
$servername = "localhost:3306";
$dbusername = "genericperson";
$dbpassword = "genericpassword";
$dbname = "libraryDBtmp";


// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>