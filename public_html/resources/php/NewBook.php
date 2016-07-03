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
    <title>New Book</title>
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
    
    <form id="bookForm">
      <div id="addBookPanel">
        <h2>Add New Book</h2>
        <label>Title</label>
        <input id="bookTitle" class="u-full-width" type="text" placeholder="Title">
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
        <div id="categorySection">
          <label>Category: 
            <select id="category" name="category">
              <option value="none" selected>-</option>
            </select>
          </label>
          <label>Categories</label>
          <textarea id="categoryList" class="u-full-width" placeholder="Categories..." style="resize:none" disabled></textarea>
          <button id="removeCategory" class="u-full-width" type="button"><i class="fa fa-trash fa-lg"></i> Remove Last Category</button>
        </div>
        <div class="row">
        <div class="one-half column">
          <label>Quantity</label>
          <input id="quantity" class="u-full-width" type="number" min="1" max="100" value="1"/>
        </div>
        <div class="one-half column">
          <label>Price <i class="icon-gbp icon"></i></label>
          <input id="price" class="u-full-width" type="number" min="1" max="100" value="1"/>
        </div>
        <input id="addNewBook" class="button-primary u-full-width" type="button" name="register" value="Add New Book">
        </div>
      </div>
    </form>
  </body>
  