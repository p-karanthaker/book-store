<!-- Begin PHP -->
<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
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
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/skeleton.css">
    <script src="https://use.fontawesome.com/824b9bf6de.js"></script>

    <!-- JavaScript
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="/js/basket.js"></script>
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="<?php echo $config["paths"]["host"].$config["paths"]["favicon"]; ?>">

  </head>
  <!-- End Head -->
  <body onload="showBasket()">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="twelve columns" style="margin-top: 5%">
          <h1>My Basket</h1>
        </div>
        <!-- End Title -->
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li class="active">Basket</li>
            <li><a href="account.php">My Account</a></li>
            <?php isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] == "STAFF" ? print_r("<li><a href='staff.php'>Staff</a></li>") : "" ?>
          </ol>
        </div>
      </div>
      
      <div class="row">
        <div class="twelve columns">
          <?php
            if(!isset($_SESSION["user_session"]))
            {
              $message = new Messages();
              $message->createMessage("<i class='fa fa-lock'></i>", array("You must be logged in to view this page."), "error", ["dismissable" => false]);
              echo $_SESSION["message"];
              $_SESSION["message"] = null;
              die();
            }
          ?>
          
          <div id="basket-alert-section" class="u-float-top u-full-width u-text-center"></div>
          
          <table class="u-full-width" id="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="basket-items">
            </tbody>
          </table>
          <button id="checkoutNow" name="checkoutNow" class="button-primary u-pull-right" type="submit"><i class="fa fa-shopping-basket fa-lg" aria-hidden="true"></i> Checkout</button>
          <button id="updateBasket" type="submit"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Update Basket</button>
          <button id="emptyBasket" type="submit"><i class="fa fa-trash fa-lg" aria-hidden="true"></i> Empty Basket</button>
          
          <div id="orderDetails" class="w3-card-4">
            
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

?>
<!-- End PHP -->

<!-- End Document -->
