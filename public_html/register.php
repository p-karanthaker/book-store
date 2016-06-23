<!-- Begin PHP -->
<?php
  session_start();

  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."resources/configs/config.ini", true);

  if(isset($_SESSION["user_session"]))
  {
    header("Location: http://localhost/".$config["paths"]["index"], true, 303);
  }
?>
<!-- End PHP -->

<!-- Begin HTML-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>Registration</title>
    <meta name="description" content="Registration">
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
      <!-- Title -->
      <div class="row">
        <div class="twelve columns" style="margin-top: 5%">
          <h2 class="u-text-centre">Register</h2>
        </div>
      </div>
      <!-- End Title -->

      <!-- Registration Form -->
      <div class="row">
        <div class="four columns offset-by-one-third">
          <form name="registerform" action="resources/php/AccountRegistration.php" method="post">
            <label for="username">Username</label>
            <input class="u-full-width" type="text" placeholder="Username" name="username" required>

            <label for="password">Password</label>
            <input class="u-full-width" type="password" placeholder="Password" name="password" required>
            <input class="u-full-width" type="password" placeholder="Confirm" name="password_confirm" required>

            <label for="user_type">User Type</label>
            <select class="u-full-width" name="user_type" required>
              <option value="Student">Student</option>
              <option value="Staff">Staff</option>
            </select>
            <input class="button-primary u-full-width" type="submit" name="register" value="Register">
            <a class="button u-full-width" href="index.php">Cancel</a>
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
  if(isset($_SESSION["message"]))
  {
    echo $_SESSION["message"];
    $_SESSION["message"] = null;
  }
?>
<!-- End PHP -->

<!-- End Document -->
