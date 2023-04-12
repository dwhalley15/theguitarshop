<?php
  //A script to log out a user (clears all session variables).
  session_start();
  $_SESSION = array();
  session_destroy();
  header("location: ../pages/login.php");
  exit;
?>