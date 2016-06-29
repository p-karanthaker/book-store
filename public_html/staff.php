<!-- Begin PHP -->
<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
  $messages = require_once($doc_root.$config["paths"]["messages"]);
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
    <link rel="icon" href="<?php echo $config["paths"]["host"].$config["paths"]["favicon"]; ?>">

  </head>
  <!-- End Head -->
  <body onload="">

    <!-- Primary Page Layout
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="twelve columns" style="margin-top: 5%">
          <h1>Staff Console</h1>
        </div>
        <!-- End Title -->
      </div>

      <div class="row">
        <div class="twelve columns">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="basket.php">Basket</a></li>
            <li><a href="account.php">My Account</a></li>
            <li class="active">Staff</li>
          </ol>
        </div>
      </div>
      
      <div class="row">
        <div class="twelve columns">
          <?php
            if(!isset($_SESSION["user_session"]) || (isset($_SESSION["user_session"]) && $_SESSION["user_session"]["user_type"] != "STAFF"))
            {
              $message = new Messages();
              $message->createMessage("<i class='fa fa-lock'></i>", array("Only members of staff can access this page."), "error", ["dismissable" => false]);
              echo $_SESSION["message"];
              $_SESSION["message"] = null;
              die();
            }
          ?>
        </div>
      </div>
      
      <div class="row">
        <div class="three columns">
          <a id="users" class="button u-full-width" href="">Users</a>
          <a id="addBook" class="button u-full-width" href="">Add Books</a>
        </div>
        <div class="nine columns">
          <div id="controlPanel">
            <div id="addBookPanel">
              <h2>Add New Book</h2>
              <label>Title</label><input class="u-full-width" type="text" placeholder="Title" name="bookTitle" required>
              <label>Authors</label>
              <div>
                <input id="newAuthor" type="text" placeholder="Author">
                <span>
                  <button id="addAuthor" type="button"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                </span>
              </div>
              <textarea id="authorList" class="u-full-width" placeholder="Authors..." style="resize:none" disabled></textarea>
              <button id="removeAuthor" class="u-full-width" type="button"><i class="fa fa-trash fa-lg"></i> Remove Last Author</button>
              <label>Description</label>
              <textarea id="description" class="u-full-width" placeholder="Description..." style="resize:none"></textarea>
              <!-- Placeholder for Category selection -->
              <div class="row">
                <div class="one-half column">
                  <label>Quantity</label>
                  <input class="u-full-width" type="number" min="1" max="100" value="1"/>
                </div>
                <div class="one-half column">
                  <label>Price <i class="icon-gbp icon"></i></label>
                  <input class="u-full-width" type="number" min="1" max="100" value="1"/>
                </div>
                <input id="addNewBook" class="button-primary u-full-width" type="submit" name="register" value="Add New Book">
              </div>
            </div>
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
