<?php
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");

  class DatabaseHelper
  { 
    private $connection;
    
    private $config;
    private $messages;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    
    public function __construct()
    {
      global $config;
      global $messages;
      $this->config = $config;
      $this->messages = $messages;
      $this->db_host = $config["database_dev"]["db_host"];
      $this->db_name = $config["database_dev"]["db_name"];
      $this->db_user = $config["database_dev"]["db_user"];
      $this->db_pass = $config["database_dev"]["db_pass"];
    }
    
    public function openConnection()
    {
      $this->connection = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function closeConnection()
    {
      $this->connection = null;
    }
    
    public function getConnection()
    {
      return $this->connection;
    }
    
    public function showError(PDOException $ex, $inSessionVar = true)
    {
      $msg_details = array("<p>Please try again later.</br></p><strong>Error Details:</strong> ".$ex->getMessage());
      if($inSessionVar == false)
      {
        echo $this->messages->createMessage("Database Operation Failed", $msg_details, "error", ["isBlock" => true, "inSessionVar" => false]);
      } else
      {
        $this->messages->createMessage("Database Operation Failed", $msg_details, "error", ["isBlock" => true]);
      }
    }
    
  }
?>