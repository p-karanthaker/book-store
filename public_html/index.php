<!-- Begin PHP -->
<?php
    session_start();
    if(isset($_SESSION["message"]))
    {
      echo "</br>".$_SESSION["message"];
      $_SESSION["message"] = null;
    }
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
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">

    <!-- JavaScript
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="img/layout/favicon.ico">

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
              echo "<form id='logoutForm' action='resources/php/AccountLogin.php' method='post'>
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
          <li><a href="#">Books</a></li>
          <li><a href="#">Basket</a></li>
          <?php echo isset($_SESSION["user_session"]) ? "<li><a href='account.php'>My Account</a></li>" : ""; ?>
        </ol>
          </div>
      </div>

    </div>
  </body>
  <!-- End Body -->
</html>
<!-- End HTML -->

<!-- Begin PHP -->
<?php

?>
<!-- End PHP -->

<!-- End Document -->
