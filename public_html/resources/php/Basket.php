<?php
  session_start();
  $basket = new Basket();

  class Basket
  {
    private $db;    
    
    public function __construct()
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
      $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$config["paths"]["messages"]);
      $message = new Messages();
      $this->db = new DatabaseHelper($config);
      
      $category = "";
      if(isset($_POST["Book"]))
      {
        if($this->addToBasket($_SESSION["user_session"]["user_id"], $_POST["Book"]))
        {
          echo $message->createMessage("Added", array("book id ".$_POST['Book']." to your basket."), "success", false, false);
        } else
        {
          echo $message->createMessage("Error", array("Unable to add item to your basket right now. Please try again later."), "error", false, false);
        }
      } else if(isset($_POST["DispBasket"]))
      {
        $this->showBasket($_SESSION["user_session"]["user_id"]);
      }
      $this->db->closeConnection();
    }
    
    private function addToBasket($user_id, $book_id)
    {
      if(is_int(intval($book_id)))
      {
        $results = "";
        if($this->db->openConnection())
        {
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL AddItemToBasket(:user_id, :book_id)");
          $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":book_id", $book_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          if($statement->execute())
          {
            return true;
          }
        }
        return false;
      }
    }
    
    private function showBasket($user_id)
    {
      $results = "";
      if($this->db->openConnection())
      {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBasketByUserId(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach($results as $arr)
        {
          echo "<tr id='basket-items'>";
          echo "<td data-book-id=".$arr['book_id'].">".utf8_encode($arr['title'])."</td>";
          echo "<td><input id='quantity' type='number' value='".utf8_encode($arr['quantity'])."' min='0' max='".utf8_encode($arr['quantity'])."'/></td>";
          echo "<td>".utf8_encode($arr['price'])."</td>";
          echo "</tr>";
        }
      }
    }
    
  }
?>