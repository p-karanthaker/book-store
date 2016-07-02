<!-- Begin PHP -->
<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
  $messages = require_once($doc_root.$config["paths"]["messages"]);
?>
<!-- Add Book Panel -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>User Orders</title>
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
    
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="<?php echo $config["paths"]["host"].$config["paths"]["favicon"]; ?>">
  </head>
  <body>
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
    
    <div id="userOrdersPanel">
      <h2>User Orders</h2>
      
    </div>
  </body>
  