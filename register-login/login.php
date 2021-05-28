<?php include_once("config.php") ?>

<!DOCTYPE html>
<html>
<body>


<?php

$name = $upassword = "";

?>
<form method="post" "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<h1>Login</h1>
Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';?>">
<br><br>
Password: <input type="text" name="password" value="<?php echo $upassword;?>">

<br><br>

<input type="submit" name="submit" value="Login">
</form>
<br><br>
<a href="register.php"> New User? </a>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST["username"];
	$upassword = $_POST["password"];
	
	$name = "'" . $name . "'";
	$upassword = "'" . $upassword . "'";
	$sql = "CALL VALIDATE_LOGIN({$name}, {$upassword});";
	//echo $sql;
	$result = $conn->query($sql);

	$row = $result->fetch_array();
	echo $row[0] . "\t\t" . $row[1];
	echo "<br/>";

	if ($row[0] == 'TRUE') {
		echo "Login successful";
		header("Location:user.php");
		exit();
	}
	else {
		echo "Login failed";
	}
	$upassword = "";
}

?>

</body>
</html>