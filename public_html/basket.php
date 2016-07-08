<!-- Begin PHP -->
<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  require_once($doc_root.$config["php"]["common_page"]);
  $messages = new Messages();

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
    <?php
      $commonPage->makeHTMLHead("Book Store: Basket");
      echo "<script src=".$config["javascript"]["basket"]."></script>";
    ?>
  </head>
  <!-- End Head -->
  <body onload="showBasket()">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php 
        $commonPage->makeBodyHeader("Basket"); 
        $commonPage->makeNavBar("basket");
        $commonPage->restrictAccess(false);
      ?>
      
      <div class="row">
        <div class="twelve columns">
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
