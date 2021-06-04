<?php
// connect to DB 
require_once "config.php";

// initialize variables with empty values
$bookName = $authorName = $pages = $language = $publisher = $publishedYear= "";
$genreHorror = $genreThriller = $genreComedy = $genreRomance = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $bookName = trim($_POST["book-name"]);
    $authorName = trim($_POST["author-name"]);
    $pages = trim($_POST["book-number-of-pages"]);
    $language = trim($_POST["language"]);
    $publisher = trim($_POST["publisher"]);
    $publishedYear = trim($_POST["published-year"]);

    if (!(trim($_POST["genre-comdedy"]))){
        $genreComedy = true;
    }
    if (!(trim($_POST["genre-horror"]))){
        $genreHorror = true;
    }
    if (!(trim($_POST["genre-thriller"]))){
        $genreThriller = true;
    }
    if (!(trim($_POST["genre-romance"]))){
        $genreRomance = true;
    }

    #call ADD BOOK sql procedure
    $sql = "CALL ADD_BOOK(
            '" . addslashes($bookName) . "', 
            '" . addslashes($authorName) . "', 
            '" . addslashes($pages) . "', 
            '" . addslashes($language) . "',
            '" . addslashes($publisher) . "',
            '" . addslashes($publishedYear) . "',
            '" . addslashes($genreComedy) . "',
            '" . addslashes($genreHorror) . "',
            '" . addslashes($genreThriller) . "',
            '" . addslashes($genreRomance) . "');";

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
    <meta charset="UTF-8">
    <title>New Book</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Book Name:</label><br>
        <input type="text" id="book-name" name="book-name" value="<?php echo $bookName; ?>"><br>
        <label>Author Name:</label><br>
        <input type="text" id="author-name" name="author-name"><br>
        <label>Number of pages:</label><br>
        <input type="text" id="book-number-of-pages" name="book-num-of-pages"><br>
        <label>Language: </label><br>
        <input type="text" id="language" name="language"><br>
        <label>Publisher:</label><br>
        <input type="text" id="publisher" name="publisher"><br>
        <label>Published Year:</label><br>
        <input type="text" id="published-year" name="published-year"><br>
        <label> Genre: </label><br>
        <label><input type="checkbox" id="genre-horror"> Horror</label>
        <label><input type="checkbox" id="genre-thriller"> Thriller</label>
        <label><input type="checkbox" id="genre-comedy"> Comedy</label>
        <label><input type="checkbox" id="genre-romance"> Romance</label>
        <div class="form-group">
                <input type="submit" name="submit" class="btn btn-success" value="Add New Book">
        </div>

    </form>
</body>
</html>