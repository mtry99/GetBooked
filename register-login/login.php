<?php 
// connect to DB
require_once "config.php";

// initialize session
session_start();

// if already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	// redirect to admin or user page
	if($_SESSION["isadmin"] == true) {
		header("location: admin.php");
		exit;
	} else {
		header("location: user.php");
		exit;
	}
}

// initialize variables with empty values
$uname = $upassword = "";
$uname_err = $upassword_err = $login_err = "";

// handle data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// check if username is empty
	if(empty(trim($_POST["username"]))) {
		$uname_err = "Please enter username.";
	} else {
		$uname = trim($_POST["username"]);
	}
	
	// check if password is empty
    if(empty($_POST["password"])){
        $upassword_err = "Please enter your password.";
    } else {
        $upassword = $_POST["password"];
    }
	
	// if no error with username and password
	if(empty($uname_err) && empty($upassword_err)) {

		// call stored procedure
		// addslashes() used in case password or username uses escape characters
		$sql = "CALL VALIDATE_LOGIN('" . addslashes($uname) . "', '" . addslashes($upassword) . "');";

		$result = $conn->query($sql);

		// if user exists
		if($result && $result->num_rows == 1) {

			$row = $result->fetch_array();

			// start new session
			session_start();
			
			$_SESSION["loggedin"] = true;
			$_SESSION["uid"] = $row[1];

			// redirect depending on if user is admin
			if($row[2]) {
				$_SESSION["isadmin"] = true;
				header("location: admin.php");
				exit;
			} else {
				$_SESSION["isadmin"] = false;
				// redirect to user page
				header("location: user.php");
				exit;
			}
			
		} else {
			// user does not exist
			$login_err = "Invalid username or password.";
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
	<div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($uname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $uname; ?>">
                <span class="invalid-feedback"><?php echo $uname_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($upassword_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $upassword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>

</body>
</html>