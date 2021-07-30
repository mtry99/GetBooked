<!DOCTYPE html>
<html lang="en">

    
<?php

require_once "config.php";
require_once "utils.php";

$sql = 'SELECT CEIL(RAND() * (SELECT MAX(author.author_id) FROM author)) as id';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$author_id = isset($_GET["id"]) ? $_GET["id"] : $row["id"];

$author_id_query = 'WHERE author.author_id = "'.$author_id.'"';

$author_sql = sprintf('
SELECT * FROM author
%s
LIMIT 1;',
$author_id_query);

$author_result = $conn->query($author_sql);
$author_row = $author_result->fetch_assoc();

// $json = file_get_contents('https://openlibrary.org'.$author_row['original_key'].'.json');
// $author_obj = json_decode($json, true);

$author_id_query = 'WHERE author.author_id = "'.$author_id.'"';

$sql = sprintf('
SELECT c.*,
GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, 
p.publisher_id, p.name as "publisher_name", bp.publish_year
FROM (SELECT b.count, b.original_key, b.isbn, b.number_of_pages, 
     b.language, b.book_id, b.title, 
     GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
     FROM (SELECT * FROM book 
          RIGHT JOIN (
          SELECT author.book_id as filtered_book_id FROM book_author as author
          %s ) g ON g.filtered_book_id = book.book_id
          LIMIT 25) as b
     LEFT JOIN book_author ba ON b.book_id = ba.book_id
     LEFT JOIN author a ON ba.author_id = a.author_id
     GROUP BY b.book_id) as c
LEFT JOIN book_genre bg ON c.book_id = bg.book_id
LEFT JOIN genre g ON bg.genre_id = g.genre_id 
LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
GROUP BY c.book_id;',
$author_id_query);

$result = $conn->query($sql);

?>

<script>

let author_id = `<?php echo ($author_id); ?>`;
let author_sql = `<?php echo ($author_sql); ?>`;
// let author_obj = `<?php #print_r ($author_obj); ?>`;

console.log(author_id);
console.log(author_sql);
// console.log(author_obj);

</script>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Author Detail</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/book_details.css">

</head>
<body>

    <?php require "header.php"; ?>

    <h4 class="font-size38 sm-font-size32 xs-font-size30 text-center mt-3 mb-1">
        <?php echo strtoupper($author_row["name"]); ?>
    </h4>

	<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

            <?php require_once "book_table.php"; ?>
        
        </div>
    </div>

</body>
<?php require "footer.php"; ?>
</html>