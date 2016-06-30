<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $order = new Order();
  die();

  class Order
  {
    private $messages;
    private $db;
    private $result = "";
    private $order_id;
    
    public function __construct()
    {
      global $messages;
      global $db;
      $this->messages = $messages;
      $this->db = $db;
      
      $user_id = $_SESSION["user_session"]["user_id"];
      
      if(isset($_POST["placeOrder"]))
      {
        if($this->placeOrder($user_id))
        {
          if($this->getOrderDetails($this->order_id))
          {
            $order_date = "";
            $order_total = 0;
            echo "<header class='w3-container-header w3-blue'><h3 id='orderId'>Order #".$this->order_id."</h3></header>";
            echo "<div class='w3-container-central'>";
            echo "<h4>Thank you ".$_SESSION['user_session']['username'].", your order has been placed!</h4>";
            echo "<p class='orderDescription'>Quote your order number to a member of staff when you visit in store to complete your purchase.</p>";
            echo "<table>
                    <tbody>";
            foreach($this->result as $arr)
            {
              echo "<tr>
                      <td>".utf8_encode($arr['title'])."</td>
                      <td>".utf8_encode($arr['quantity'])."</td>
                      <td>£".utf8_encode($arr['cost'])."</td>
                    </tr>";
              $order_date = utf8_encode($arr['date']);
              $order_total += utf8_encode($arr['cost']);
            }
            echo "</tbody></table>";
            echo "<h5>Amount Due: £$order_total</h5>";
            echo "</div>";
            echo "<footer class='w3-container-footer w3-blue'>Ordered On: ".$order_date."</footer>";
          }
        } else
        {
          echo $this->messages->createMessage("Error!", array("We were unable to process the order. Please try again later."), "error", ["inSessionVar" => false, "dismissable" => false]); 
        }
      }
    }
    
    private function placeOrder($user_id)
    {
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT PlaceOrder(:user_id)");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
        if($statement->execute())
        {
          $results = $statement->fetch(PDO::FETCH_ASSOC);
          $this->order_id = $results["PlaceOrder('$user_id')"];
          return true;
        }
      } catch (PDOException $ex)
      {
        $this->db->showError($ex, false);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
    private function getOrderDetails($order_id)
    {
      $order_id = ctype_digit($order_id) ? $order_id : null;
      if($order_id != null)
      {
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("CALL GetOrderById(:order_id)");
          $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          if($statement->execute())
          {
            $this->result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return true;
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
  }
?>
