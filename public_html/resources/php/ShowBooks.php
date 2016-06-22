<?php
  $books = new ShowBooks();
  
  class ShowBooks 
  {
    private $db;
    
    public function __construct()
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
      $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);
      $this->db = new DatabaseHelper($config);
      
      $category = "";
      if(isset($_GET['Category']))
      {
        $this->getBooksByCategory($_GET['Category']);
      } else if(isset($_GET['Book']))
      {
        $this->getBookDetails();
      }
      $this->db->closeConnection();
    }
    
    private function getBooksByCategory($category)
    {
      $category = $_GET['Category'];
      $category = $category == "All" ? "" : $_GET['Category'];

      $results = "";
      if($this->db->openConnection())
      {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBooksByCategory(?)");
        $statement->bindParam(1, $category, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000);
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach($results as $arr)
        {
          echo "<tr id='books'>";
          echo "<td bookId=".$arr['book_id'].">".utf8_encode($arr['title'])."</td>";        
          echo "<td>".utf8_encode($arr['authors'])."</td>";
          echo "<td>".utf8_encode($arr['category'])."</td>";
          echo "<td>".utf8_encode($arr['quantity'])."</td>";
          echo "<td>".utf8_encode($arr['price'])."</td>";
          echo "</tr>";
        }
      }
    }
    
    private function getBookDetails()
    {
      $bookId = $_GET['Book'];
      $bookId = $bookId == "" ? "1" : $_GET['Book'];

      $results = "";
      if($this->db->openConnection())
      {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("CALL GetBookById(?)");
        $statement->bindParam(1, $bookId, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000);
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
        echo "</div>";
        echo "<footer class='w3-container-footer'>";
        echo "<form method='post' action=''>";
        echo "<input id='addToBasket' class='button-primary' type='button' value='Add To Basket'>";
        echo "<input id='addQuantity' type='number' value='1' min='1' max=".utf8_encode($results['quantity']).">";
        echo "</form>";
        echo "</footer>";
      }
    }
    
  }


?>