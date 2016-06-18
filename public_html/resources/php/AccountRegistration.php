<?php
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  $register = new AccountRegistration($config);

  $result = $register->getResult();

  if($result)
  {
    header("Location: http://localhost/".$config["paths"]["index"], true, 303);
  } else
  {
    header("Location: http://localhost/".$config["paths"]["register"], true, 303);
  }

  die();

  class AccountRegistration
  { 
    private $config;
    private $message;
    private $result;
    
    public function __construct($config)
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $this->config = $config;
      $this->result = false;
      $database_helper = require_once($doc_root.$this->config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$this->config["paths"]["messages"]);
      $this->message = new Messages();
      
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
                // Account created
                $this->message->createMessage("Success!", array("Your account has been created."), "success");
                $db->closeConnection();
                $this->result = true;
                return true;
              }
              return false;
            }
            // Account already exists
            $this->message->createMessage("Error!", array("User already exists, please try again."), "error");
            $db->closeConnection();
            return false;
          } else
          {
            return false;
          }
        } else 
        {
          // Invalid form data
          $msg_details = array
            ("<strong>Username</strong> must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>."
            ,"<strong>Passwords</strong> must match, and must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>."
            );
          $this->message->createMessage("Invalid Fields!", $msg_details, "warning", true);
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
    
    public function getResult()
    {
      return $this->result;
    }
  }
?>
