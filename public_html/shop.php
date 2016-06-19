<!-- Begin PHP -->
<?php
  if(isset($_SESSION["message"]))
  {
    echo "</br>".$_SESSION["message"];
    $_SESSION["message"] = null;
  }

  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);
  
  $db = new DatabaseHelper($config);
  $results = "";
  if($db->openConnection())
  {
    $connection = $db->getConnection();
    $statement = $connection->prepare("SELECT title, authors, quantity, price FROM books");
    if($statement->execute())
    {
      $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
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
    <script src="js/table-filter.js"></script>
    
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
        <div class="twelve columns" style="margin-top: 5%">
          <h1>Book Store</h1>
        </div>
        <!-- End Title -->
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active">Books</li>
            <li><a href="#">Basket</a></li>
            <?php echo isset($_SESSION["user_session"]) ? "<li><a href='account.php'>My Account</a></li>" : ""; ?>
          </ol>
        </div>
      </div>

      <div class="row">
        <div class="twelve columns">
          <input class="u-full-width" type="text" id="search" placeholder="Type to search">
          <table class="u-full-width" id="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Authors</th>
                <th>Quantity</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($results as $arr)
                {
                  echo "<tr>";
                  foreach($arr as $value)
                  {
                    echo "<td>".utf8_encode($value)."</td>";
                  }
                  echo "</tr>";
                }
              ?>
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
