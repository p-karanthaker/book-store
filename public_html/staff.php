<!-- Begin PHP -->
<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
  $messages = require_once($doc_root.$config["paths"]["messages"]);
?>
<!-- End PHP -->

<!-- Begin HTML -->
<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>Staff Console</title>
    <meta name="description" content="Aston Book Store Project">
    <meta name="author" content="Karan Thaker">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/skeleton.css">
    <script src="https://use.fontawesome.com/824b9bf6de.js"></script>

    <!-- JavaScript
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="/js/staff.js"></script>
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="<?php echo $config["paths"]["host"].$config["paths"]["favicon"]; ?>">

  </head>
  <!-- End Head -->
  <body onload="">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php
        if(isset($_SESSION["user_session"]))
        {
          require_once($doc_root.$config["paths"]["header"]);
        }
      ?>
      
      <div class="row">
        <!-- Title -->
        <div class="nine columns" style="margin-top: 5%">
          <h1>Staff Console</h1>
        </div>
        <!-- End Title -->
        <div class="three columns" style="margin-top: 5%">
          <?php 
            if(isset($_SESSION["user_session"]))
            {
              echo "<form id='logoutForm' action='/resources/php/AccountLogin.php' method='post'>
                      <input class='button-primary u-full-width' type='submit' name='logout' value='Sign Out'>
                    </form>";
            } else
            {
              echo "<a class='button button-primary u-full-width' href='login.php'>Sign In</a>";
            }
          ?>
        </div>
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="basket.php">Basket</a></li>
            <li class="active">Staff</li>
          </ol>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <?php
            if(!isset($_SESSION["user_session"]) || (isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] != "STAFF"))
            {
              $message = new Messages();
              $message->createMessage("<i class='fa fa-lock'></i>", array("Only members of staff can access this page."), "error", ["dismissable" => false]);
              echo $_SESSION["message"];
              $_SESSION["message"] = null;
              die();
            }
          ?>
        </div>
      </div>
      <div id="basket-alert-section" class="u-float-top u-full-width u-text-center"></div>
      
      <div class="row">
        <div class="three columns">
          <a id="users" class="button u-full-width" href="/resources/php/UserInfo.php">Users</a>
          <a id="orders" class="button u-full-width" href="/resources/php/UserOrders.php">Orders</a>
          <a id="addBook" class="button u-full-width" href="/resources/php/NewBook.php">Add Books</a>
        </div>
        <div class="nine columns">
          <div id="controlPanel">
            <h3>Use the buttons on the side!</h3>
          </div>
        </div>
      </div>
    </div>
  </body>
  <!-- End Body -->
</html>
<!-- End HTML -->

<!-- Begin PHP -->
<?php
  if(isset($_SESSION["message"]))
  {
    echo "<br />".$_SESSION["message"];
    $_SESSION["message"] = null;
  }
?>
<!-- End PHP -->

<!-- End Document -->
