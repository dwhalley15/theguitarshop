<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] != "staff"){
    header("location: ../pages/login.php");
    exit;
  }
  /*A script to delete a product from the database based on prod_id.
    Once complete routes the user to the manage product page.*/
  if(!empty($_GET['prod_id']) && isset($_GET['prod_id'])){
    include "../classes/database.php";
    include "../classes/deleteQuery.php";
    include "../classes/selectQuery.php";
    $database = new Database();
    $delete = new DeleteQuery();
    $select = new SelectQuery();
    $prod_id = $_GET['prod_id'];
    $conn = $database->connect();
    if(!$conn){
      header("location: ../pages/manageProduct.php");
      exit;
    }
    else{
      $row = $select->selectId($conn, $prod_id);
      $image = $row['image'];
      $image_path = "../uploads/".$image;
      unlink($image_path);
      $result = $delete->deleteProduct($conn, $prod_id);
      $database->disconnect($conn);
      if($result == true){
        header("location: ../pages/manageProduct.php");
        exit;
      }
      else{
        header("location: ../pages/manageProduct.php");
        exit;
      }
    }
  }
  else{
    header("location: ../pages/manageProduct.php");
    exit;
  }
?>





