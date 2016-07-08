<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $basket = new Basket();
  die();

  class Basket
  {
    /**
     * The Messages object.
     */
    private $messages;
    
    /**
     * The DatabaseHelper object.
     */
    private $db;
    
    /**
     * Constructs Basket by initialising Messages and DatabaseHelper objects.
     * Checks POST variables to perform various Basket actions.
     */
    public function __construct()
    {
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      
      $category = "";
      $user_id = $_SESSION["user_session"]["user_id"];
      
      if(isset($_POST["bookId"]))  // Add item to basket.
      {
        $this->addToBasket($user_id, $_POST["bookId"]);
      } else if(isset($_POST["showBasket"]))  // Show the user basket.
      {
        $this->showBasket($user_id);
      } else if(isset($_POST["updateBasket"]))  // Try to update the user basket.
      {
        if(!empty($_POST["updateBasket"]))  // Update the user basket if POST variable is not empty
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
      } else if(isset($_POST["emptyBasket"]))   // Empty the user's basket.
      {
        $this->emptyBasket($user_id);
      } else if(isset($_POST["removeItem"]))    // Remove an item from the user's basket.
      {
        if($this->updateBasket($user_id, $_POST["removeItem"], "0"))
        {
          echo $this->messages->createMessage("Info:", array("Item was removed from your basket."), "info", ["inSessionVar" => false, "dismissable" => false]);
        }
      }
    }
    
    /**
     * Add an item to the user's basket.
     *
     * @param Integer   $user_id  The user's user_id.
     * @param Integer   $book_id  The id of the book.
     */
    private function addToBasket($user_id, $book_id)
    {
      if(ctype_digit($book_id))
      {
        try
        {
          $results = "";
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT AddItemToBasket(:user_id, :book_id, 1)"); // See /resources/sql/create-procedures.sql for SQL code.
          $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          $statement->bindParam(":book_id", $book_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          if($statement->execute())
          {
            $results = $statement->fetch(PDO::FETCH_ASSOC);
            $success = $results["AddItemToBasket('$user_id', '$book_id', 1)"];
            if($success)
            {
              echo $this->messages->createMessage("Added", array($_POST['bookTitle']." to your basket."), "success", ["inSessionVar" => false, "dismissable" => false]);
            } else
            {
              echo $this->messages->createMessage("Out of Stock!", array("This item is no longer in stock."), "error", ["inSessionVar" => false, "dismissable" => false]);
            }
          } else
          {
            echo $this->messages->createMessage("Error", array("Unable to add item to your basket right now. Please try again later."), "error", ["inSessionVar" => false, "dismissable" => false]);
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
    
    /**
     * Get details of a user's basket.
     *
     * @param Integer $user_id  The id of the user.
     */
    private function showBasket($user_id)
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBasketByUserId(:user_id)");  // See /resources/sql/create-procedures.sql for SQL code.
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
            echo "<td><input id='quantity' type='number' value='".utf8_encode($arr['quantity'])."' min='1' max='100'/></td>";
            echo "<td id='bookPrice'>£".utf8_encode($arr['cost'])."</td>";
            echo "<td><button class='removeItem u-pull-right' type='submit'><i class='fa fa-trash fa-lg' aria-hidden='true'></i></button></td>";
            echo "</tr>";
            $totalPrice += utf8_encode($arr['subtotal']);
          }
          echo "<tr id='totalPrice'>";
          echo "<td></td>";
          echo "<td><strong>Total<strong></td>";
          echo "<td><b>£".$totalPrice."</b></td>";
          echo "<td></td>";
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
    
    /**
     * Updates the user's basket with a book id and a new amount of books.
     *
     * @param Integer $user_id  The id of the user.
     * @param Integer $book_id  The id of the book being updated.
     * @param Integer $new_amount The amount to change the current basket amount to.
     */
    private function updateBasket($user_id, $book_id, $new_amount)
    {
      if(ctype_digit($book_id) && ctype_digit($new_amount))
      {
        $results = "";
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL UpdateBasket(:user_id, :book_id, :new_amount)");
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
    
    /**
     * Empty the user's basket.
     */
    private function emptyBasket($user_id)
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL EmptyBasket(:user_id)");  // See /resources/sql/create-procedures.sql for SQL code.
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);

        if($statement->execute())
        { 
          // Restock the items after being removed from the basket.
          $itemsToRestock = $statement->fetchAll();
          foreach($itemsToRestock as $item)
          {
            $statement = $connection->prepare("UPDATE books AS b SET b.quantity = b.quantity + :quantity WHERE b.book_id = :book_id");
            $statement->bindParam(":quantity", $item['quantity']);
            $statement->bindParam(":book_id", $item['book_id']);
            $statement->execute();
          }
          echo $this->messages->createMessage("Info:", array("Your basket has been emptied."), "info", ["inSessionVar" => false, "dismissable" => false]);
        }
        else
        {
          echo $this->messages->createMessage("Error!", array("Unable to empty your basket right now. Please try again later."), "error", ["inSessionVar" => false, "dismissable" => false]);
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
?>