<!DOCTYPE html>
<html lang="en">
    
<?php

require_once "config.php";
require_once "utils.php";

$sql = sprintf('
SELECT *
FROM collection
LIMIT 25;');

$result = $conn->query($sql);

?>

<script>

let sql = `<?php echo ($sql); ?>`;

console.log(sql);

</script>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Collections</title>

    <?php require_once "frameworks.php"; ?>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/collection.css">

</head>
<body>

    <?php require "header.php"; ?>

    <h4 class="font-size38 text-center mt-3 mb-1">
        COLLECTIONS
    </h4>

	<div class="wrapper">

        <!-- Page Content Holder -->
        <div id="content">

            <div class="d-flex justify-content-around flex-wrap">
                <?php
            
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div onclick="collection_clicked('<?php echo $row['collection_id'];?>')" class="text-center square" style="width: 23%; margin-bottom: 2%;">
                            <div class="card-body p-0">
                                <?php

                                echo '<div class="pt-3">';
                                echo '<h1 class="h5">'.$row['name'].'</h1>';
                                echo '</div>';

                                ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "0 results";
                }

                ?>
            </div>

            <script src="js/collection.js"></script>
        
        </div>
    </div>

</body>
</html>