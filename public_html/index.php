<!-- Begin PHP -->
<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  require_once($doc_root.$config["php"]["common_page"]);
?>
<!-- End PHP -->

<!-- Begin HTML -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $commonPage->makeHTMLHead("Book Store: Get the London Book"); ?>
  </head>
  <!-- End Head -->
  <body>
    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <?php 
        $commonPage->makeBodyHeader("Book Store"); 
        $commonPage->makeNavBar("home");
      
        if(!isset($_SESSION["user_session"]))
        {
          echo "<div class='row'>";
          echo "<h3>Welcome to the Book Store!</h3>";
          echo "<p><a href='login.php'>Sign In</a> or <a href='register.php'>Register</a> to start shopping.</p>";
          echo "</div>";
        } else
        {
          echo "<div class='row'>";
          echo "<h3>Hi ".$_SESSION['user_session']['username']."!</h3>";
          echo "<p>Start shopping <a href='shop.php'>here</a> now.</p>";
          echo "</div>";
        }
      ?>
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
