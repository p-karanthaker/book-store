<?php
  $category = "";
  if(isset($_GET['Category']))
  {
    $category = $_GET['Category'];
    $category = $category == "All" ? "" : $_GET['Category'];

    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
    $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);

    $db = new DatabaseHelper($config);
    $results = "";
    if($db->openConnection())
    {
      $connection = $db->getConnection();
      $statement = $connection->prepare("CALL GetBooks(?)");
      $statement->bindParam(1, $category, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000);
      if($statement->execute())
      {
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
      }
      
      foreach($results as $arr)
      {
        echo "<tr>";
        foreach($arr as $value)
        {
          echo "<td>".utf8_encode($value)."</td>";
        }
        echo "</tr>";
      }
      $db->closeConnection();
    }
  }
?>