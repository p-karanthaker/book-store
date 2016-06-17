<?php
  class Messages
  {
    private $message;
    
    public function __construct()
    {
      session_start();
      $_SESSION["message"] = null;
    }
    
    public function info($message, $isBlock = false)
    {
      if(!$isBlock)
      {
        $this->message = "<div class='container'>
                            <div class='alert alert-info'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <strong>Info:</strong> $message[0]
                            </div>
                           </div>";
      } else
      {
        $msg = "";
        foreach ($message as $value) {
          $msg .= "<li>$value</li>";
        }
        $this->message ="<div class='container'>
                           <div class='alert alert-info alert-block'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <h5><strong>Information</strong></h5> 
                             <ul>$msg</ul>
                           </div>
                         </div>";
      }
      $_SESSION["message"] = $this->message;
    }
    
    public function success($message, $isBlock = false)
    {
      if(!$isBlock)
      {
        $this->message = "<div class='container'>
                            <div class='alert alert-success'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <strong>Success!</strong> $message[0]
                            </div>
                           </div>";
      } else
      {
        $msg = "";
        foreach ($message as $value) {
          $msg .= "<li>$value</li>";
        }
        $this->message ="<div class='container'>
                           <div class='alert alert-success alert-block'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <h5><strong>Success!</strong></h5> 
                             <ul>$msg</ul>
                           </div>
                         </div>";
      }
      $_SESSION["message"] = $this->message;
    }
    
    public function warning($message, $isBlock = false)
    {
      if(!$isBlock)
      {
        $this->message = "<div class='container'>
                            <div class='alert alert-warning'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <strong>Warning!</strong> $message[0]
                            </div>
                           </div>";
      } else
      {
        $msg = "";
        foreach ($message as $value) {
          $msg .= "<li>$value</li>";
        }
        $this->message ="<div class='container'>
                           <div class='alert alert-warning alert-block'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <h5><strong>Warning!</strong></h5> 
                             <ul>$msg</ul>
                           </div>
                         </div>";
      }
      $_SESSION["message"] = $this->message;
    }
    
    public function error($message, $isBlock = false)
    {
      if(!$isBlock)
      {
        $this->message = "<div class='container'>
                            <div class='alert alert-error'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <strong>Error!</strong> $message[0]
                            </div>
                           </div>";
      } else
      {
        $msg = "";
        foreach ($message as $value) {
          $msg .= "<li>$value</li>";
        }
        $this->message ="<div class='container'>
                           <div class='alert alert-error alert-block'>
                             <a class='close' data-dismiss='alert'>&times;</a>
                             <h5><strong>Error!</strong></h5> 
                             <ul>$msg</ul>
                           </div>
                         </div>";
      }
      $_SESSION["message"] = $this->message;
    }
  }
?>