<?php
  class Messages
  {
    private $message;
    
    public function __construct()
    {
      $_SESSION["message"] = null;
    }
    
    public function createMessage($title, $message, $type, $isBlock = false) {
      $alertClass;
      $titleAndMessage = "<strong>";
      switch($type)
      {
        case "info":
          $alertClass = "alert-info";
          $titleAndMessage .= $isBlock ? "<h5>$title</h5>" : "$title";
          break;
        case "success":
          $alertClass = "alert-success";
          $titleAndMessage .= $isBlock ? "<h5>$title</h5>" : "$title";
          break;
        case "warning":
          $alertClass = "alert-warning";
          $titleAndMessage .= $isBlock ? "<h5>$title</h5>" : "$title";
          break;
        case "error":
          $alertClass = "alert-error";
          $titleAndMessage .= $isBlock ? "<h5>$title</h5>" : "$title";
          break;
        default:
          $alertClass = "alert-warning";
          $titleAndMessage .= "<h5>Invalid Message Type!</h5> </br>Your request has still been processed. </br>Valid message types are:";
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
      $this->message = "<div class='container'>
                          <div class='alert $alertClass'>
                            <a class='close' data-dismiss='alert'>&times;</a>
                            $titleAndMessage
                          </div>
                        </div>";
      $_SESSION["message"] = $this->message;
    }
  }
?>