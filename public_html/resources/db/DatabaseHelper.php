<?php
  class DatabaseHelper
  { 
    private $connection;
    private $config;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    
    private $messages;
    
    public function __construct($config)
    {
      $this->config = $config;
      $this->db_host = $this->config["database_dev"]["db_host"];
      $this->db_name = $this->config["database_dev"]["db_name"];
      $this->db_user = $this->config["database_dev"]["db_user"];
      $this->db_pass = $this->config["database_dev"]["db_pass"];
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $messages = require_once($doc_root.$this->config["paths"]["messages"]);
      $this->message = new Messages();
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
      $msg_details = array("<p>Please try again later.</br></p><strong>Error Details:</strong> ".$ex->getMessage());
      $this->message->createMessage("Database Operation Failed", $msg_details, "error", true);
    }
    
  }
?>