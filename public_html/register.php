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
    <?php $commonPage->makeHTMLHead("Book Store: Register"); ?>
  </head>
  <!-- End Head -->
  <body>

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">  
      <!-- Title -->
      <div class="row">
        <div class="twelve columns" style="margin-top: 5%">
          <h2 class="u-text-centre">Register</h2>
        </div>
      </div>
      <!-- End Title -->

      <!-- Registration Form -->
      <div class="row">
        <div class="four columns offset-by-one-third">
          <form name="registerform" action="<?php echo $config['php']['account_registration']; ?>" method="post">
            <label for="username">Username</label>
            <input class="u-full-width" type="text" placeholder="Username" name="username" required>

            <label for="password">Password</label>
            <input class="u-full-width" type="password" placeholder="Password" name="password" required>
            <input class="u-full-width" type="password" placeholder="Confirm" name="password_confirm" required>

            <label for="user_type">User Type</label>
            <select class="u-full-width" name="user_type" required>
              <option value="Student">Student</option>
              <option value="Staff">Staff</option>
            </select>
            <input class="button-primary u-full-width" type="submit" name="register" value="Register">
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
