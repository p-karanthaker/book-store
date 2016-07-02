<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $books = new ShowBooks();
  
  class ShowBooks 
  {
    private $db;
    
    public function __construct()
    {
      global $db;
      $this->db = $db;
      
      $category = "";
      if(isset($_GET["Category"]))
      {
        $this->getBooksByCategory($_GET["Category"]);
      } else if(isset($_GET["Book"]))
      {
        $this->getBookDetails();
      } else if(isset($_POST["loadCategories"]))
      {
        $this->getCategories();
      }
    }
    
    private function getCategories()
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT cat_id, `name` FROM category");
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach($results as $arr)
        {
          echo "<option value=".$arr['cat_id'].">".utf8_encode($arr['name'])."</option>";
        }
      } catch (PDOException $ex)
      {
        $this->db->showError($ex, false);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
    private function getBooksByCategory($category)
    {
      $category = $_GET["Category"];
      $category = $category == "All" ? "" : $category;

      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBooksByCategory(:book_category)");
        $statement->bindParam(":book_category", $category, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach($results as $arr)
        {
          echo "<tr id='books'>";
          echo "<td data-book-id=".$arr['book_id'].">".utf8_encode($arr['title'])."</td>";        
          echo "<td>".utf8_encode($arr['authors'])."</td>";
          echo "<td>".utf8_encode($arr['category'])."</td>";
          echo "<td>".utf8_encode($arr['quantity'])."</td>";
          echo "<td>£".utf8_encode($arr['price'])."</td>";
          echo "</tr>";
        }
      } catch (PDOException $ex)
      {
        $this->db->showError($ex, false);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
    private function getBookDetails()
    {
      $bookId = $_GET["Book"];
      $bookId = ctype_digit($bookId) ? $bookId : 1;

      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBookById(:book_id)");
        $statement->bindParam(":book_id", $bookId, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetch(PDO::FETCH_ASSOC);
        }
        
        echo "<header class='w3-container-header w3-blue'><h3 id='bookTitle'>".utf8_encode($results['title'])."</h3></header>";
        echo "<div class='w3-container-central'>";
        echo "<h4>Description</h4>";
        echo "<p id='bookDescription'>".utf8_encode($results['description'])."</p>";
        echo "<label class='u-pull-left'>Quantity: </label><p id='bookQuantity'>".utf8_encode($results['quantity'])."</p>";
        echo "<label class='u-pull-left'>Price: £</label><p id='bookPrice'>".utf8_encode($results['price'])."</p>";
        echo "<input id='addToBasket' data-book-id=".$results['book_id']." class='button-primary' type='button' value='Add To Basket'>";
        echo "</div>";
        echo "<footer class='w3-container-footer w3-blue'>Categories: ".$results['category']."</footer>";
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
