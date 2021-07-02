<?php
require_once "config.php";

$_SESSION["loggedin"] = false;
$_SESSION["uid"] = null;

header('Location: login.php');
