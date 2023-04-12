<?php

  class InsertQuery{
    
    //A function to check if a product already exists in the database based on name and brand.
    function duplicate($conn, $name, $brand){
      $result = mysqli_query($conn, "SELECT count(*) AS count_all FROM product WHERE name='$name' && brand='$brand'");
      $row = mysqli_fetch_array($result);
      $count = $row['count_all'];
      return $count;
    }

    //A function to add a product to the database returns the new prod_id if successful.
    function insertProduct($conn, $product){
      $prod_id = 0;
      $query = "INSERT INTO product (name, description, brand, price ,sku, stock, image, type, on_sale, sale_reduction) VALUES ('".$product['name']."', '".$product['description']."', '".$product['brand']."', '".$product['price']."', '".$product['sku']."', '".$product['stock']."', '".$product['image']."', '".$product['type']."', '".$product['on_sale']."', '".$product['sale_reduction']."')";
      if(mysqli_query($conn, $query)){
        $prod_id = mysqli_insert_id($conn);
      }
      return $prod_id;
    }

    //A function to check if an account already exists in the database based on email.
    function duplicateUser($conn, $email){
      $result = mysqli_query($conn, "SELECT count(*) AS count_all FROM user WHERE email='$email'");
      $row = mysqli_fetch_array($result);
      $count = $row['count_all'];
      return $count;
    }

    //A function to add a user to the database returns the new user_id if successful.
    function insertUser($conn, $account){
      $user_id = 0;
      $query = "INSERT INTO user (first_name, last_name, email, phone_number ,street_address, town, county, post_code, role, password) VALUES ('".$account['first_name']."', '".$account['last_name']."', '".$account['email']."', '".$account['phone_number']."', '".$account['street_address']."', '".$account['town']."', '".$account['county']."', '".$account['post_code']."', '".$account['role']."', '".$account['password']."')";
      if(mysqli_query($conn, $query)){
        $user_id = mysqli_insert_id($conn);
      }
      return $user_id;
    }

    //A function to add a new purchase to the database returns the new order_id if successful. 
    function insertOrder($conn, $new_order){
      $order_id = 0;
      $query = "INSERT INTO purchase (user_id, delivery_address, payment_details, order_date) VALUES ('".$new_order['user_id']."', '".$new_order['delivery_address']."', '".$new_order['payment_details']."', '".$new_order['order_date']."')";
      if(mysqli_query($conn, $query)){
        $order_id = mysqli_insert_id($conn);
      }
      return $order_id;
    }
    
    //A function to add a new order_line to the database.
    function insertOrderLine($conn, $new_order_line){
      $query = "INSERT INTO order_line (order_id, prod_id, quantity) VALUES ('".$new_order_line['order_id']."', '".$new_order_line['prod_id']."', '".$new_order_line['quantity']."')";
      mysqli_query($conn, $query);
    }

  }

?>