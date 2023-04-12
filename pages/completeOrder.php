<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
    exit;
  }
?>

<!DOCTYPE html>
<html>
  
  <?php 
      include 'head.php';
      include 'header.php';
    ?>

  <body>
    <div class="form">
      
      <?php
        //A post condition to add a new purchase to the database with current user_id as the foreign key.
        if(!empty($_POST) && isset($_POST)){
          //Define all variables required for order.
          $user_id = $_SESSION['user_id'];
          $delivery_address = $_POST['street_address'] . ", " . $_POST['town'] . ", " . $_POST['county'] . ", " . $_POST['post_code'];
          $payment_details = "This site does not store any real payment details";
          $order_date = date("y-m-d");
          //Set validation check boolean.
          $orderComplete = true;
          //Calls classes
          include "../classes/database.php";
          include "../classes/insertQuery.php";
          include "../classes/updateQuery.php";
          include "../classes/validation.php";
          include '../classes/selectQuery.php';
          $database = new Database();
          $insert = new InsertQuery();
          $select = new SelectQuery();
          $update = new UpdateQuery();
          $validate = new Validation();
          //Check that the length of the atts. do not exceed 1000.
          $delivery_address = $validate->charLimit($delivery_address);
          $payment_details = $validate->charLimit($payment_details);
          //Add all variables into a multidimensional array.
          $new_order = array(
            "user_id"=>$user_id,
            "delivery_address"=>$delivery_address,
            "payment_details"=>$payment_details,
            "order_date"=>$order_date
          );
          //Strips unnecessary whitespace, removes any backslashes and changes any special characters to avoid malicious scripts.
          $new_order = $validate->trimStripSpecialArr($new_order);
          //Loop checks all values in array to see if any are considered empty.
          foreach($new_order as $key => $value){
            if(empty($value)){
              echo "<p class='errorShown'>" . $key . " was not entered correctly!</p>";
              $orderComplete = false;
            }
          }
          //Once all checks are complete either confirms order creation or shows what att. had errors and prompts to return to order creation.
          if($orderComplete === true){
            $conn = $database->connect();
            if(!$conn){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              $order_id = $insert->insertOrder($conn, $new_order);
              if(empty($order_id)){
                $database->disconnect($conn);
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Order was not completed.</p>
                      <p> Please return to <a class='linkBtn' href='placeOrder.php'>place order.</a>.</p>";
              }
              else{             
                //Loop through cart to get all prod_id s and quantities..
                $cart_products = $_SESSION['cart'];
                foreach($cart_products as $product_id_key => $quantity){
                  $prod_ids[] = $product_id_key;
                  $quantities[] = $quantity;
                }
                //Initialise order_line array.
                $new_order_line = array(
                  "order_id"=>$order_id,
                  "prod_id"=>0,
                  "quantity"=>0
                );
                //Loop through all order products adding them to the array and inserting to database.
                //Also updates the relevant stock level.
                for($i=0; $i < count($prod_ids); $i++){
                  $new_order_line['prod_id'] = $prod_ids[$i];
                  $new_order_line['quantity'] = $quantities[$i];
                  $insert->insertOrderLine($conn, $new_order_line);
                  $row = $select->selectId($conn, $prod_ids[$i]);
                  $new_stock = $row['stock'] - $quantities[$i];
                  if($new_stock <= 0){
                    $new_stock = "none";
                  }
                  $update->updateStock($conn, $prod_ids[$i], $new_stock);
                }
                //Disconnects from database clears the cart confirms success.
                $database->disconnect($conn);
                unset($_SESSION['cart']);
                echo "<h3>Congratulations</h3><br>
                      <p>Your order has been placed!</p><br>
                      <p>Click <a class='linkBtn' href='account.php'>here</a> to view your orders!</p>";              
              }
            }
          }
          else{
            echo "<h3>OOPS! Something went wrong!</h3>
                <p class='errorShown'>Order was not completed.</p>
                <p> Please return to <a class='linkBtn' href='placeOrder.php'>place order.</a>.</p>";
          }
        }
        else{
          echo "<h3>OOPS! Something went wrong!</h3>
                <p class='errorShown'>Order was not completed.</p>
                <p> Please return to <a class='linkBtn' href='placeOrder.php'>place order.</a>.</p>";
        }
      ?>

    </div>
  </body>
  <script src="../jsScripts/noFormResub.js"></script>
  
  <?php include 'footer.php'?>

</html>