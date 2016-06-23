<!-- Begin PHP -->
<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  $messages = require_once($doc_root.$config["paths"]["messages"]);

  if(isset($_SESSION["message"]))
  {
    echo "<br />".$_SESSION["message"];
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
    <script src="js/shop.js"></script>
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="img/layout/favicon.ico">

  </head>
  <!-- End Head -->
  <body onload="categoryFilter()">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="twelve columns" style="margin-top: 5%">
          <h1>Shop</h1>
        </div>
        <!-- End Title -->
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active">Shop</li>
            <li><a href="basket.php">Basket</a></li>
            <li><a href='account.php'>My Account</a></li>
          </ol>
        </div>
      </div>
      
      <div class="row">
        <div class="twelve columns">
          <?php
            if(!isset($_SESSION['user_session']))
            {
              $message = new Messages();
              $message->createMessage("Access Denied!", array("You must be logged in to view this page."), "error");
              echo $_SESSION["message"];
              $_SESSION["message"] = null;
              die();
            }
          ?>
          
          <div id='bookDetails' class="w3-card-4">
            <header class="w3-container-header w3-blue"><h3 id='bookTitle'>Details</h3></header>
            <div class="w3-container-central">
            <p id='bookDescription'>View book details and Add to Basket here by clicking on a book.</p>
            </div>
          </div>
          <br />
        
          <input class="u-full-width" type="text" id="search" placeholder="Start typing to search">
          <label>Filter By Category: 
            <select id="category" name="category" onchange="categoryFilter()">
              <option value="all" selected>All</option>
              <option value="1">Business &amp; Economics</option>
              <option value="2">History</option>
              <option value="3">Computers</option>
              <option value="4">Mathematics</option>
              <option value="5">Photography</option>
              <option value="6">Art</option>
              <option value="7">Music</option>
              <option value="8">Religion</option>
              <option value="9">Science</option>
              <option value="10">Nature</option>
              <option value="11">Medical</option>
            </select>
          </label>
          <table class="u-full-width" id="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Authors</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
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
