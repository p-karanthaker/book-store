<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $order = new Order();
  die();

  /**
   * The Order class performs various actions for processing and retrieving
   * BookStore user orders.
   */
  class Order
  {
    /**
     * The Messages object.
     */
    private $messages;
    
    /**
     * The DatabaseHelper object.
     */
    private $db;
    
    /**
     * Stores the result of functions.
     */
    private $result = "";
    
    /**
     * Stores the order id of a new order.
     */
    private $order_id;
    
    /**
     * Constructs the Order object by initialising Messages and DatabaseHelper objects
     * and then decides on the action to perform based on POST/GET variables.
     */
    public function __construct()
    {
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      
      $user_id = $_SESSION["user_session"]["user_id"];
      
      if(isset($_POST["placeOrder"]))   
      {
        if($this->placeOrder($user_id))   // Place an order from basket checkout.
        {
          if($this->getOrderDetails($this->order_id))  // Get the order details to display back to the user.
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
                      <td>".utf8_encode($arr['quantity'])." &times;</td>
                      <td>£".utf8_encode($arr['cost'])."</td>
                    </tr>";
              $order_date = utf8_encode($arr['date']);
              $order_total += utf8_encode($arr['subtotal']);
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
      } else if(isset($_POST["loadOrders"]))  // Gets all user orders for staff members to view.
      {
        $this->getAllOrders();
      } else if(isset($_GET["Order"]))    // Get the order details of a single order.
      {
        if($this->getOrderDetails($_GET["Order"]))
        {
          $order_date = "";
          $order_total = 0;
          echo "<header class='w3-container-header w3-blue'><h3 id='orderId'>Order #".$_GET["Order"]."</h3></header>";
          echo "<div class='w3-container-central'>";
          echo "<table>
                  <tbody>";
          foreach($this->result as $arr)
          {
            echo "<tr>
                    <td>".utf8_encode($arr['title'])."</td>
                    <td>".utf8_encode($arr['quantity'])." &times;</td>
                    <td>£".utf8_encode($arr['cost'])."</td>
                  </tr>";
            $order_date = utf8_encode($arr['date']);
            $order_total += utf8_encode($arr['subtotal']);
          }
          echo "</tbody></table>";
          echo "<h5>Amount Due: £$order_total</h5>";
          echo "<input id='completeOrder' data-order-id=".$_GET["Order"]." class='button-primary' type='button' value='Complete Order'>";
          echo "</div>";
          echo "<footer class='w3-container-footer w3-blue'>Ordered On: ".$order_date."</footer>";
        }
      } else if(isset($_GET["CompleteOrder"]))  // Complete a user's order.
      {
        $this->completeOrder($_GET["CompleteOrder"]);
      }
    }
    
    /**
     * Places an order for a student by calling the PlaceOrder stored function.
     * See the SQL code in /resources/db/create-procedures.sql
     */
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
    
    /**
     * Completes a user order by calling the CompleteOrder stored function.
     * See /resources/db/create-procedures.sql for SQL code.
     */
    private function completeOrder($order_id)
    {
      $order_id = ctype_digit($order_id) ? $order_id : null;
      if($order_id != null)
      {
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT CompleteOrder(:order_id)");
          $statement->bindParam(":order_id", $order_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
          if($statement->execute())
          {
            $results = $statement->fetch(PDO::FETCH_ASSOC);
            $success = $results["CompleteOrder('$order_id')"];
            if($success)
            {
              echo $this->messages->createMessage("Success!", array("Order #$order_id is complete. Payment has been taken from the user."), "success", ["inSessionVar" => false, "dismissable" => false]);
            } else
            {
              echo $this->messages->createMessage("Failed!", array("Unable to complete Order #$order_id. User does not have enough balance, or order is already complete."), "error", ["inSessionVar" => false, "dismissable" => false]);
            }
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
    
    /**
     * Get the details of a single order.
     */
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
    
    /**
     * Get all user orders which have not yet been completed.
     */
    private function getAllOrders()
    {
      try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT o.order_id, u.username, o.`date`
                                             FROM orders o
                                             INNER JOIN `user` u
                                             ON u.user_id = o.user_id
                                             WHERE o.active = true"
                                           );
          if($statement->execute())
          {
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($results as $arr)
            {
              echo "<tr class='clickableRow orders'>";
              echo "<td data-order-id=".utf8_encode($arr['order_id']).">".utf8_encode($arr['order_id'])."</td>";        
              echo "<td>".utf8_encode($arr['username'])."</td>";
              echo "<td>".utf8_encode($arr['date'])."</td>";
              echo "</tr>";
            }
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
?>
