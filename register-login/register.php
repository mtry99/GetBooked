<?php include_once("config.php") ?>

<!DOCTYPE html>
<html>
<body>


<?php

$name = $uname = $upassword = $cpassword = "";

?>
<form method="post" "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<h1>Register</h1>
Name: <input type="text" name="name" value="<?php echo $name;?>">
<br><br>
Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';?>">
<br><br>
Password: <input type="text" name="password" value="<?php echo $upassword;?>">
<br><br>
Confirm Password: <input type="text" name="confirm-password" value="<?php echo $cpassword;?>">
<br><br>

<input type="submit" name="submit" value="Register">
</form>
<br><br>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST["name"];
    $uname = $_POST["username"];
	$upassword = $_POST["password"];
    $cpassword = $_POST["confirm-password"];

    if ($name == NULL || $uname == NULL || $upassword == NULL || $cpassword == NULL){
        echo "Invalid name or username or password";
        exit();
    }

    if ($upassword != $cpassword){
        echo "Passwords don't match";
        exit();
    }
	
	$name = "'" . $name . "'";
    $uname = "'" . $uname . "'";
	$upassword = "'" . $upassword . "'";
    $cpassword = "'" . $cpassword . "'";
	$sql = "CALL REGISTER({$uname},{$upassword},{$name});";
	echo $sql;
	$result = $conn->query($sql);

	echo "Registration successful";
	header("Location:login.php");
	exit();
}

?>

</body>
</html>