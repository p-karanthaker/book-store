<?php
  class AccountRegistration
  { 
    private $config;
    
    public function __construct($config)
    {
      $this->config = $config;
      $database_helper = require_once($this->config["paths"]["db_helper"]);
      
      if(isset($_POST["register"]))
      {
        $this->registerUser();
      }
    }
    
    private function registerUser()
    {
        if($this->validateFormData())
        {
          $db = new DatabaseHelper($this->config);
          
          if($db->openConnection())
          {
            $connection = $db->getConnection();
            
            /* gather details for html escaping*/
            $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
            $user_type = htmlspecialchars($_POST["user_type"], ENT_QUOTES); 
            
            /* hash+salt password */
            $salt = bin2hex(openssl_random_pseudo_bytes(22));
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT, ["salt" => $salt]);
            
            /* check user doesn't exist */
            $statement = $connection->prepare("SELECT username FROM user WHERE username = :username");
            $statement->bindValue(":username", $username);
            $statement->execute();
            
            if($statement->rowCount() == 0)
            {
              $statement = $connection->prepare("INSERT INTO user (username, password_hash, password_salt, type)
                                                 VALUES(:username, :password_hash, :password_salt, :type)");
              $statement->bindParam(":username", $username);
              $statement->bindParam(":password_hash", $password);
              $statement->bindParam(":password_salt", $salt);
              $statement->bindParam(":type", $user_type);
              
              if($statement->execute())
              {
                echo "<div class='container'>
                        <div class='alert alert-success'>
                          <a class='close' data-dismiss='alert'>&times;</a>
                          <strong>Success!</strong> Your account has been created.
                        </div>
                      </div>";
                $db->closeConnection();
                return true;
              }
              return false;
            }
            echo "<div class='container'>
                      <div class='alert alert-error'>
                        <a class='close' data-dismiss='alert'>&times;</a>
                        <strong>Error!</strong> User already exists, please try again.
                      </div>
                    </div>";
            $db->closeConnection();
            return false;
          } else
          {
            return false;
          }
        } else 
        {
          echo "<div class='container'>
                  <div class='alert alert-error alert-block'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h5><strong>Invalid Input!</strong></h5> 
                    <ul>
                      <li><strong>Username</strong> must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>.</li>
                      <li><strong>Passwords</strong> must match, and must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>.</li>
                    </ul>
                  </div>
                </div>";
          return false;
        }
    } 
    
    /**
     * Validates the user input on the registration form
     * @return bool Returns whether the form is valid or not
     */    
    private function validateFormData()
    {
      define("MIN_LENGTH", 6);
      define("MAX_LENGTH", 12);
      define("REGEX_MATCHER", '/^[a-z0-9]{6,12}$/i');
      
      if(!empty($_POST["username"])
        && !empty($_POST["password"])
        && !empty($_POST["password_confirm"])
        && !empty($_POST["user_type"])
        && strlen($_POST["username"]) <= MAX_LENGTH
        && strlen($_POST["username"]) >= MIN_LENGTH
        && strlen($_POST["password"]) <= MAX_LENGTH
        && strlen($_POST["password"]) >= MIN_LENGTH
        && preg_match(REGEX_MATCHER, $_POST["username"])
        && preg_match(REGEX_MATCHER, $_POST["password"])
        && ($_POST["password"] === $_POST["password_confirm"])
        && ($_POST["user_type"] === "Student") || ($_POST["user_type"] === "Staff"))
      {
        return true;
      } else {
        return false;
      }
    }
  }
?>
