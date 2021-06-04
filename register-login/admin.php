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


// ADD BOOK 
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['new-book'])){
    header("location: new-book.php");
}

// DELETE BOOK
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['delete-book'])){
    header("location: delete-book.php");
}



// if Logout button has been clicked
if($_SERVER["REQUEST_METHOD"] == "POST" and !(isset($_POST['new-book'])) and !(isset($_POST['delete-book']))) {
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
    <link rel="stylesheet" href="admin.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>  


<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Logout">
        </div>

        <div class="form-group">
            <input name="new-book" type="submit" class="btn btn-success" value="New Book">
        </div>
  
        <div class="form-group">
            <input name="delete-book" type="submit" class="btn btn-danger" value="Delete Book">
        </div>
    </form>


  <div class="row searchFilter" >
     <div class="col-sm-12" >
      <div class="input-group" >
       <input id="table_filter" type="text" class="form-control" placeholder="Search the Library System ..." >
       <div class="input-group-btn" >
        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ><span class="label-icon" >Category</span> <span class="caret" >&nbsp;</span></button>
        <div class="dropdown-menu dropdown-menu-right" >
           <ul class="category_filters" >
                <li > <input type="checkbox" id="All"><label>All</label> </li>
                <li > <input type="checkbox" id="Book Title"><label>Book Title</label></li>
                <li > <input type="checkbox" id="Author"><label>Author</label> </li>
                <li > <input type="checkbox" id="Genre"><label>Genre</label> </li>
                <li > <input type="checkbox" id="Publisher"><label>Publisher</label></li>
                <li > <input type="checkbox" id="Published Year"><label>Published Year</label></li>
           </ul>
        </div>
        <button id="searchBtn" type="button" class="btn btn-secondary btn-search" ><span class="glyphicon glyphicon-search" >&nbsp;</span> <span class="label-icon" >Search</span></button>
       </div>
      </div>
     </div>
  </div>
    
  </div>

</div>
</body>
</html>