
<?php
// connect to DB 
require_once "config.php";
require_once "access.php";
checkAdminAccess();

// initialize variables with empty values
$bookName = $authorName = $pages = $language = $publisher = $publishedYear= $isbn = $genre = "";
$book_err = $bookName_err = $authorName_err = $pages_err = $language_err = $publisher_err = $publishedYear_err = $isbn_err = "";
$output = 0;

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $output = 0;
    // check if name is empty
    if (empty(trim($_POST["book-name"]))){
        $bookName_err = "Please enter a book name.";
    } else{
        $bookName = trim($_POST["book-name"]);
    }

    if (empty(trim($_POST["author-name"]))){
        $authorName_err = "Please enter an author name.";
    } else {
        $authorName = trim($_POST["author-name"]);
    }

    if (empty(trim($_POST["book-number-of-pages"]))){
        $pages_err = "Please enter number of pages";
    } else {
        $pages = trim($_POST["book-number-of-pages"]);
    }

    if (empty(trim($_POST["language"]))){
        $language_err = "Please enter a language";
    } else {
        $language = trim($_POST["language"]);
    }

    if (empty(trim($_POST["publisher"]))){
        $publisher_err = "Please enter a publisher";
    } else {
        $publisher = trim($_POST["publisher"]);
    }

    if (empty(trim($_POST["published-year"]))){
        $publishedYear_err = "Please enter a published year";
    } else {
        $publishedYear = trim($_POST["published-year"]);
    }

    if (empty(trim($_POST["isbn"]))){
        $isbn_err = "Please enter isbn";
    } else {
        $isbn = trim($_POST["isbn"]);
    }

    
    if (empty($bookName_err) && empty($authorName_err) && empty($pages_err) && empty($language_err) && empty($publisher_err) && empty($publishedYear_err) && empty($isbn_err)){

        #call ADD BOOK sql procedure
        $sql = "CALL ADD_BOOK(
            '" . addslashes($bookName) . "', 
            '" . addslashes($authorName) . "', 
            '" . addslashes($pages) . "', 
            '" . addslashes($language) . "',
            '" . addslashes($publisher) . "',
            '" . addslashes($publishedYear) . "',
            '" . addslashes($isbn) . "');";

        $result = $conn->query($sql);

        if (!$result){
            $book_err = "ERROR ADDING NEW BOOK";
        }

        $output = 1;    
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Book</title>

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
        }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Book Name:</label><br>
            <input type="text" id="book-name" name="book-name" class="<?php echo (!empty($bookName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bookName; ?>"><br>
            <span class="invalid-feedback"><?php echo $bookName_err; ?></span>
        </div> 
        <div class="form-group">
            <label>Author Name:</label><br>
            <input type="text" id="author-name" name="author-name" class="<?php echo (!empty($authorName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $authorName; ?>"><br>
            <span class="invalid-feedback"><?php echo $authorName_err; ?></span>
        </div> 
        <div class="form-group">
            <label>ISBN:</label><br>
            <input type="text" id="isbn" name="isbn" class="<?php echo (!empty($isbn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $isbn; ?>"><br>
            <span class="invalid-feedback"><?php echo $isbn_err; ?></span>
        </div> 
        <div class="form-group">
            <label>Number of pages:</label><br>
            <input type="text" id="book-number-of-pages" name="book-number-of-pages" class="<?php echo (!empty($pages_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pages; ?>"><br>
            <span class="invalid-feedback"><?php echo $pages_err; ?></span>
        </div> 
        <div class="form-group">
            <label>Language: </label><br>
            <input type="text" id="language" name="language" class="<?php echo (!empty($language_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $language; ?>"><br>
            <span class="invalid-feedback"><?php echo $language_err; ?></span>
        </div> 
        <div class="form-group">
            <label>Publisher:</label><br>
            <input type="text" id="publisher" name="publisher" class="<?php echo (!empty($publisher_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $publisher; ?>"><br>
            <span class="invalid-feedback"><?php echo $publisher_err; ?></span>
        </div> 
        <div class="form-group">
            <label>Published Year:</label><br>
            <input type="text" id="published-year" name="published-year" class="<?php echo (!empty($publishedYear_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $publishedYear; ?>"><br>
            <span class="invalid-feedback"><?php echo $publishedYear_err; ?></span>
        </div> 
        <br></br>
        <div class="form-group">
                <input type="submit" name="submit" class="btn btn-success" value="Add New Book">
        </div>
    </form>
    
    <?php 
        if(empty($book_err) && ($output == 1)){
            echo '<div class="alert alert-success">' . "Successfully Add a New Book" . '</div>';
        }        
    ?>

    </div>
</body>
</html>