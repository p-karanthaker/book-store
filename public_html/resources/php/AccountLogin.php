<?php
  session_start();
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."/resources/configs/config.ini", true);
  $login = new AccountLogin($config);

  $result = $login->getResult();

  if($result)
  {
    header("Location: ".$config["paths"]["host"].$config["paths"]["index"], true, 303);
  } else
  {
    header("Location: ".$config["paths"]["host"].$config["paths"]["login"], true, 303);
  }
  die();

  class AccountLogin
  {
    private $config;
    private $message;
    private $result;
    
    public function __construct($config)
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $this->config = $config;
      
      $database_helper = require_once($doc_root.$this->config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$this->config["paths"]["messages"]);
      $this->message = new Messages();
      
      if(isset($_POST["login"]))
      {
         $this->doLogin();
      } else if(isset($_POST["logout"]))
      {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), "", time() - 42000, $params["user_session"], $params["message"]);
        }
        $this->message->createMessage("Goodbye!", array("You have been signed out. Please visit again soon!"), "info");
        $this->result = true;
      } else {
        $this->result = false;
      }
    }
    
    private function doLogin()
    {
      if($this->validateFormData())
      {
        $username = $_POST["username"];
        $password = $_POST["password"];
        if($this->authenticate($username, $password))
        {
          $this->message->createMessage("Welcome back", array("<strong>$username</strong>!"), "info");
          $this->result = true;
          return true;
        } else
        {
          // Authentication failed...drop down into default return.
        }
      }
      // Invalid form data / authentication failed / db connection failed
      $msg_details = array("Username or Password is invalid.");
      $this->message->createMessage("Login Failed!", $msg_details, "error");
      return false;
    }

    private function authenticate($username, $password)
    {
      $db = new DatabaseHelper($this->config);
      try
      {
        $db->openConnection();
        $connection = $db->getConnection();
        $statement = $connection->prepare("SELECT user_id, password_hash, type FROM user WHERE username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();
        
        // Check if account was found
        if($statement->rowCount() == 1)
        {
          $results = $statement->fetch(PDO::FETCH_ASSOC);
        
          // Fetch database results
          $db_user_id = $results["user_id"];
          $db_password_hash = $results["password_hash"];
          $db_user_type = $results["type"];

          if(password_verify($password, $db_password_hash))
          {
            // Authentication success
            $user_session = array("user_id"=>$db_user_id, "username"=>$username, "user_type"=>$db_user_type);
            $_SESSION["user_session"] = $user_session;
            $db->closeConnection();
            return true;
          }
        }
        return false;
      } catch (PDOException $ex)
      {
        return false;
      } finally
      {
        $db->closeConnection();
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
      define("REGEX_MATCHER", "/^[a-z0-9]{6,12}$/i");
      
      if(!empty($_POST["username"])
        && !empty($_POST["password"])
        && strlen($_POST["username"]) <= MAX_LENGTH
        && strlen($_POST["username"]) >= MIN_LENGTH
        && strlen($_POST["password"]) <= MAX_LENGTH
        && strlen($_POST["password"]) >= MIN_LENGTH
        && preg_match(REGEX_MATCHER, $_POST["username"])
        && preg_match(REGEX_MATCHER, $_POST["password"]))
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