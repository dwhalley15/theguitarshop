<?php

  class DeleteQuery{
  
    //A function to delete an order_line from the database based on a composite key of order_id and prod_id.
    function deleteOrderLine($conn, $order_id, $prod_id){
      $complete = true;
      $query = "DELETE FROM order_line WHERE order_id='$order_id' AND prod_id='$prod_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }
  
    //A function to delete an purchase from the database based on an order_id.
    function deleteOrder($conn, $order_id){
      $complete = true;
      $query = "DELETE FROM purchase WHERE order_id='$order_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }
  
    //A function to delete a user from the database based on a user_id.
    function deleteAccount($conn, $user_id){
      $complete = true;
      $query = "DELETE FROM user WHERE user_id='$user_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }
  
    //A function to delete a product from the database based on prod_id.
    function deleteProduct($conn, $prod_id){
      $complete = true;
      $query = "DELETE FROM product WHERE prod_id='$prod_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }

    //A function to count the amount of order_lines in an order.
    function countOrderLine($conn, $order_id){
        $result = mysqli_query($conn, "SELECT COUNT(*) As all_order_items FROM order_line INNER JOIN product ON order_line.prod_id=product.prod_id WHERE order_id='$order_id'");
        $row = mysqli_fetch_array($result);
        $count = $row['all_order_items'];
        return $count;
    }
    
  }

?>