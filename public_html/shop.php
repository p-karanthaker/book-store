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
      $commonPage->makeHTMLHead("Book Store: Shop");
      echo "<script src=".$config["javascript"]["shop"]."></script>";
    ?>
  </head>
  <!-- End Head -->
  <body>

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php 
        $commonPage->makeBodyHeader("Shop"); 
        $commonPage->makeNavBar("shop");
        $commonPage->restrictAccess(false);
      ?>
      
      <div class="row">
        <div class="twelve columns">
          <div id="basket-alert-section" class="u-float-top u-full-width u-text-center"></div>
          
          <div id="bookDetails" class="w3-card-4">
            <header class="w3-container-header w3-blue"><h3 id="bookTitle">Details</h3></header>
            <div class="w3-container-central">
            <p id="bookDescription">View book details and Add to Basket here by clicking on a book.</p>
            </div>
          </div>
          
          <br />
        
          <input class="u-full-width" type="text" id="search" placeholder="Start typing to search">
          <label>Filter By Category: 
            <select id="category" name="category" onchange="categoryFilter()">
              <option value="all" selected>All</option>
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
