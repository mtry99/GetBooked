<?php
// connect to DB 
require_once "config.php";

// initialize variables with empty values
$name = $uname = $upassword = $cpassword = "";
$name_err = $uname_err = $upassword_match = $upassword_err = $register_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // check if name is empty
	if(empty(trim($_POST["name"]))) {
		$name_err = "Please enter name.";
	} else {
		$name = trim($_POST["name"]);
	}

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

    // check if password is empty
    if(empty($_POST["password"])){
        $upassword_err = "Please enter your password.";
    } else {
        $cpassword = $_POST["confirm-password"];
    }

    if ($upassword != $cpassword){
        $upassword_err = "Passwords don't match.";
    }
        
    if (empty($name_err) && empty($uname_err) && empty($upassword_err) && empty($upassword_match)){
    
        $sql = "CALL REGISTER('" . addslashes($uname) . "', '" . addslashes($upassword) . "', '" . addslashes($name) . "');";
        $result = $conn->query($sql);

		if($result) {
            header("Location:login.php");
            exit();
        } else{
            $register_err = "Invalid username or password.";
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
    <title>Register</title>

    <?php require_once "frameworks.php"; ?>
    <link rel="stylesheet" href="css/book_details.css">
</head>
<body>
    <?php require "header.php"; ?>
    <div>
        <p>&nbsp Please fill in your information to sign up.</p>
    </div>
	<div id="content">
    <?php 
    if(!empty($register_err)){
        echo '<div class="alert alert-danger">' . $register_err . '</div>';
    }        
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-group">
            <label>Name: </label><br/>
            <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div> 
            
        <div class="form-group">
            <label>Username: </label><br/>
            <input type="text" name="username" class="<?php echo (!empty($uname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $uname; ?>">
            <span class="invalid-feedback"><?php echo $uname_err; ?></span>
        </div>    

        <div class="form-group">
            <label>Password: </label><br/>
            <input type="password" name="password" class="<?php echo (!empty($upassword_err)) ? 'is-invalid' : ''; echo (!empty($upassword_match)) ? 'is-invalid' : '';?>">
            <span class="invalid-feedback"><?php echo $upassword_err; echo $upassword_match; ?></span>
        </div>

        <div class="form-group">
            <label>Confirm Password: </label><br/>
            <input type="password" name="confirm-password" class="<?php echo (!empty($upassword_err)) ? 'is-invalid' : ''; echo (!empty($upassword_match)) ? 'is-invalid' : '';?>">
            <span class="invalid-feedback"><?php echo $upassword_err; echo $upassword_match; ?></span>
        </div>

        <br></br>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-success" value="Register">
        </div>
        <p>Already have an account? <a style="color:green" href="login.php">Login here</a>.</p>
    </form>
    </div>

</body>
</html>
