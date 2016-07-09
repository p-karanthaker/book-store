<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $books = new Books();
  die();

  /**
   * The Books class performs various actions to get
   * details about books and book categories. Also adds
   * books into the store.
   */
  class Books 
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
     * Constructs Books by initialising Messages and DatabaseHelper.
     * Then checks POST/GET variable to decide which action to perform.
     */
    public function __construct()
    {
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      
      $category = "";
      if(isset($_GET["Category"]))
      {
        $this->getBooksByCategory($_GET["Category"]);
      } else if(isset($_GET["Book"]))
      {
        $this->getBookDetails($_GET["Book"]);
      } else if(isset($_POST["loadCategories"]))
      {
        $this->getCategories();
      } else if(isset($_POST["book"]))
      {
        $this->addNewBook($_POST["book"]);
      }
    }
    
    /**
     * Gets the book categories from the database and echo's as
     * HTML select tag options.
     */
    private function getCategories()
    {
      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT cat_id, `name` FROM category ORDER BY `name` ASC");
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
    
    /**
     * Get's books beloging to specific categories
     * and outputs as HTML table rows.
     */
    private function getBooksByCategory($category)
    {
      $category = $_GET["Category"];
      $category = $category == "All" ? "" : $category;

      $results = "";
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBooksByCategory(:book_category)");   // See /resources/sql/create-procedures.sql for SQL code.
        $statement->bindParam(":book_category", $category, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach($results as $arr)
        {
          echo "<tr class='clickableRow'>";
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
    
    /**
     * Gets the details of a specific book id and returns HTML
     * to be added into a <div> using the w3-card css class.
     */
    private function getBookDetails($bookId)
    {
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
    
    /**
     * Adds a new book to the BookStore database.
     */
    private function addNewBook($new_book)
    {
      // Get all the details of the book and store in variables after escaping html characters.
      $book = $new_book[0];
      $title = array_key_exists("title", $book) ? htmlspecialchars($book["title"], ENT_QUOTES) : "";
      $authors = array_key_exists("authors", $book) ? htmlspecialchars($book["authors"], ENT_QUOTES) : "";
      $description = array_key_exists("description", $book) ? htmlspecialchars($book["description"], ENT_QUOTES) : "";
      $categories = array_key_exists("categories", $book) ? htmlspecialchars($book["categories"], ENT_QUOTES) : "";
      $quantity = array_key_exists("quantity", $book) ? htmlspecialchars($book["quantity"], ENT_QUOTES) : "";
      $price = array_key_exists("price", $book) ? htmlspecialchars($book["price"], ENT_QUOTES) : "";
      if($this->validateBookDetails($book))
      {
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("INSERT INTO books (title, `authors`, quantity, price, description) 
                                             VALUES (:title, :authors, :quantity, :price, :description)");
          $statement->bindParam(":title", $title);
          $statement->bindParam(":authors", $authors);
          $statement->bindParam(":quantity", $quantity);
          $statement->bindParam(":price", $price);
          $statement->bindParam(":description", $description);
          if($statement->execute())
          {
            $bookId = $connection->lastInsertId();
            
            $cat_arr = explode(", ", $categories);
            $addedToCategories = false;
            foreach($cat_arr as $category)
            {
              $statement = $connection->prepare("INSERT INTO bookcategory (book_id, cat_id)
                                               SELECT	:book_id,
                                                      cat_id
                                               FROM category
                                               WHERE `name` LIKE :category");
              $statement->bindParam(":book_id", $bookId);
              $statement->bindParam(":category", $category);
              if($statement->execute())
              {
                $addedToCategories = true;
              }
            }
            if($addedToCategories)
            {
              $msg_details = array("$title to the book stock.");
              echo $this->messages->createMessage("Added", $msg_details, "success", ["inSessionVar" => false, "dismissable" => false]);
            }
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
     * Validate the new book details by matching user input against
     * regex patterns.
     */
    private function validateBookDetails($book)
    {
      $title = array_key_exists("title", $book) ? $book["title"] : "";
      $authors = array_key_exists("authors", $book) ? $book["authors"] : "";
      $description = array_key_exists("description", $book) ? $book["description"] : "";
      $categories = array_key_exists("categories", $book) ? $book["categories"] : "";
      $quantity = array_key_exists("quantity", $book) ? $book["quantity"] : "";
      $price = array_key_exists("price", $book) ? $book["price"] : "";

      $stringmatcher="a-z0-9\s,.+*?$!:\-'\"\&";
      define("REGEX_MATCHER_ONE", "/^[$stringmatcher]{1,100}$/i");
      define("REGEX_MATCHER_FIF", "/^[$stringmatcher]{1,50}$/i");
      define("REGEX_MATCHER_NUM", "/^[\d]{1,11}$/");
      define("REGEX_MATCHER_DEC", "/^\d{1,13}\.?(?:\d{0}|\d{2})$/");
      define("REGEX_MATCHER_DESCRIPTION", "/^[$stringmatcher]{1,5000}$/i");
      
      $cat_arr = explode(", ", $categories);
      
      $msg_details = array();
      if(!preg_match(REGEX_MATCHER_ONE, $title))
      {
        $msg_details = array("Book Title must be between 1 and 100 characters.");
        echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
        return false;
      } else if(!preg_match(REGEX_MATCHER_ONE, $authors))
      {
        $msg_details = array("Author list must be between 1 and 100 characters.");
        echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
        return false;
      } else if(!preg_match(REGEX_MATCHER_DESCRIPTION, $description))
      {
        $msg_details = array("Description must be between 1 and 5000 characters.");
        echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
        return false;
      } else if(!preg_match(REGEX_MATCHER_NUM, $quantity))
      {
        $msg_details = array("Quantity must be an integer.");
        echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
        return false;
      } else if(!preg_match(REGEX_MATCHER_DEC, $price))
      {
        $msg_details = array("Price must be a value of either 0 decimal places or 2 decimal places.");
        echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
        return false;
      }
      
      foreach($cat_arr as $category)
      {
        if(!preg_match(REGEX_MATCHER_FIF, $category))
        {
          $msg_details = array("Category must be between 1 and 50 characters.");
          echo $this->messages->createMessage("Warning!", $msg_details, "warning", ["inSessionVar" => false]);
          return false; 
        }
      }
      
      return true;
    }
    
  }
?>
