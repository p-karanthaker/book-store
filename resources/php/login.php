<?php
  function login()
  {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(authenticate($username, $password))
    {
      echo "Username: ", $username, "<br>";
      echo "Password: ", $password, "<br>";
    } else
    {
      echo "Invalid!";
    }
  }

  function authenticate($username, $password)
  {
    if($password != $username)
    {
      return true;
    } else
    {
      return false;
    }
  }
  
  if(isset($_POST['submit']))
  {
     login();
  }
?>