<?php
  /**
   * The Messages class provides an easy method of creating
   * bootstrap flash alert messages. The messages can either be added to
   * a "message" session variable or they can returned as a string.
   */
  class Messages
  {/**
     * The message being written.
     */
    private $message;
    
    /**
     * Constructs the Message object by initialising a null session for the message.
     */
    public function __construct()
    {
      $_SESSION["message"] = null;
    }
    
    /**
     * Create a bootstrap alert flash message.
     *
     * @param String  $title  The title of the message.
     * @param String  $message  The message content.
     * @param String  $type The type of alert (info, success, warning, error)
     * @param Array   $options  Optional parameters in an associative array.
     *                          "isBlock" => true/false   Is the alert a block style? Default: false
     *                          "inSessionVar" => true/false  Is the alert in the session variable? Default: true
     *                          "dismissable" => true/false Is the alert able to be closed?
     *
     * @return  String  Returns the message as a string rather than in the session variable.
     */
    public function createMessage($title, $message, $type, $options = array()) {      
      $isBlock = array_key_exists("isBlock", $options) ? $options["isBlock"] : false;
      $inSessionVar = array_key_exists("inSessionVar", $options) ? $options["inSessionVar"] : true;
      $dismissable = array_key_exists("dismissable", $options) ? $options["dismissable"] : true;
      
      $alertClass;
      $titleAndMessage = "<strong>";
      $titleAndMessage .= $isBlock ? "<h5>$title</h5>" : "$title";
      switch($type)
      {
        case "info":
          $alertClass = "alert-info";
          break;
        case "success":
          $alertClass = "alert-success";
          break;
        case "warning":
          $alertClass = "alert-warning";
          break;
        case "error":
          $alertClass = "alert-error";
          break;
        default:
          $alertClass = "alert-warning";
          $titleAndMessage = "<strong><h5>Invalid Message Type!</h5> </br>Your request has still been processed. </br>Valid message types are:";
          $message = array("<strong>info</strong>"
                           ,"<strong>success</strong>"
                           ,"<strong>warning</strong>"
                           ,"<strong>error</strong>");
          $isBlock = true;
          break;
      }
      $titleAndMessage .= "</strong>";
      
      if($isBlock)
      {
        $alertClass .= " alert-block";
        $titleAndMessage .= "<ul>";
        foreach ($message as $value) {
          $titleAndMessage .= "<li>$value</li>";
        }
        $titleAndMessage .= "</ul>";
      } else 
      {
        $titleAndMessage .= " $message[0]";
      }
      
      $dismissElement = "";
      if($dismissable)
      {
        $dismissElement = "<a class='close' data-dismiss='alert'>&times;</a>";
      }
      $this->message = "<div class='container'>
                          <div class='alert $alertClass'>
                            $dismissElement
                            $titleAndMessage
                          </div>
                        </div>";
      if($inSessionVar)
      {
        $_SESSION["message"] = $this->message;
      } else
      {
        return $this->message;
      }
    }
  }
?>