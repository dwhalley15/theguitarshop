<?php
  //Routes users to the home page.
  session_start();
  session_regenerate_id();
  header("location: pages/home.php");
?>