<!-- Begin PHP -->
<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  require_once($doc_root.$config["php"]["common_page"]);
  $messages = new Messages();
?>
<!-- End PHP -->

<!-- Begin HTML -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      $commonPage->makeHTMLHead("Book Store: Staff Console");
      echo "<script src=".$config["javascript"]["staff"]."></script>";
    ?>

  </head>
  <!-- End Head -->
  <body>
    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php 
        $commonPage->makeBodyHeader("Staff Console"); 
        $commonPage->makeNavBar("staff");
        $commonPage->restrictAccess(true);
      ?>
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
