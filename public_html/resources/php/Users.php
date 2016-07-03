<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $users = new Users();
  die();

  class Users
  {
    private $messages;
    private $db;  
    
    public function __construct()
    {
      global $messages;
      global $db;
      $this->messages = $messages;
      $this->db = $db;
      
      if(isset($_POST["loadUsers"]))
      {
        $this->getAllUsers(); 
      } else if(isset($_GET["User"]))
      {
        $this->getUserDetails($_GET["User"]);
      }
    }
    
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
            
            echo "<header class='w3-container-header w3-blue'><h3 id='username'>".$result[0]['username']."</h3></header>";
            echo "<div class='w3-container-central'>";
            echo "<h5>Account Balance: £".$result[0]['balance']."</h5>";
            echo "<label>Add Balance:</label>";
            echo "<input type='number' min=".$result[0]['balance']." value=".$result[0]['balance']."></input>";
            echo "<input id='increaseBalance' data-user-id=".$result[0]['user_id']." class='button-primary u-pull-right' type='button' value='Increase Balance'>";
            echo "</div>";
            echo "<footer class='w3-container-footer w3-blue'>User Type: ".$result[0]['type']."</footer>";
            
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
    
  }
?>