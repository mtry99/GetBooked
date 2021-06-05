<?php
// connect to DB 
require_once "config.php";

// initialize variables with empty values
$bookName = $authorName = $pages = $language = $publisher = $publishedYear= $isbn= $genre = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $bookName = trim($_POST["book-name"]);
    $authorName = trim($_POST["author-name"]);
    $pages = trim($_POST["book-number-of-pages"]);
    $language = trim($_POST["language"]);
    $publisher = trim($_POST["publisher"]);
    $publishedYear = trim($_POST["published-year"]);
    $isbn = trim($_POST["isbn"]);
    $genre= trim($_POST["genre"]);

    
    #call ADD BOOK sql procedure
    $sql = "CALL ADD_BOOK(
            '" . addslashes($bookName) . "', 
            '" . addslashes($authorName) . "', 
            '" . addslashes($pages) . "', 
            '" . addslashes($language) . "',
            '" . addslashes($publisher) . "',
            '" . addslashes($publishedYear) . "',
            '" . addslashes($genre) . "',
            '" . addslashes($isbn) . "');";

    echo $sql;

    $result = $conn->query($sql);
    if ($result){
        echo "Successfully added new book";
    }else{
        echo "ERROR ADDING NEW BOOK";
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
    <link rel="stylesheet" href="book_details.css">
</head>
<body>
    <?php require "header.php"; ?>
    <div id="content"> 
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Book Name:</label><br>
        <input type="text" id="book-name" name="book-name" value="<?php echo $bookName; ?>"><br>
        <label>Author Name:</label><br>
        <input type="text" id="author-name" name="author-name"><br>
        <label>ISBN:</label><br>
        <input type="text" id="isbn" name="isbn"><br>
        <label>Number of pages:</label><br>
        <input type="text" id="book-number-of-pages" name="book-number-of-pages"><br>
        <label>Language: </label><br>
        <input type="text" id="language" name="language"><br>
        <label>Publisher:</label><br>
        <input type="text" id="publisher" name="publisher"><br>
        <label>Published Year:</label><br>
        <input type="text" id="published-year" name="published-year"><br>
        <label>Genre: (enter comma seperated values)</label><br>
        <input type="text" id="genre" name="genre"><br>
        <br></br>
        <div class="form-group">
                <input type="submit" name="submit" class="btn btn-success" value="Add New Book">
        </div>

    </form>
    </div>
</body>
</html>