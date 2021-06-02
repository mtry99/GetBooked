<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Book Search</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="book.css">

</head>
<body>

    <?php require "header.php"; ?>

	<div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Book Filter</h3>
            </div>

            <ul class="list-unstyled components px-3">
                <div class="custom-control custom-switch pb-0">
                    <input type="checkbox" class="custom-control-input" id="switch-pages" data-toggle="collapse" data-target="#collapse-pages">
                    <label class="custom-control-label" for="switch-pages">Filter Pages</label>
                </div>
                <div id="collapse-pages" class="collapse">
                <div class="row mt-2">
                    <div class="col-sm-3">
                    <input type="text" pattern="\d*" id="pages-amount1" class="form-control text-right" placeholder="Enter amount1" name="pages-amount1">
                    </div>
                    <div class="col-sm-6 text-center form-control-plaintext text-light">
                    Pages
                    </div>
                    <div class="col-sm-3">
                    <input type="text" pattern="\d*" id="pages-amount2" class="form-control" placeholder="Enter amount2" name="pages-amount2">
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
                    <input type="text" pattern="\d*" id="year-amount1" class="form-control text-right" placeholder="Enter amount1" name="year-amount1">
                    </div>
                    <div class="col-sm-6 text-center form-control-plaintext text-light">
                    Publish Year
                    </div>
                    <div class="col-sm-3">
                    <input type="text" pattern="\d*" id="year-amount2" class="form-control" placeholder="Enter amount2" name="year-amount2">
                    </div>
                </div>
                <div id="year-range-slider" class="mt-2 range-slider"></div>
                </div>
            </ul>

            <!--
            <ul class="list-unstyled CTAs">
                <li>
                    <a href="#" class="apply-filter ">Apply Filter</a>
                </li>
            </ul>
            -->
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
                    <div class="form-group">
                    <label for="input-genre">Genre (enter comma separated list)</label>
                    <input type="text" placeholder="Any genre" class="form-control" id="input-genre">
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink pr-5">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="in-stock-check">
                                <label class="form-check-label" for="in-stock-check">
                                    Display only in stock books
                                </label>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <a href="#" class="btn apply-filter" onclick='return apply_filter()'>Apply Filter</a>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
            
            <h2>Collapsible Sidebar Using Bootstrap 4</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h3>Lorem Ipsum Dolor</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>

    
    <script src="book.js"></script>
</body>
</html>