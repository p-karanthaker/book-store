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
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">

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

        <!-- Login Form -->
        <div id="loginForm" class="row">
          <div class="four columns offset-by-one-third">
            <form id="register" action="register.php" method="post">
            <label for="username">Username</label>
            <input class="u-full-width" type="text" placeholder="Username" id="username" name="username" required>

            <label for="password">Password</label>
            <input class="u-full-width" type="password" placeholder="Password" id="password" name="password" required>
            <input class="u-full-width" type="password" placeholder="Confirm" id="passwordConfirm" name="passwordConfirm" required>

            <label for="userType">User Type</label>
            <select class="u-full-width" id="userType" required>
              <option value="Option 1">Student</option>
              <option value="Option 2">Staff</option>
            </select>
            <input class="button-primary u-full-width" type="submit" name="submit" value="Register">
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

?>
<!-- End PHP -->

<!-- End Document -->
