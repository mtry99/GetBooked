<?php

require_once "config.php";

// year_on=true&year_min=1753&year_max=1925

$book_filter = array();

$book_filter["title"] = isset($_GET["title"]) ? $_GET["title"] : "";
$book_filter["author"] = isset($_GET["author"]) ? $_GET["author"] : "";
$book_filter["publisher"] = isset($_GET["publisher"]) ? $_GET["publisher"] : "";
$book_filter["genre"] = isset($_GET["genre"]) ? $_GET["genre"] : "";

$book_filter["in_stock"] = isset($_GET["in_stock"]) ? $_GET["in_stock"] : "false";

$book_filter["page_on"] = isset($_GET["page_on"]) ? $_GET["page_on"] : "false";
$book_filter["page_min"] = isset($_GET["page_min"]) ? $_GET["page_min"] : "500";
$book_filter["page_max"] = isset($_GET["page_max"]) ? $_GET["page_max"] : "1200";

$book_filter["year_on"] = isset($_GET["year_on"]) ? $_GET["year_on"] : "false";
$book_filter["year_min"] = isset($_GET["year_min"]) ? $_GET["year_min"] : "1900";
$book_filter["year_max"] = isset($_GET["year_max"]) ? $_GET["year_max"] : "2021";

$book_filter["language"] = isset($_GET["language"]) ? $_GET["language"] : "any";

function ColorHSLToRGB($h, $s, $l){

    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
          $m;
          $sv;
          $sextant;
          $fract;
          $vsf;
          $mid1;
          $mid2;

          $m = $l + $l - $v;
          $sv = ($v - $m ) / $v;
          $h *= 6.0;
          $sextant = floor($h);
          $fract = $h - $sextant;
          $vsf = $v * $sv * $fract;
          $mid1 = $m + $vsf;
          $mid2 = $v - $vsf;

          switch ($sextant)
          {
                case 0:
                      $r = $v;
                      $g = $mid1;
                      $b = $m;
                      break;
                case 1:
                      $r = $mid2;
                      $g = $v;
                      $b = $m;
                      break;
                case 2:
                      $r = $m;
                      $g = $v;
                      $b = $mid1;
                      break;
                case 3:
                      $r = $m;
                      $g = $mid2;
                      $b = $v;
                      break;
                case 4:
                      $r = $mid1;
                      $g = $m;
                      $b = $v;
                      break;
                case 5:
                      $r = $v;
                      $g = $m;
                      $b = $mid2;
                      break;
          }
    }
    return "rgb(".($r * 255.0).",".($g * 255.0).",".($b * 255.0).")";
}

