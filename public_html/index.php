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
      <div class="row">
        <!-- Title -->
        <div class="two-thirds column" style="margin-top: 5%">
          <h1>Book Store</h1>
        </div>
        <!-- End Title -->
        <!-- Log In -->
        <!-- TODO: If there is a session, then show "Sign Out" and "My Account" -->
        <div class="one-third column" style="margin-top: 5%">
          <a class="button button-primary u-full-width" href="login.php">Sign In</a>
          <a class="button button-primary u-full-width" href="login.php">My Account</a>
        </div>
        <!-- End Login -->
      </div>

      <div class="row">
        <div class="twelve columns">
        <ol class="breadcrumb">
          <li class="active">Home</li>
          <li><a href="#">Books</a></li>
          <li><a href="#">Basket</a></li>
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
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config_ini = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  
  var_dump($config_ini);

  var_dump($config_ini["database_dev"]);
?>
<!-- End PHP -->

<!-- End Document -->
