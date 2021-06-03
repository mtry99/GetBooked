<?php
$servername = "localhost:3306";
$dbusername = "importperson";
$dbpassword = "genericpassword";
$dbname = "importdb";


// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>