$language_map = array("afr"=>"Afrikaans","alb"=>"Albanian","amh"=>"Amharic","ang"=>"English, Old","ara"=>"Arabic","arm"=>"Armenian","asm"=>"Assamese","ava"=>"Avaric","aze"=>"Azerbaijani","baq"=>"Basque","bel"=>"Belarusian","ben"=>"Bengali","bnt"=>"Bantu","bos"=>"Bosnian","bre"=>"Breton","bul"=>"Bulgarian","cat"=>"Catalan","cau"=>"Caucasian","chi"=>"Chinese","chv"=>"Chuvash","cmn"=>"Mandarin","cze"=>"Czech","dan"=>"Danish","dut"=>"Dutch","dzo"=>"Dzongkha","egy"=>"Egyptian","eng"=>"English","enm"=>"English, Middle","esk"=>"Eskimo languages","esp"=>"Esperanto","est"=>"Estonian","fao"=>"Faroese","fin"=>"Finnish","fiu"=>"Finno-Ugrian","fre"=>"French","fri"=>"Frisian","frm"=>"French, Middle","fro"=>"French, Old","gae"=>"Scottish Gaelix","gag"=>"Galician","geo"=>"Georgian","ger"=>"German","gle"=>"Irish","glg"=>"Galician","gmh"=>"German, Middle High","grc"=>"Ancient Greek","gre"=>"Greek","gsw"=>"gsw","guj"=>"Gujarati","hat"=>"Haitian French Creole","hau"=>"Hausa","heb"=>"Hebrew","hin"=>"Hindi","hrv"=>"Croatian","hun"=>"Hungarian","ibo"=>"Igbo","ice"=>"Icelandic","ind"=>"Indonesian","iri"=>"Irish","ita"=>"Italian","jpn"=>"Japanese","kal"=>"Kalâtdlisut","kan"=>"Kannada","kaz"=>"Kazakh","khi"=>"Khoisan","kir"=>"Kyrgyz","kok"=>"Konkani","kor"=>"Korean","kur"=>"Kurdish","lad"=>"Ladino","lao"=>"Lao","lat"=>"Latin","lav"=>"Latvian","lit"=>"Lithuanian","mac"=>"Macedonian","mai"=>"Maithili","mal"=>"Malayalam","mao"=>"Maori","mar"=>"Marathi","may"=>"Malay","mni"=>"Manipuri","mol"=>"Moldavian","mon"=>"Mongolian","mul"=>"Multiple languages","nai"=>"North American Indian","nep"=>"Nepali","new"=>"Newari","nor"=>"Norwegian","oci"=>"Occitan","oji"=>"Ojibwa","ori"=>"Oriya","oss"=>"Ossetic","ota"=>"Turkish, Ottoman","paa"=>"Papuan","pan"=>"Panjabi","pap"=>"Papiamento","per"=>"Persian","pol"=>"Polish","por"=>"Portuguese","roa"=>"Romance","rom"=>"Romani","rum"=>"Romanian","run"=>"Rundi","rus"=>"Russian","sah"=>"Yakut","san"=>"Sanskrit","scc"=>"Serbian","scr"=>"Croatian","sin"=>"Sinhalese","slo"=>"Slovak","slv"=>"Slovenian","smo"=>"Samoan","snh"=>"Sinhalese","som"=>"Somali","spa"=>"Spanish","srp"=>"Serbian","swa"=>"Swahili","swe"=>"Swedish","tag"=>"Tagalog","tam"=>"Tamil","tat"=>"Tatar","tel"=>"Telugu","tgk"=>"Tajik","tgl"=>"Tagalog","tha"=>"Thai","tib"=>"Tibetan","tuk"=>"Turkmen","tur"=>"Turkish","tut"=>"Altaic","twi"=>"Twi","ukr"=>"Ukrainian","und"=>"Undetermined","urd"=>"Urdu","uzb"=>"Uzbek","vie"=>"Vietnamese","wel"=>"Welsh","wen"=>"Sorbian","xho"=>"Xhosa","yid"=>"Yiddish","yor"=>"Yoruba","zap"=>"Zapotec");

$query_title = '';
$query_page = '';
$query_year = '';
$query_publisher = '';
$query_author = '';
$query_genre = '';

$query_language = '';
$query_count = '';

$search_str = '';
$search_str_1 = '';
$search_str_2 = '';
$search_str_order = '';

$first = true;
if($book_filter["title"] !== "") {
    $pieces = explode(" ", $book_filter["title"]);
    $search_str = implode(" +", $pieces); // "( *".implode("* *", $pieces)."* ) (\"".implode(" ", $pieces)."\")"
    $search_str = 'MATCH(book.title) AGAINST(\'+'.$search_str.'\' IN BOOLEAN MODE) ';
    $query_title = ($first?' WHERE ':' AND ').$search_str;
    //var_dump($query_title);
    $first = false;

    $search_str = ', '.$search_str.'score ';
    $search_str_1 = 'c.score, ';
    $search_str_2 = 'b.score, ';
    $search_str_order = 'ORDER BY score DESC';
}
if($book_filter["page_on"] !== "false") {
    $query_page = ($first?' WHERE ':' AND ').'book.number_of_pages BETWEEN '.$book_filter["page_min"].' AND '.$book_filter["page_max"].' ';
    $first = false;
}
if($book_filter["year_on"] !== "false") {
    $query_year = ($first?' WHERE ':' AND ').'book.publish_year BETWEEN '.$book_filter["year_min"].' AND '.$book_filter["year_max"].' ';
    $first = false;
}
if($book_filter["language"] !== "any") {
    $query_language = ($first?' WHERE ':' AND ').'book.language = "'.$book_filter["language"].'" ';
    $first = false;
}
if($book_filter["in_stock"] !== "false") {
    $query_count = ($first?' WHERE ':' AND ').'book.count > "0" ';
    $first = false;
}

