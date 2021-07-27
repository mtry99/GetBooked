<?php
require_once "config.php";

$_SESSION["loggedin"] = false;
$_SESSION["uid"] = null;
$_SESSION["isadmin"] = false;

header('Location: login.php');
