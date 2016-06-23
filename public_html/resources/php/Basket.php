<?php
  session_start();
  $basket = new Basket();

  class Basket
  {
   
    public function __construct()
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
      $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$config["paths"]["messages"]);
      $message = new Messages();
      $db = new DatabaseHelper($config);
      
      $category = "";
      if(isset($_POST['Book']))
      {
        echo $message->createMessage("Added", array("book id ".$_POST['Book']." to your basket."), "success", false, false);
      }
      $db->closeConnection();
    }
    
  }
?>