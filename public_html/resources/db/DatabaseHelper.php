<?php
  class DatabaseHelper
  { 
    private $connection;
    private $config;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    
    public function __construct($config)
    {
      $this->config = $config;
      $this->db_host = $this->config["database_dev"]["db_host"];
      $this->db_name = $this->config["database_dev"]["db_name"];
      $this->db_user = $this->config["database_dev"]["db_user"];
      $this->db_pass = $this->config["database_dev"]["db_pass"];
    }
    
    public function openConnection()
    {
      $config = $this->config;
      try
      {
        $this->connection = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
      } catch (PDOException $ex)
      {
        $this->echoError($ex);
      }
    }
    
    public function closeConnection()
    {
      $this->connection = null;
    }
    
    public function getConnection()
    {
      return $this->connection;
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