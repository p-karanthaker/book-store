<?php
  session_start();
  $order = new Order();
  die();

  class Order
  {
    private $db;
    private $result = "";
    
    public function __construct()
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
      $database_helper = require_once($doc_root.$config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$config["paths"]["messages"]);
      $message = new Messages();
      $this->db = new DatabaseHelper($config);
      
      $user_id = $_SESSION["user_session"]["user_id"];
      
      if(isset($_POST["placeOrder"]))
      {
        if($this->placeOrder($user_id))
        {
          if($this->result != null)
          {
            echo "<header class='w3-container-header w3-blue'><h3 id='orderId'>Order #".$this->result."</h3></header>";
            echo "<div class='w3-container-central'>";
            echo "<h4>Details</h4>";
            echo "<p id='orderDescription'>Your order has been placed!</p>";
            echo "</div>";
          }
        } else
        {
          echo $message->createMessage("Error!", array("We were unable to process the order. Please try again later."), "success", ["inSessionVar" => false, "dismissable" => false]); 
        }
      }
      $this->db->closeConnection();
    }
    
    private function placeOrder($user_id)
    {
      if($this->db->openConnection())
      {
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT PlaceOrder(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetch(PDO::FETCH_ASSOC);
          $this->result = $results["PlaceOrder('$user_id')"];
          return true;
        }
      }
      return false;
    }
  }
?>
