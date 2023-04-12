<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: ../pages/login.php");
    exit;
  }
  /* A script which deletes a user from the database based on the user_id.
    If failed routes the user to the account page.
    Once completed routes the user to the logout script.*/
  if(!empty($_POST) && isset($_POST)){
    include "../classes/database.php";
    include "../classes/deleteQuery.php";
    $database = new Database();
    $delete = new DeleteQuery();
    $user_id = $_POST['user_id'];
    $conn = $database->connect();
    if(!$conn){
      header("location: ../pages/account.php");
      exit;
    }
    else{
      $result = $delete->deleteAccount($conn, $user_id);
      $database->disconnect($conn);
      if($result == true){
        header("location: logout.php");
        exit;
      }
      else{
        header("location: ../pages/account.php");
        exit;
      }
    }
  }
  else{
    header("location: ../pages/account.php");
    exit;
  }
?>