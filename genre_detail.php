<!DOCTYPE html>
<html lang="en">
    
<?php

require_once "config.php";
require_once "utils.php";

$sql = 'SELECT CEIL(RAND() * (SELECT MAX(genre.genre_id) FROM genre)) as id';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$genre_id = isset($_GET["id"]) ? $_GET["id"] : $row["id"];
$genre_id_query = 'WHERE genre.genre_id = "'.$genre_id.'"';

$genre_sql = sprintf('
SELECT * FROM genre
%s
LIMIT 1;',
$genre_id_query);

$genre_result = $conn->query($genre_sql);
$genre_row = $genre_result->fetch_assoc();

$sql = sprintf('
SELECT *
FROM
    (SELECT  c.count, c.original_key, c.isbn, c.number_of_pages, c.language, c.publish_year, c.book_id, c.title, c.author, GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, p.publisher_id, p.name as "publisher_name"
    FROM 
        (SELECT  b.count, b.original_key, b.isbn, b.number_of_pages, b.language, b.publish_year, b.book_id, b.title, b.publisher_id, GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
        FROM 
            (SELECT * FROM book 
            RIGHT JOIN (
            SELECT genre.book_id as filtered_book_id FROM book_genre as genre
            %s ) g ON g.filtered_book_id = book.book_id) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id = a.author_id
        GROUP BY b.book_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id = c.publisher_id 
    GROUP BY c.book_id) as d
LIMIT 25;',
$genre_id_query);

$result = $conn->query($sql);

?>

<script>

let genre_id = `<?php echo ($genre_id); ?>`;

console.log(genre_id);

</script>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Genre Detail</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">

</head>
<body>

    <?php require "header.php"; ?>

    <h4 class="font-size38 sm-font-size32 xs-font-size30 text-center mt-3 mb-1">
        <?php echo strtoupper($genre_row["name"]); ?>
    </h4>

	<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

            <?php require_once "book_table.php"; ?>
        
        </div>
    </div>

</body>
</html>