<nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">Library</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
        <?php
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        //var_dump($uri_parts[0]);
        ?>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/book.php"?"active":"" ?>" href="/book.php">Search Books </span></a>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/book_detail.php"?"active":"" ?>" href="/book_detail.php">Random Book </a>
        <a class="nav-item nav-link" href="/account.php">Account History </a>
        <?php if ((isset($_SESSION["isadmin"])) && ($_SESSION["isadmin"] === true)): ?>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/book_detail.php"?"active":"" ?>"  href="/new-book.php">Add New Book </a>
        <?php endif; ?>
        <a class="nav-item nav-link <?php  $_SESSION["loggedin"]= false?>" href="/login.php"> Logout </a>
    </div>
  </div>
</nav>