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
        $statement = $connection->prepare("CALL GetBooks(?)");
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
      }
    }
    
  }


?>