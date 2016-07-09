<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"]."/resources/php/CommonObjects.php");
  $register = new AccountRegistration();

  $result = $register->getResult();

  if($result)
  {
    // Redirect to BookStore homepage if registration was successful.
    header("Location: ".$config["paths"]["baseurl"].$config["content"]["index"], true, 303);
  } else
  {
    // Redirect to BookStore registration page if registration was unsuccessful.
    header("Location: ".$config["paths"]["baseurl"].$config["content"]["register"], true, 303);
  }

  die();

  /**
   * AccountRegistration performs the actions needed to register a user
   * to the BookStore database.
   */
  class AccountRegistration
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
     * The config associative array.
     */
    private $config;
    
    /**
     * Constructs AccountRegistration by initialising Messages and DatabaseHelper objects.
     * Then checks if any POST actions have been sent to perform registration.
     */
    public function __construct()
    { 
      global $config;
      $this->config = $config;
      $this->messages = new Messages();
      $this->db = new DatabaseHelper();
      if(isset($_POST["register"]))
      {
        $this->registerUser();
      }
    }
    
    /** 
     * Registers a user to the BookStore database.
     */
    private function registerUser()
    {
        if($_POST["user_type"] === "Staff" && $this->config["disabled"]["staff_registration"])
        {
          $this->messages->createMessage("Forbidden!", array("Staff registration is currently disabled."), "error");
          return false;
        }
        if($this->validateFormData())
        { 
          try
          {
            $this->db->openConnection();
            $connection = $this->db->getConnection();

            /* gather details for html escaping*/
            $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
            $user_type = htmlspecialchars($_POST["user_type"], ENT_QUOTES); 

            /* hash+salt password */
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

            /* check user doesn't exist */
            $statement = $connection->prepare("SELECT username FROM user WHERE username = :username");
            $statement->bindValue(":username", $username);
            $statement->execute();

            if($statement->rowCount() == 0)
            {
              $statement = $connection->prepare("INSERT INTO user (username, password_hash, type)
                                                 VALUES(:username, :password_hash, :type)");
              $statement->bindParam(":username", $username);
              $statement->bindParam(":password_hash", $password);
              $statement->bindParam(":type", $user_type);

              $statement->execute();
              // Account created
              $this->messages->createMessage("Success!", array("Your account has been created."), "success");
              $this->result = true;
              return true;
            }
            // Account already exists
            $this->messages->createMessage("Error!", array("User already exists, please try again."), "error");
            return false;
          } catch (PDOException $ex)
          {
            $this->db->showError($ex);
          } finally
          {
            $this->db->closeConnection();
          }
        } else 
        {
          // Invalid form data
          $msg_details = array
            ("<strong>Username</strong> must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>."
            ,"<strong>Passwords</strong> must match, and must be <strong>6-12</strong> characters long using only <strong>alphanumerics</strong>."
            );
          $this->messages->createMessage("Invalid Fields!", $msg_details, "warning", ["isBlock" => true]);
          return false;
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
