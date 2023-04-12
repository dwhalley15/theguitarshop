<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: ../pages/login.php");
    exit;
  }

  /*A script to remove a purchase from the database based on order_id
    Once complete routes the user to the account page*/
  if(!empty($_GET['order_id']) && isset($_GET['order_id'])){    
    include '../classes/database.php';
    include '../classes/updateQuery.php';
    include '../classes/deleteQuery.php';
    include '../classes/selectQuery.php';
    $database = new Database();
    $update = new UpdateQuery();
    $delete = new DeleteQuery();
    $select = new SelectQuery();
    $order_id = $_GET['order_id'];
    $conn = $database->connect();
    if(!$conn){
      header("location: ../pages/account.php");
      exit;
    }
    else{
      $order_line = $select->selectOrderLine($conn, $order_id);
      while($row = mysqli_fetch_assoc($order_line)){
        if($row['stock'] == "none"){
          $row['stock'] = 0;
        }
        $new_stock = $row['stock'] + $row['quantity'];
        $update->updateStock($conn, $row['prod_id'], $new_stock);
      }
      $delete->deleteOrder($conn, $order_id);
      header("location: ../pages/account.php");
      exit;
    }
  }
  else{
    header("location: ../pages/account.php");
    exit;
  }
?>