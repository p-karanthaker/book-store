<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $users = new Users();
  die();

  /**
   * The Users class provides various actions to perform on
   * BookStore users.
   */
  class Users
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
     * Constructs the Users object by initialising Messages and DatabaseHelper objects.
     * Then looks at POST/GET variables to decide on what action to take.
     */
    public function __construct()
    {
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      
      if(isset($_POST["loadUsers"]))  // Get details of all users.
      {
        $this->getAllUsers(); 
      } else if(isset($_GET["User"])) // Get details of a single user.
      {
        $this->getUserDetails($_GET["User"]);
      } else if(isset($_POST["addBalance"]))  // Add funds to a specific user account.
      {
        $this->addBalance($_POST["userId"], $_POST["addBalance"]);
      }
    }
    
    /**
     * Get the details of a single user.
     *
     * @param Integer $user_id  The user_id of the user to retrieve the details from.
     */
    private function getUserDetails($user_id)
    {
      $user_id = ctype_digit($user_id) ? $user_id : null;
      if($user_id != null)
      {
        try
        {
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT user_id, username, `type`, balance FROM `user` WHERE user_id = :user_id");
          $statement->bindParam(":user_id", $user_id);
          if($statement->execute())
          {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<header class='w3-container-header w3-blue'><h3 id='username'>".$result[0]['username']."</h3></header>
                    <div class='w3-container-central'>
                      <h5>Account Balance: £".$result[0]['balance']."</h5>
                      <label>Add Balance:</label>
                      <div class='row'>
                        <div class='one-half column'>
                          <input id='addBalance' type='number' min='0' value='0' class='u-full-width'></input>
                        </div>
                        <div class='one-half column'>
                          <input id='increaseBalance' data-user-id=".$result[0]['user_id']." class='button-primary u-full-width' type='button' value='Increase Balance'>
                        </div>
                      </div>
                    </div>
                  <footer class='w3-container-footer w3-blue'>User Type: ".$result[0]['type']."</footer>";
            
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
     * Get all users of the BookStore.
     */
    private function getAllUsers()
    {
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
        $statement = $connection->prepare("SELECT user_id, username, `type`, balance FROM `user`");
        if($statement->execute())
        {
          $results = $statement->fetchAll(PDO::FETCH_ASSOC);

          foreach($results as $arr)
          {
            echo "<tr class='clickableRow users'>";
            echo "<td data-user-id=".utf8_encode($arr['user_id']).">".utf8_encode($arr['username'])."</td>";        
            echo "<td>".utf8_encode($arr['type'])."</td>";
            echo "<td>".utf8_encode($arr['balance'])."</td>";
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
    
    /**
     * Add funds to a user account.
     *
     * @param Integer $user_id  The id of the user account to add funds to.
     * @param Integer $amount   The amount of funds to add to the user account.
     */
    private function addBalance($user_id, $amount)
    {
      if(ctype_digit($user_id) && is_double(floatval($amount)))
      {
        try
        {
          if($amount > 0)
          {
            $this->db->openConnection();
            $connection = $this->db->getConnection();
            $statement = $connection->prepare("UPDATE `user` SET balance=balance+:amount WHERE user_id=:user_id");
            $statement->bindParam(":amount", $amount);
            $statement->bindParam(":user_id", $user_id);
            if($statement->execute())
            {
              echo $this->messages->createMessage("Info:", array("Added £$amount to user $user_id's account."), "info", ["inSessionVar" => false, "dismissable" => false]);
              return true;
            }
          } else 
          {
            echo $this->messages->createMessage("Warning:", array("Value must be greater than £0."), "warning", ["inSessionVar" => false, "dismissable" => false]);
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