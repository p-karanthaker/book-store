<?php
  session_start();
  $basket = new Basket();
  die();

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
      $user_id = $_SESSION["user_session"]["user_id"];
      if(isset($_POST["bookId"]))
      {
        if($this->addToBasket($user_id, $_POST["bookId"]))
        {
          echo $message->createMessage("Added", array($_POST['bookTitle']." to your basket."), "success", false, false);
        } else
        {
          echo $message->createMessage("Error", array("Unable to add item to your basket right now. Please try again later."), "error", false, false);
        }
      } else if(isset($_POST["showBasket"]))
      {
        $this->showBasket($user_id);
      } else if(isset($_POST["updateBasket"]))
      {
        $arr = explode(",", $_POST["updateBasket"]);
        $books = array_chunk($arr, 2);
        
        foreach($books as $book)
        {
          $this->updateBasket($user_id, $book[0], $book[1]);
        }
      } else if(isset($_POST["emptyBasket"]))
      {
        $this->emptyBasket($user_id);
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
        $totalPrice = 0;
        foreach($results as $arr)
        {
          echo "<tr id='items' data-book-id=".utf8_encode($arr['book_id']).">";
          echo "<td id='bookTitle'>".utf8_encode($arr['title'])."</td>";
          echo "<td><input id='quantity' type='number' value='".utf8_encode($arr['quantity'])."' min='0' max='".utf8_encode($arr['quantity'])."'/></td>";
          echo "<td id='bookPrice'>£".utf8_encode($arr['price'])."</td>";
          echo "</tr>";
          $totalPrice += utf8_encode($arr['price']);
        }
        echo "<tr id='totalPrice'>";
        echo "<td></td>";
        echo "<td><strong>Total<strong></td>";
        echo "<td>£".$totalPrice."</td>";
      }
    }
    
    private function updateBasket($user_id, $book_id, $new_amount)
    {
      if(is_int(intval($book_id)) && is_int(intval($new_amount)))
      {
        $results = "";
        if($this->db->openConnection())
        {
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL RemoveItemFromBasket(:user_id, :book_id, :new_amount)");
          $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":book_id", $book_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":new_amount", $new_amount, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          
          if($statement->execute())
          {
            return true;
          }
        }
        return false;
      }
    }
    
    private function emptyBasket($user_id)
    {
      $results = "";
      if($this->db->openConnection())
      {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL EmptyBasket(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);

        if($statement->execute())
        {
          return true;
        }
      }
      return false;
    }
    
  }
?>