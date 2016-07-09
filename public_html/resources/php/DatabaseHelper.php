<?php
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");

  /**
   * The DatabaseHelper class provides a convenient location from
   * which to create, open, and close new database connections.
   */
  class DatabaseHelper
  { 
    /**
     * The PDO connection object.
     */
    private $connection;
    
    /**
     * The configuration associative array.
     */
    private $config;
    
    /**
     * The Messages object.
     */
    private $messages;
    
    /**
     * The database hostname.
     */
    private $db_host;
    
    /**
     * The database name.
     */
    private $db_name;
    
    /**
     * The user connecting to the database.
     */
    private $db_user;
    
    /**
     * The password for the user connecting to the database.
     */
    private $db_pass;
    
    /**
     * Constructs the DatabaseHelper by initialising the fields.
     */
    public function __construct()
    {
      global $config;
      global $messages;
      $this->config = $config;
      $this->messages = $messages;
      $this->db_host = $config["database"]["db_host"];
      $this->db_name = $config["database"]["db_name"];
      $this->db_user = $config["database"]["db_user"];
      $this->db_pass = $config["database"]["db_pass"];
    }
    
    /**
     * Opens a new database connection using PDO.
     */
    public function openConnection()
    {
      $this->connection = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Closes the PDO database connection.
     */
    public function closeConnection()
    {
      $this->connection = null;
    }
    
    /**
     * Gets the PDO database connection.
     *
     * @return PDO
     */
    public function getConnection()
    {
      return $this->connection;
    }
    
    /**
     * Prints PDOException messages.
     *
     * @param PDOException  $ex the PDOException message to print.
     * @param Boolean       $inSessionVar decide if the message will be added to the session
     *                                    and printed on a page after a header redirect (true), or
     *                                    to print it immediately (false). Default is true.
     */
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