<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $login = new AccountLogin();

  $result = $login->getResult();

  if($result)
  {
    // Redirect to BookStore homepage if login was successful.
    header("Location: ".$config["paths"]["baseurl"].$config["content"]["index"], true, 303);
  } else
  {
    // Redirect to BookStore login page if login was unsuccessful.
    header("Location: ".$config["paths"]["baseurl"].$config["content"]["login"], true, 303);
  }
  die();

  /**
   * AccountLogin performs credential validation and session creation/destruction for
   * users of the BookStore.
   */
  class AccountLogin
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
     * The results of functions.
     */
    private $result;
    
    /**
     * Constructs AccountLogin by initialising Messages and DatabaseHelper objects.
     * Then checks if any POST actions have been sent to perform login or logout.
     */
    public function __construct()
    {
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      
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
        $this->messages->createMessage("Goodbye!", array("You have been signed out. Please visit again soon!"), "info");
        $this->result = true;
      } else {
        $this->result = false;
      }
    }
    
    /**
     * Perform the login steps of BookStore.
     */
    private function doLogin()
    {
      if($this->validateFormData())
      {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $this->authenticate($username, $password);
      }
    }

    /**
     * Authenticates user input against database values.
     *
     * @param String $username  The username entered.
     * @param String $password  The password entered.
     */
    private function authenticate($username, $password)
    {
      try
      {
        $this->db->openConnection();
        $connection = $this->db->getConnection();
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
            $this->messages->createMessage("Welcome back", array("<strong>$username</strong>!"), "info");
            $this->result = true;
            return;
          }
        }
        $msg_details = array("Username or Password is invalid.");
        $this->messages->createMessage("Login Failed!", $msg_details, "error");
      } catch (PDOException $ex)
      {
        $this->db->showError($ex);
      } finally
      {
        $this->db->closeConnection();
      }
    }
    
    /**
     * Validates the user input on the registration form using
     * regex matching, and min/max length checking.
     *
     * @return Boolean Returns whether the form is valid or not
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
        $msg_details = array("Username or Password is invalid.");
        $this->messages->createMessage("Login Failed!", $msg_details, "error");
        return false;
      }
    }
    
    /**
     * Returns the value of the result variable.
     *
     * @return String   The value of the result variable.
     */
    public function getResult()
    {
      return $this->result;
    }
  }
?>