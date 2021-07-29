<!DOCTYPE html>
<html lang="en">
<?php 
require_once "config.php"; 
require_once "access.php";
checkUserAccess();
?>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>Fines</title>

<?php require_once "frameworks.php"; ?>

<!-- Our Custom CSS -->
<link rel="stylesheet" href="css/book_details.css">

</head>
<body>

    <?php require "header.php"; ?>

    <?php
    $uid = $_SESSION["uid"];

    $fine_query = "CALL GET_FINES_AMOUNT($uid);";
    $tot_out_fines = $conn -> query($fine_query);
    $tot = $tot_out_fines->fetch_row()[0];
    $tot_out_fines->free(); $conn->next_result();
    ?>
    <div class="container">
    &nbsp<b> Total Outstanding Fines: $<?php echo $tot; ?></b>
    </div>
    <div class="container">
    <table class="table table-striped table-hover book-table">
    <thead>
        <tr>
        <th scope="col" style="width: 15%">Return By Date</th>
        <th scope="col" style="width: 15%">Date Returned</th>
        <th scope="col" style="width: 25%">Book</th>
        <th scope="col" style="width: 10%">Fine Type</th>
        <!-- 0 is late charge; 1 is replacement fee (not implemented) -->
        <th scope="col" style="width: 15%; text-align:center">Amount</th>
        <th scope="col" style="width: 15%; text-align:center">Amount Outstanding</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $out_fines_query = "CALL GET_OUTSTANDING_FINES($uid);";
    $out_fines_result = $conn->query($out_fines_query);
    echo $conn->error;
    if ($out_fines_result->num_rows > 0) {
        // outputs data of each row
        while ($row = $out_fines_result->fetch_assoc()) {
            echo '<tr>';
            echo '<td style="text-align:center">';
            echo $row["return_by_date"];
            echo '</td>';
            echo '<td style="text-align:center">';
            if ($row["return_date"] == NULL) echo "N/A";
            else echo $row["return_date"];
            echo '</td>';
            echo '<td>';
            echo $row["title"];
            echo '</td>';
            echo '<td>';
            if ($row["fine_type"] == 0) echo "Late Fine";
            elseif ($row["fine_type"] == 1) echo "Replacement Fine";
            else echo $row["fine_type"];
            echo '</td>';
            echo '<td style="text-align:right">';
            echo "$";
            echo $row["total_amount"];
            echo '</td>';
            echo '<td style="text-align:right">';
            echo "$";
            echo $row["outstanding_amount"];
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';

    } else {
        echo '</tbody></table></div>';
        echo '<div class="container">';
        echo "<center><b>Congratulations, You have no outstanding fines!</b></center>";
        echo '<br></br>';
        echo '</div>';
    }
    $out_fines_result->free(); $conn->next_result();
    ?>
</body>
<?php require "footer.php"; ?>
</html>