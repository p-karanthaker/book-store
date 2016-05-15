<?php
  class DatabaseHelper
  { 
    private $connection;
    private $config;
    
    public function __construct()
    {
      $this->config = require_once("E:/Servers/wamp64/www/book-store/resources/configs/config.php");
    }
    
    public function openConnection()
    {
      $config = $this->config;
      try
      {
        $this->connection = new PDO("mysql:host=".$config[DB_HOST].";dbname=".$config[DB_NAME], $config[DB_USER], $config[DB_PASS]);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $ex)
      {
        $this->echoError($ex);
      }
    }
    
    public function closeConnection()
    {
      $this->connection = null;
    }
    
    private function echoError(PDOException $ex)
    {
      echo "<div class='container'>
              <div class='alert alert-error alert-block'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4><strong>Error!</strong></h4>
                <p>Database operation failed. Please try again later.</br></p>"
                ."<strong>Error Details:</strong> ".$ex->getMessage()."
              </div>
            </div>";
    }
    
  }
?>