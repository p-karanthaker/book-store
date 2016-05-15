<!-- Begin HTML-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>Registration</title>
    <meta name="description" content="Registration">
    <meta name="author" content="Karan Thaker">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    
    <!-- JavaScript
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="img/layout/favicon.ico">

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
          <form name="registerform" action="register.php" method="post">
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
      
      <!-- Alert examples
      <div class="alert alert-warning">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Warning!</strong> This is a warning alert.
      </div>

      <div class="alert alert-success">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Success!</strong> You successfully read this important alert message.
      </div>

      <div class="alert alert-error">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Error!</strong> Change a few things up and try submitting again.
      </div>

      <div class="alert alert-info">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>Info!</strong> This alert needs your attention, but it's not super important.
      </div>
      -->
      
    </div>
  </body>
  <!-- End Body-->
</html>
<!-- End HTML -->

<!-- Begin PHP -->
<?php
  // Load Configs
  

  class AccountRegistration
  { 
    public function __construct()
    {
      $config = require_once("../resources/db/db.php");
      $db = new DatabaseHelper();
      
      $db->openConnection();
      $db->closeConnection();
      
      if(isset($_POST["register"]))
      {
        $this->registerUser();
      }
    }
    
    private function registerUser()
    {
        if ($this->validateFormData())
        {
          echo "<div class='container'>
                  <div class='alert alert-success'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <strong>Success!</strong> Form is valid!
                  </div>
                </div>";
        } else 
        {
          echo "<div class='container'>
                  <div class='alert alert-error alert-block'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h5><strong>Invalid Input!</strong></h5> 
                    <ul>
                      <li><strong>Username</strong> must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>.</li>
                      <li><strong>Passwords</strong> must match, and must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>.</li>
                    </ul>
                  </div>
                </div>";
          return false;
        }
    } 
    
    /**
     * Validates the user input on the registration form
     * @return bool Returns whether the form is valid or not
     */    
    private function validateFormData()
    {
      define("MIN_LENGTH", 6);
      define("MAX_LENGTH", 12);
      define("REGEX_MATCHER", '/^[a-z0-9]{6,12}$/i');
      
      if(!empty($_POST["username"])
        && !empty($_POST["password"])
        && !empty($_POST["password_confirm"])
        && !empty($_POST["user_type"])
        && strlen($_POST["username"]) <= MAX_LENGTH
        && strlen($_POST["username"]) >= MIN_LENGTH
        && strlen($_POST["password"]) <= MAX_LENGTH
        && strlen($_POST["password"]) >= MIN_LENGTH
        && preg_match(REGEX_MATCHER, $_POST["username"])
        && preg_match(REGEX_MATCHER, $_POST["password"])
        && ($_POST["password"] === $_POST["password_confirm"])
        && ($_POST["user_type"] === "Student") || ($_POST["user_type"] === "Staff"))
      {
        return true;
      } else {
        return false;
      }
    }
    
    function random()
    {
      if(isset($_POST["register"])) 
      {
        $salt = bin2hex(openssl_random_pseudo_bytes(4));
        echo $salt, "<br>";
        $username = $_POST["username"];
        $password_hash = crypt($_POST["password"], $salt);
        $password_confirm_hash = crypt($_POST["password_confirm"], $salt);
        $user_type = $_POST["user_type"];
        echo $username, "<br>", $password_hash, "<br>", $password_confirm_hash, "<br>", $user_type, "<br>";

      } else 
      {
        return false;
      }
    }
    
  }
  $application = new AccountRegistration();
?>
<!-- End PHP -->

<!-- End Document -->
