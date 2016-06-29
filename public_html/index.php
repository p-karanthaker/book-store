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
    <title>Book Store: Get the London Book</title>
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
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="<?php echo $config["paths"]["host"].$config["paths"]["favicon"]; ?>">

  </head>
  <!-- End Head -->
  <body>

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="nine columns" style="margin-top: 5%">
          <h1>Book Store</h1>
        </div>
        <!-- End Title -->
        <!-- Log In -->
        <!-- TODO: If there is a session, then show "Sign Out" and "My Account" -->
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
        <!-- End Login -->
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li class="active">Home</li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="basket.php">Basket</a></li>
            <li><a href="account.php">My Account</a></li>
            <?php isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] == "STAFF" ? print_r("<li><a href='staff.php'>Staff</a></li>") : "" ?>
          </ol>
        </div>
      </div>
      
      <?php 
            if(!isset($_SESSION["user_session"]))
            {
              echo "<div class='row'>";
              echo "<h3>Welcome to the Book Store!</h3>";
              echo "<p><a href='login.php'>Sign In</a> or <a href='register.php'>Register</a> to start shopping.</p>";
              echo "</div>";
            } else
            {
              echo "<div class='row'>";
              echo "<h3>Hi ".$_SESSION['user_session']['username']."!</h3>";
              echo "<p>Start shopping <a href='shop.php'>here</a> now.</p>";
              echo "</div>";
            }
      ?>
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
