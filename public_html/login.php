<!-- Begin PHP -->
<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  require_once($doc_root.$config["php"]["common_page"]);

  if(isset($_SESSION["user_session"]))
  {
    header("Location: ".$config["paths"]["baseurl"].$config["content"]["index"], true, 303);
  }
?>
<!-- End PHP -->

<!-- Begin HTML -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $commonPage->makeHTMLHead("Book Store: Sign In"); ?>
  </head>
  <!-- End Head -->
  <body>

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <!-- Title -->
      <div class="row">
        <div class="twelve columns" style="margin-top: 5%">
          <h2 class="u-text-centre">Book Store</h2>
        </div>
      </div>
      <!-- End Title -->
      
      <!-- Login Form -->
      <div id="loginForm" class="row">
        <div class="four columns offset-by-one-third">
          <form id="loginForm" action="<?php echo $config['php']['account_login']; ?>" method="post">
              <label for="username">Username</label>
              <input class="u-full-width" type="text" placeholder="Username" id="username" name="username" required>

              <label for="password">Password</label>
              <input class="u-full-width" type="password" placeholder="Password" id="password" name="password" required>

              <input class="button-primary u-full-width" type="submit" name="login" value="Sign In">
              <a class="button u-full-width" href="register.php">Not a member?</a>
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
  if(isset($_SESSION["message"]))
  {
    echo $_SESSION["message"];
    $_SESSION["message"] = null;
  }
?>
<!-- End PHP -->

<!-- End Document -->
