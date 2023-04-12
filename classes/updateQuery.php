<?php

  class UpdateQuery{

    //A function to update the stock attribute of a product in the database based on prod_id.
    function updateStock($conn, $prod_id, $stock){
      $complete = true;
      $query = "UPDATE product SET stock='$stock' WHERE prod_id='$prod_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }

    //A function to update the delivery address attribute of a purchase in the database based on order_id.
    function updateOrderAdd($conn, $order_id, $new_address){
      $complete = true;
      $query = "UPDATE purchase SET delivery_address='$new_address' WHERE order_id='$order_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }

    //A function to update most attributes of a user in the database based on user_id.
    function updateAccount($conn, $update_account){
      $complete = true;
      $query = "UPDATE user SET 
                first_name='".$update_account['first_name']."',
                last_name='".$update_account['last_name']."',
                phone_number='".$update_account['phone_number']."',
                street_address='".$update_account['street_address']."',
                town='".$update_account['town']."',
                county='".$update_account['county']."',
                post_code='".$update_account['post_code']."'
                WHERE user_id='".$update_account['user_id']."'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }

    //A function to update the password attribute of a user in the database based on user_id.
    function updatePassword($conn, $pass, $user_id){
      $complete = true;
      $query = "UPDATE user SET password='$pass' WHERE user_id='$user_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }
    
    //A function to update most attributes of a product in the database based on prod_id.
    function updateProduct($conn, $update_product){
      $complete = true;
      $query = "UPDATE product SET
                name='".$update_product['name']."',
                description='".$update_product['description']."',
                brand='".$update_product['brand']."',
                price='".$update_product['price']."',
                sku='".$update_product['sku']."',
                stock='".$update_product['stock']."',
                type='".$update_product['type']."',
                on_sale='".$update_product['on_sale']."',
                sale_reduction='".$update_product['sale_reduction']."'
                WHERE prod_id='".$update_product['prod_id']."'";
      if(!mysqli_query($conn, $query)){
         $complete = false;
      }
      return $complete;
    }

    //A function to update the image attribute of a product in the database based on prod_id.
    function updateImage($conn, $image, $prod_id){
      $complete = true;
      $query = "UPDATE product SET image='$image' WHERE prod_id='$prod_id'";
      if(!mysqli_query($conn, $query)){
        $complete = false;
      }
      return $complete;
    }
    
  }

?>