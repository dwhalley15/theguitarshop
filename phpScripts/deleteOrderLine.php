<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: ../pages/login.php");
    exit;
  }
  /*A script to remove an order_line from the database based on the compostite key of order_id and prod_id. 
    Once complete routes the user to the edit order page.*/
  if(!empty($_POST) && isset($_POST)){
    include '../classes/database.php';
    include '../classes/updateQuery.php';
    include '../classes/deleteQuery.php';
    include '../classes/selectQuery.php';
    $database = new Database();
    $update = new UpdateQuery();
    $delete = new DeleteQuery();
    $select = new SelectQuery();
    $order_id = $_POST['order_id'];
    $prod_id = $_POST['prod_id'];
    $quantity = $_POST['quantity'];
    $conn = $database->connect();
    if(!$conn){
      header("location: ../pages/editOrder.php?order_id=$order_id");
      exit;
    }
    else{
      $row = $select->selectId($conn, $prod_id);
      if($row['stock'] == "none"){
        $row['stock'] = 0;
      }
      $new_stock = $row['stock'] + $quantity;
      $update->updateStock($conn, $prod_id, $new_stock);
      $delete->deleteOrderLine($conn, $order_id, $prod_id);
      $count = $delete->countOrderLine($conn, $order_id);
      $database->disconnect($conn);
      if($count <= 0){
          header("location: deleteOrder.php?order_id=$order_id");
          exit;
      }
      else{
        header("location: ../pages/editOrder.php?order_id=$order_id");
        exit;
      }
    }
  }
?>