<?php

function checkAccess() {
    # if user is not logged in, redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
        header("location: login.php");
        exit();
    }
}

function checkNoAccess() {
    # if user is logged in, redirect to home (book) page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
        header("location: book.php");
    }
}

function checkAdminAccess() {
    # check if user is logged in and is admin, otherwise redirect
    checkAccess();
    if(!isset($_SESSION["isadmin"]) || $_SESSION["isadmin"] != true) {
        header("location: book.php");
    } 
}

function checkUserAccess() {
    # check if user is logged in and is not admin, otherwise redirect
    checkAccess();
    if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"] == true) {
        header("location: book.php");
    }
}


?>