$first = true;
if($book_filter["publisher"] !== "") {
    $query_publisher = ($first?' WHERE ':' AND ').'UPPER(d.publisher_name) LIKE UPPER("%'.$book_filter["publisher"].'%") ';
    $first = false;
}
if($book_filter["author"] !== "") {
    $query_author = ($first?' WHERE ':' AND ').'UPPER(d.author) LIKE UPPER("%'.$book_filter["author"].'%") ';
    $first = false;
}
if($book_filter["genre"] !== "") {

    $pieces = explode(",", $book_filter["genre"]);
    $pieces = array_map("trim", $pieces);
    
    foreach($pieces as $i => $piece) {

        $query_genre = $query_genre.($first?' WHERE ':' AND ').'UPPER(d.genre) LIKE UPPER("%'.$piece.'%") ';
        $first = false;
    }
}

$sql = sprintf('
SELECT *
FROM
    (SELECT %s c.count, c.original_key, c.isbn, c.number_of_pages, c.language, c.publish_year, c.book_id, c.title, c.author, GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, p.publisher_id, p.name as "publisher_name"
    FROM 
        (SELECT %s b.count, b.original_key, b.isbn, b.number_of_pages, b.language, b.publish_year, b.book_id, b.title, b.publisher_id, GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
        FROM 
            (SELECT * %s FROM book 
            %s
            %s
            %s
            %s
            %s) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id  = a.author_id
        GROUP BY b.book_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = c.publisher_id 
    GROUP BY c.book_id) as d
%s
%s
%s
%s
LIMIT 25;',
$search_str_1, $search_str_2, $search_str, $query_title, $query_page, $query_year, $query_language, 
$query_count, $query_publisher, $query_author, $query_genre, $search_str_order);

//var_dump($sql);

$result = $conn->query($sql);

//var_dump($book_filter);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Book Search</title>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>     
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="book.css">

    <script>
    
    let book_filter = {};

    book_filter["title"] = "<?php echo $book_filter["title"]; ?>";
    book_filter["author"] = "<?php echo $book_filter["author"]; ?>";
    book_filter["publisher"] = "<?php echo $book_filter["publisher"]; ?>";
    book_filter["genre"] = "<?php echo $book_filter["genre"]; ?>";

    book_filter["in_stock"] = <?php echo $book_filter["in_stock"]; ?>;

    book_filter["page_on"] = <?php echo $book_filter["page_on"]; ?>;
    book_filter["page_min"] = <?php echo $book_filter["page_min"]; ?>;
    book_filter["page_max"] = <?php echo $book_filter["page_max"]; ?>;

    book_filter["year_on"] = <?php echo $book_filter["year_on"]; ?>;
    book_filter["year_min"] = <?php echo $book_filter["year_min"]; ?>;
    book_filter["year_max"] = <?php echo $book_filter["year_max"]; ?>;

    book_filter["language"] = "<?php echo $book_filter["language"]; ?>";

    console.log(book_filter);

    let sql = `<?php echo ($sql); ?>`;

    console.log(sql);

    </script>

</head>
<body>

    <?php require "header.php"; ?>

	<div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Advanced Filter</h3>
            </div>

            <ul class="list-unstyled components px-3">
                <div class="custom-control custom-switch pb-0">
                    <input type="checkbox" class="custom-control-input" id="switch-pages" data-toggle="collapse" data-target="#collapse-pages">
                    <label class="custom-control-label" for="switch-pages">Filter Pages</label>
                </div>
                <div id="collapse-pages" class="collapse">
                <div class="row mt-2">
                    <div class="col-sm-3">
                    <input type="number" step="1" id="pages-amount1" class="form-control text-right" placeholder="Enter amount1" name="pages-amount1">
                    </div>
                    <div class="col-sm-6 text-center form-control-plaintext text-light">
                    Pages
                    </div>
                    <div class="col-sm-3">
                    <input type="number" step="1" id="pages-amount2" class="form-control" placeholder="Enter amount2" name="pages-amount2">
                    </div>
                </div>
                <div id="pages-range-slider" class="mt-2 range-slider"></div>
                </div>
            </ul>

            <ul class="list-unstyled components px-3 pt-0">
                <div class="custom-control custom-switch pb-0">
                    <input type="checkbox" class="custom-control-input" id="switch-year" data-toggle="collapse" data-target="#collapse-year">
                    <label class="custom-control-label" for="switch-year">Filter Year</label>
                </div>
                <div id="collapse-year" class="collapse">
                <div class="row mt-2">
                    <div class="col-sm-3">
                    <input type="number" step="1" id="year-amount1" class="form-control text-right" placeholder="Enter amount1" name="year-amount1">
                    </div>
                    <div class="col-sm-6 text-center form-control-plaintext text-light">
                    Publish Year
                    </div>
                    <div class="col-sm-3">
                    <input type="number" step="1" id="year-amount2" class="form-control" placeholder="Enter amount2" name="year-amount2">
                    </div>
                </div>
                <div id="year-range-slider" class="mt-2 range-slider"></div>
                </div>
            </ul>

        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex p-3 mb-2">

                <div class="flex-shrink pr-3">
                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                </div>

                <div class="flex-fill">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="input-title">Title</label>
                        <input type="text" placeholder="Any title" class="form-control" id="input-title">
                        </div>
                        <div class="form-group col-md-3">
                        <label for="input-author">Author</label>
                        <input type="text" placeholder="Any author" class="form-control" id="input-author">
                        </div>
                        <div class="form-group col-md-3">
                        <label for="input-publisher">Publisher</label>
                        <input type="text" placeholder="Any publisher" class="form-control" id="input-publisher">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <label for="input-genre">Genres (enter comma separated list)</label>
                            <input type="text" placeholder="Any genre" class="form-control" id="input-genre">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="input-language">Language</label>
                            <select id="input-language" name="languages" class="custom-select">
                                <option value='any' selected>Any</option>
                                <?php
                                foreach($language_map as $lan => $language) {
                                    echo '<option value="'.$lan.'">'.$language.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink pr-5">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="in-stock-check">
                                <label class="form-check-label" for="in-stock-check">
                                    Display books that are in stock only 
                                </label>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <a href="#" class="btn apply-filter" onclick='return apply_filter()'>
                                    <div id="apply-filter-text">
                                        Apply Filter
                                    </div>
                                    <div id="apply-filter-spinner" style="display: none;" class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
            
            <table class="table table-striped table-hover book-table">
            <thead>
                <tr>
                <th scope="col" style="width: 2.5%">#</th>
                <th scope="col" style="width: 15%">Cover</th>
                <th scope="col" style="width: 47.5%">Book</th>
                <th scope="col" style="width: 35%">Genre(s)</th>
                </tr>
            </thead>
            <tbody>

                <?php
                
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {

                        echo '<tr><th scope="row">';
                        echo $row["book_id"];
                        echo '</th><td><img id="cover-';
                        echo $row["original_key"];
                        echo '" class="cover-image" src="no_cover.jpg">';
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
                } else {
                    echo "0 results";
                }

                ?>
            </tbody>
            </table>
        
        </div>
    </div>

    
    <script src="book.js"></script>
</body>
</html>