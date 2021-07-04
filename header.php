<nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">Library</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav mr-auto mt-2 mt-lg-0">
        <?php
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        //var_dump($uri_parts[0]);
        ?>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/book.php"?"active":"" ?>" href="/book.php">Search Books </span></a>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/book_detail.php"?"active":"" ?>" href="/book_detail.php">Random Book </a>
        <a class="nav-item nav-link <?php echo ($uri_parts[0] === "/collection.php" || $uri_parts[0] === "/collection_detail.php")?"active":"" ?>" href="/collection.php">Collections </a>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/inventory.php"?"active":"" ?>" href="/inventory.php">My Inventory </a>
        <?php if (!((isset($_SESSION["isadmin"])) && ($_SESSION["isadmin"] === true))): ?>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/account.php"?"active":"" ?>" href="/account.php">My Borrow History </a>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/fines.php"?"active":"" ?>" href="/fines.php">Fines </a>
        <?php endif; ?>
        <?php if ((isset($_SESSION["isadmin"])) && ($_SESSION["isadmin"] === true)): ?>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/new-book.php"?"active":"" ?>"  href="/new-book.php">Add New Book </a>
        <a class="nav-item nav-link <?php echo $uri_parts[0] === "/pay-fine.php"?"active":"" ?>"  href="/pay-fine.php">Pay Fine </a>
        <?php endif; ?>

      

    </div>
    <div class="form-inline my-2 my-lg-0">
      <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]): ?>
      <a class="nav-item nav-link btn btn-danger btn-logout ml-1 <?php  $_SESSION["loggedin"]= false ?>" href="/logout.php"> Logout </a>
      <?php else: ?>
      <a class="nav-item nav-link btn btn-success btn-logout ml-1" href="/login.php"> Login </a>
      <a class="nav-item nav-link btn btn-outline-success btn-logout ml-1" href="/register.php"> Sign Up </a>
      <?php endif; ?>

    </div>
  </div>
</nav>