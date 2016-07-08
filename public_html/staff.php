<!-- Begin PHP -->
<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $messages = new Messages();
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
    <link rel="icon" href="<?php echo $config["paths"]["baseurl"].$config["images"]["favicon"]; ?>">

  </head>
  <!-- End Head -->
  <body onload="">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php
        if(isset($_SESSION["user_session"]))
        {
          require_once($doc_root.$config["php"]["header_bar"]);
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
              $messages->createMessage("<i class='fa fa-lock'></i>", array("Only members of staff can access this page."), "error", ["dismissable" => false]);
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
            <?php
              $msg_details = array("<strong><h3>Users</h3></strong><p>Click Users to view details of Book Store users and top up their accounts.</p>
                                    <strong><h3>Orders</h3></strong><p>Click Orders to view details of user orders and complete the order.</p>
                                    <strong><h3>Add Books</h3></strong><p>Click Add Books to add new books into the Book Store catalogue.</p>");
              echo $messages->createMessage("<i class='fa fa-info-circle fa-lg' aria-hidden='true'></i> Staff Actions", $msg_details, "info", ["isBlock" => true, "inSessionVar" => false, "dismissable" => false]);
            ?>
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
