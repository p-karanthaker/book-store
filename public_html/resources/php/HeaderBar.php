<?php 
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $header = new Header();
  $header->makeHeader();

  class Header
  {
    private $db;  
    
    public function __construct()
    {
      global $db;
      $this->db = $db;
    }
    
    public function makeHeader()
    {
      try
        {
          $user_id = $_SESSION["user_session"]["user_id"];
          $this->db->openConnection();
          $connection = $this->db->getConnection();
          $statement = $connection->prepare("SELECT user_id, username, `type`, balance FROM `user` WHERE user_id = :user_id");
          $statement->bindParam(":user_id", $user_id);
          if($statement->execute())
          {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            echo "<div class='row'>
                    <div class='twelve columns infobar bg-color-skeleton-blue'>
                      <div class='u-pull-left'>
                        <i class='fa fa-user fa-lg' aria-hidden='true' style='color:#000;'></i> ".$result[0]["username"]."
                      </div>
                      <div class='u-pull-right'>
                        <i class='fa fa-gbp fa-lg' aria-hidden='true' style='color:#000;'></i>".$result[0]["balance"]."
                      </div>
                    </div>
                  </div>";
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
?>