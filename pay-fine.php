<?php
require_once "config.php";


$username = $payment = "";
$paymentAmount = 0;
$msg = $err = $usernameErr = $paymentErr = "";
$output = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $output = 0;

    if (empty(trim($_POST["username"]))) {
        $usernameErr = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    $paymentAmount = floatval($_POST["payment"]);

    if (empty(trim($_POST["payment"])) || $paymentAmount <= 0) {
        $paymentErr = "Please enter valid payment amount that is greater than 0.";
    } else {
        $payment = trim($_POST["payment"]);
    }

    if (empty($usernameErr) && empty($paymentErr)) {
        $sql = "SELECT user_id FROM user WHERE username = '" . $username . "'";

        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_array();
            $uid = $row[0];

            $sql = "CALL PAY_FINES($uid, $paymentAmount);";
            $result = $conn->query($sql);

            if ($result && $result->num_rows == 1) {
                $row = $result->fetch_array();
                $msg = "Processed payment of $$row[0].";
                $output = 1;
            } 
            else {
                $err = "Could not process payment.";
            }

        }
        else {
            $usernameErr = "Username is not valid.".
            $err = "Could not process payment.";
        }
    }


}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pay Fine</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">
</head>
<body>
    <?php require "header.php"; ?>
    <div id="content"> 
    <?php 
        if(!empty($book_err)){
            echo '<div class="alert alert-danger">' . $book_err . '</div>';
        } elseif(empty($err) && ($output == 1)) {
            echo '<div class="alert alert-success">' . $msg . "</div>";
        }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Username:</label><br>
        <input type="text" id="username" name="username" class="<?php echo (!empty($usernameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"><br>
        <span class="invalid-feedback"><?php echo $usernameErr; ?></span>
        <label>Payment Amount ($):</label><br>
        <input type="text" id="payment" name="payment" class="<?php echo (!empty($paymentErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $payment; ?>"><br>
        <span class="invalid-feedback"><?php echo $paymentErr; ?></span>
        
        <br></br>
        <div class="form-group">
                <input type="submit" name="submit" class="btn btn-success" value="Process Payment">
        </div>
    </form>
    

    </div>
</body>
</html>