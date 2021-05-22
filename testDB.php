<!DOCTYPE html>
<html>
<body>


<?php
$servername = "localhost:3306";
$username = "genericperson";
$password = "genericpassword";
$dbname = "testDB";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT uid FROM student";
$result = $conn->query($sql);

echo $result->num_rows." students in the student table";


$conn->close();
?>

</body>
</html>
