<?php
  /* A script whichs adds an product item to the cart array in the current session. 
  If an item already exists in the cart session array just increases the quantity.
  Once completed routes the user back to the product view page. */
  session_start();
  session_regenerate_id();
  if(!empty($_POST)){
    $prod_id = $_POST['prod_id'];
    $quantity = $_POST['quantity'];
    if($quantity > 0){
      if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
        if(array_key_exists($prod_id, $_SESSION['cart'])){
          $_SESSION['cart'][$prod_id] += $quantity;
        }
        else{
          $_SESSION['cart'][$prod_id] = $quantity;
        }
      }
      else{
        $_SESSION['cart'] = array($prod_id => $quantity);
      }
    }
  }
  header("location: ../pages/productView.php?prod_id=$prod_id");
  exit;
?>