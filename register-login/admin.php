<?php
session_start();


if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // if not logged in, redirect to login page
    header("location: login.php");
    exit;
} elseif ($_SESSION["isadmin"] === false) {
    // if logged in as non-admin, cannot access this page
    echo "Sorry, you don't have permission to access this page.";
    exit;
}

// if Logout button has been clicked
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // unset session variables and destroy session
    $_SESSION = array();
    session_destroy();
    
    // redirect to login page
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
	<div class="wrapper">
        <h2>Admin Page</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Logout">
            </div>
        </form>
    </div>

</body>
</html>