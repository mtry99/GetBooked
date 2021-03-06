

<script>

let sql = `<?php echo ($sql); ?>`;

console.log(sql);

</script>

<table class="table table-striped table-hover book-table">
<thead>
    <tr>
    <th scope="col" style="width: 1.5%">#</th>
    <th scope="col" style="width: 12%">Cover</th>
    <th scope="col" style="width: 51.5%">Book</th>
    <th scope="col" style="width: 35%">Genre(s)</th>
    </tr>
</thead>
<tbody>

    <?php
    
    if (isset($result->num_rows) && $result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {

            echo '<tr><th scope="row">';
            echo $row["book_id"];
            echo '</th><td><img id="cover-';
            echo $row["original_key"];
            echo '" class="cover-image" src="assets/no_cover.jpg">';
            echo '</td><td class="clickable-book-info"><span class="book-table-title"><a href="#" onclick="return title_clicked(';
            echo $row["book_id"];
            echo ')">';
            echo $row["title"];
            echo '</a></span><br><span class="book-table-bold">Author(s): </span>';
            $author_array = explode(',', $row["author"]);
            foreach($author_array as $i => $author) {

                $author_array_array = explode(':', $author);

                if(!isset($author_array_array[1])) {
                    continue;
                }

                if($i !== 0) {
                    echo ', ';
                }

                echo '<a href="#" onclick="return author_clicked(';
                echo $author_array_array[0];
                echo ')">';
                echo $author_array_array[1];
                echo '</a>';
            }
            echo '<br><span class="book-table-bold">Publisher: </span><a href="#" onclick="return publisher_clicked(';
            echo $row["publisher_id"];
            echo ')">';
            echo $row["publisher_name"];
            echo ' (';
            echo $row["publish_year"];
            echo ')</a><br><span class="book-table-bold">ISBN: </span>';
            echo $row["isbn"];
            echo '<span class="book-table-bold"> Language: </span>';
            echo $language_map[$row["language"]];
            echo '<br><span class="book-table-bold badge availability ';
            if($row["count"] !== "0") {
                echo 'available';
                echo '">Available copies: ';
                echo $row["count"];
            } else {
                echo '">OUT OF STOCK';
            }
            echo '</span></td><td>';
            $genre_array = explode(',', $row["genre"]);
            foreach($genre_array as $i => $genre) {

                if($genre == "") continue;

                $genre_array_array = explode(':', $genre);

                if($i !== 0) {
                    echo ' ';
                }

                echo '<a href="#" class="badge" onclick="return genre_clicked(';
                echo $genre_array_array[0];
                echo ')" style="background-color: ';
                echo ColorHSLToRGB(($genre_array_array[0]*1049)%360/360,0.8,0.6);
                echo ';">';
                echo $genre_array_array[1];
                echo '</a>';
            }
            echo '</td></tr>';
        }
    }

    ?>

    <script src="js/book_table.js"></script>
</tbody>
</table>