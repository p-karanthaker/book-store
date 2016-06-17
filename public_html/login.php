<!-- Begin HTML -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>Book-Store: Sign In</title>
    <meta name="description" content="Aston Book Store Project">
    <meta name="author" content="Karan Thaker">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">

    <!-- JavaScript
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="img/layout/favicon.ico">

  </head>
  <!-- End Head -->
  <body>

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <!-- Title -->
      <div class="row">
        <div class="twelve columns" style="margin-top: 5%">
          <h2 class="u-text-centre">Book Store</h2>
        </div>
      </div>
      <!-- End Title -->
      
      <!-- Login Form -->
      <div id="loginForm" class="row">
        <div class="four columns offset-by-one-third">
          <form id="loginForm" action="resources/php/AccountLogin.php" method="post">
              <label for="username">Username</label>
              <input class="u-full-width" type="text" placeholder="Username" id="username" name="username">

              <label for="password">Password</label>
              <input class="u-full-width" type="password" placeholder="Password" id="password" name="password">

              <input class="button-primary u-full-width" type="submit" name="login" value="Sign In">
              <a class="button u-full-width" href="register.php">Not a member?</a>
          </form>
        </div>
      </div>
      <!-- End Login Form -->
    </div>
  </body>
  <!-- End Body-->
</html>
<!-- End HTML -->

<!-- Begin PHP -->
<?php
  session_start();
  if(isset($_SESSION["message"]))
  {
    echo $_SESSION["message"];
    $_SESSION["message"] = null;
  }
?>
<!-- End PHP -->

<!-- End Document -->
