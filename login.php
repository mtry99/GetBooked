<?php 
// connect to DB
require_once "config.php";
require_once "access.php";
checkNoAccess();


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
			$_SESSION["uid"] = $row[0];

			// redirect depending on if user is admin
			if($row[2]) {
				$_SESSION["isadmin"] = true;
				header("location: book.php");
				exit;
			} else {
				$_SESSION["isadmin"] = false;
				// redirect to user page

				header("location: book.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    
    <?php require_once "frameworks.php"; ?>
    <link rel="stylesheet" href="css/book_details.css">
</head>
<body>
    <?php require "header.php"; ?>

    <div id="content">

    <?php 
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }        
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <div class="form-group">
            <label>Username:</label><br>
            <input type="text" id="username" name="username" class="<?php echo (!empty($uname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $uname; ?>"><br>
            <span class="invalid-feedback"><?php echo $uname_err; ?></span>
        </div> 
        
        <div class="form-group">
            <label>Password:</label><br>
            <input type="password" id="password" name="password" class="<?php echo (!empty($upassword_err)) ? 'is-invalid' : ''; ?>"><br>
            <span class="invalid-feedback"><?php echo $upassword_err; ?></span>
        </div> 

        <br></br>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Login">
        </div>
        <p>Don't have an account? <a style="color:green" href="register.php">Sign up now</a>.</p>
    </form>

    </div>

</body>
<?php require "footer.php"; ?>
</html>