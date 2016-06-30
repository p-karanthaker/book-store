<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $basket = new Basket();
  die();

  class Basket
  {
    private $messages;
    private $db;  
    
    public function __construct()
    {
      global $messages;
      global $db;
      $this->messages = $messages;
      $this->db = $db;
      
      $category = "";
      $user_id = $_SESSION["user_session"]["user_id"];
      if(isset($_POST["bookId"]))
      {
        $this->addToBasket($user_id, $_POST["bookId"]);
      } else if(isset($_POST["showBasket"]))
      {
        $this->showBasket($user_id);
      } else if(isset($_POST["updateBasket"]))
      {
        if(!empty($_POST["updateBasket"]))
        {
          $arr = explode(",", $_POST["updateBasket"]);
          $books = array_chunk($arr, 2);
          $updated = false;
          foreach($books as $book)
          {
            $updated = $this->updateBasket($user_id, $book[0], $book[1]);
          }
          if($updated)
          {
            echo $this->messages->createMessage("Info:", array("Your basket has been updated."), "info", ["inSessionVar" => false, "dismissable" => false]);
          } else 
          {
            echo $this->messages->createMessage("Info:", array("Nothing to update."), "info", ["inSessionVar" => false, "dismissable" => false]);
          }
        }
      } else if(isset($_POST["emptyBasket"]))
      {
        $this->emptyBasket($user_id);
      }
    }
    
    private function addToBasket($user_id, $book_id)
    {
      if(ctype_digit($book_id))
      {
        try
        {
          $results = "";
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL AddItemToBasket(:user_id, :book_id)");
          $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":book_id", $book_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          if($statement->execute())
          {
            echo $this->messages->createMessage("Added", array($_POST['bookTitle']." to your basket."), "success", ["inSessionVar" => false, "dismissable" => false]);
          }
          echo $this->messages->createMessage("Error", array("Unable to add item to your basket right now. Please try again later."), "error", ["inSessionVar" => false, "dismissable" => false]);
        } catch (PDOException $ex)
        {
          $this->db->showError($ex, false);
        } finally
        {
          $this->db->closeConnection();
        }
      }
    }
    
    private function showBasket($user_id)
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBasketByUserId(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        if(!empty($results))
        {
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
        } else {
          echo "<div><h3>Your basket is empty.</h3></div>";
        }
      } catch (PDOException $ex)
      {
        $this->db->showError($ex, false);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
    private function updateBasket($user_id, $book_id, $new_amount)
    {
      if(ctype_digit($book_id) && ctype_digit($new_amount))
      {
        $results = "";
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL RemoveItemFromBasket(:user_id, :book_id, :new_amount)");
          $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":book_id", $book_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":new_amount", $new_amount, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          
          if($statement->execute())
          {
            return true;
          }
        } catch (PDOException $ex)
        {
          $this->db->showError($ex, false);
        } finally
        {
          $this->db->closeConnection();
        }
      }
    }
    
    private function emptyBasket($user_id)
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL EmptyBasket(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);

        if($statement->execute())
        {
          echo $this->messages->createMessage("Info:", array("Your basket has been emptied."), "info", ["inSessionVar" => false, "dismissable" => false]);
          return true;
        }
        echo $this->messages->createMessage("Error!", array("Unable to empty your basket right now. Please try again later."), "error", ["inSessionVar" => false, "dismissable" => false]);
        return false;
      } catch (PDOException $ex)
      {
        $this->db->showError($ex, false);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
  }
?>