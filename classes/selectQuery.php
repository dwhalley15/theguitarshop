<?php

  class SelectQuery{

    //A function to select all products in the database that are marked as on sale.
    function selectOnSale($conn){
      $result = mysqli_query($conn, "SELECT * FROM product WHERE on_sale = 'yes'");
      return $result;
    }

    //A function to select one product from the database based on prod_id.
    function selectId($conn, $prod_id){
      $result = mysqli_query($conn, "SELECT * FROM product WHERE prod_id = '$prod_id'");
      $row = mysqli_fetch_assoc($result);
      return $row;
    }

    //A function to select products from the database based on one or more prod_ids.
    function selectIds($conn, $prod_ids){
      $result = mysqli_query($conn, "SELECT * FROM product WHERE prod_id IN ($prod_ids)");
      return $result;
    }

    //A function to select all products from the database where attributes name and brand are similar to a search term includes limit needed for pagination.
    function selectSearch($conn, $search, $offset, $total_products_per_page){
      $result = mysqli_query($conn, "SELECT * FROM product WHERE LOWER(name) LIKE '%" . strtolower($search) . "%' OR LOWER(brand) LIKE '%" . strtolower($search) . "%' LIMIT $offset, $total_products_per_page");
      return $result;
    }

    //A function to count products from the database where attributes name and brand are similar to a search term returns the count needed for pagination.
    function searchCount($conn, $search){
      $count = mysqli_query($conn, "SELECT COUNT(*) As total_products FROM product WHERE LOWER(name) LIKE '%" . strtolower($search) . "%' OR LOWER(brand) LIKE '%" . strtolower($search) . "%'");
      $result = mysqli_fetch_array($count);
      return $result;
    }

    //A function to count products from the database based on the attribute type returns the count needed for pagination.
    function typeCount($conn, $productType){
      $count = mysqli_query($conn, "SELECT COUNT(*) As total_products FROM product WHERE type = '$productType'");
      return $count;
    }

    //A function to count all products in the database returns the count needed for pagination.
    function countAll($conn){
      $count = mysqli_query($conn, "SELECT COUNT(*) As total_products FROM product");
      return $count;
    }
    
    //A function to select products from the database based on attribute type then orders them based on either a parameter or a default includes limit needed for pagination.
    function selectType($conn, $productType, $offset, $total_products_per_page, $orderBy = "name"){
      $result = mysqli_query($conn, "SELECT * FROM product WHERE type = '$productType' ORDER BY $orderBy ASC LIMIT $offset, $total_products_per_page");
      return $result;
    }

    //A function to selects all products in the database includes limit needed for pagination.
    function selectAllProducts($conn, $offset, $total_products_per_page){
      $result = mysqli_query($conn, "SELECT * FROM product LIMIT $offset, $total_products_per_page");
      return $result;
    }

    //A function to select all products from the database.
    function selectProducts($conn){
      $result = mysqli_query($conn, "SELECT * FROM product");
      return $result;
    }

    //A function to select a user from the database based on email returns only the details needed for log in.
    function logIn($conn, $email){
      $result = mysqli_query($conn, "SELECT user_id, email, first_name, last_name, password, role FROM user WHERE email = '$email'");
      $row = mysqli_fetch_array($result);
      return $row;
    }

    //A function to select a user from the database based on user_id only returns details needed for creating an purchase.
    function orderDetails($conn, $user_id){
      $result = mysqli_query($conn, "SELECT street_address, town, county, post_code FROM user WHERE user_id = '$user_id'");
      $row = mysqli_fetch_array($result);
      return $row;
    }

    //A function to count all purchases in the database based on the foreign key user_id.
    function countOrders($conn, $user_id){
      $result = mysqli_query($conn, "SELECT COUNT(*) As all_orders FROM purchase WHERE user_id = '$user_id'");
      $row = mysqli_fetch_array($result);
      $count = $row['all_orders'];
      return $count;
    }

    //A function to selects all purchases from the database based on the forieng key user_id.
    function selectOrders($conn , $user_id){
      $result = mysqli_query($conn, "SELECT order_id, order_date FROM purchase WHERE user_id = '$user_id' ORDER BY order_date");
      return $result;
    }

    //A fucntion to selects a purchase from the database based on order_id.
    function selectOrder($conn, $order_id){
      $result = mysqli_query($conn, "SELECT * FROM purchase WHERE order_id = '$order_id'");
      $row = mysqli_fetch_array($result);
      return $row;
    }

    //A function to select all purchases and related order_lines from the database based on order_id.
    function selectOrderLine($conn, $order_id){
      $result = mysqli_query($conn, "SELECT * FROM order_line INNER JOIN product ON order_line.prod_id=product.prod_id WHERE order_id='$order_id'");
      return $result;
    }

    //A function to select a user from the database based on user_id.
    function selectAccountDetails($conn, $user_id){
      $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id='$user_id'");
      $row = mysqli_fetch_array($result);
      return $row;
    }
    
  }

?>