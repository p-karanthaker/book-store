<?php
  $doc_root = $_SERVER["DOCUMENT_ROOT"];
  $config = parse_ini_file($doc_root."book-store/public_html/resources/configs/config.ini", true);
  $start = new AccountLogin($config);
  
  header("Location: http://localhost/".$config["paths"]["login"], true, 303);
  die();

  class AccountLogin
  {
    private $config;
    private $message;
    
    public function __construct($config)
    {
      $doc_root = $_SERVER["DOCUMENT_ROOT"];
      $this->config = $config;
      $database_helper = require_once($doc_root.$this->config["paths"]["db_helper"]);
      $messages = require_once($doc_root.$this->config["paths"]["messages"]);
      $this->message = new Messages();
      
      if(isset($_POST['login']))
      {
         $this->doLogin();
      }
    }
    
    private function doLogin()
    {
      if($this->validateFormData())
      {
        if($this->authenticate())
        {
          
          return true;
        } else
        {
          // Authentication failed...drop down into default return.
        }
      }
      // Invalid form data / authentication failed
      $msg_details = array("Username or Password is invalid.");
      $this->message->error($msg_details, true);
      return false;
      $this->message->info(array("Logging in...", false));
    }

    private function authenticate($username, $password)
    {
      
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
  }
?>