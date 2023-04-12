<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
    exit;
  }
  //A post condition that updates a purchase in the database based on order_id.
  include '../classes/database.php';
  include '../classes/selectQuery.php';
  include '../classes/updateQuery.php';
  include '../classes/validation.php';
  if(!empty($_POST) && isset($_POST)){
    $order_id = $_GET['order_id'];
    $new_address = $_POST['street_address'] . ", " . $_POST['town'] . ", " . $_POST['county'] . ", " . $_POST['post_code'];
    $data = new Database();
    $update = new UpdateQuery();
    $validate = new Validation();
    $new_address = $validate->trimStripSpecial($new_address);
    $new_address = $validate->charLimit($new_address);
    $msg = "";
    if($new_address == ""){
      $msg = "OOPS! Something went wrong! Invalid address entered!";
    }
    else{
      $conn = $data->connect();
      if(!$conn){
        $msg = "OOPS! Something went wrong! Could not connect to database.";
      }
      else{
        $updated = $update->updateOrderAdd($conn, $order_id, $new_address);
        if($updated !== true){
          $msg = "OOPS! Something went wrong! Failed to update order.";
        }
        else{
          header("Refresh: 0");
        }
      }
    }
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
      <h3>Order Number: <?php echo $_GET['order_id'];?></h3>
      <p><?php if(!empty($msg)) echo $msg; ?></p>
      <div class='orders'>

      <?php
        //A function to display a purchase from the database based on order_id.
        function showOrderAdd(){
          $order_id = $_GET['order_id'];
          $db = new Database();
          $select = new SelectQuery();
          $conn = $db->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
          else{
            $order_row = $select->selectOrder($conn, $order_id);
            $db->disconnect($conn);
            $address = $order_row['delivery_address'];
            $address_arr = explode(", ", $address);
            if($order_row['user_id'] != $_SESSION['user_id']){
              echo "<h3>OOPS! Something went wrong!</h3><br>
                    <p>No order selected please return to your <a class='linkBtn' href='account.php'>account</a>.</p>";
            }
            else{
              echo "<div class='payment'>
                      <h3>Shipping Address</h3>
                        <form name='editAddress' action='";htmlspecialchars($_SERVER['PHP_SELF']); echo"' method='post'>
                         <label for='street_address'>Street Address</label><span class='error'>*</span>
                         <input type='text' maxlength='255' name='street_address' value='".$address_arr[0]."'>
                         <label for='town'>Town</label><span class='error'>*</span>
                         <input type='text' maxlength='255' name='town' value='".$address_arr[1]."'>
                         <label for='county'>County</label><span class='error'>*</span>
                         <input type='text' maxlength='255' name='county' value='".$address_arr[2]."'>
                         <label for='post_code'>Post Code</label><span class='error'>*</span>
                         <input type='text' maxlength='9' name='post_code' value='".$address_arr[3]."'>
                         <br><h3>Payment Details</h3>
                         <label for='payment'>Card Details</label><span class='error'>*</span>
                         <input type='text' maxlength='255' name='payment' value='".$order_row['payment_details']."' disabled>
                         <input type='text' style='width: 30%'; disabled>&nbsp<input type='text' style='width: 30%'; disabled>
                         <p class='error' id='formValidation'>Fields marked with * must be completed!</p>
                         <br><img src='../images/cardicon.png' alt='cardicon' style='width:110px;, height:60px;'>
                        <input type='button' id='editAddBtn' class='confirmBtn' value='Update Delivery/Payment'>
                      </form>
                    </div>";
            }
          }
        }
        //A condition to run the showOrderAdd only if an order_id is present.
        if(!empty($_GET['order_id']) && isset($_GET['order_id'])){
              showOrderAdd();
            }
            else{
              echo "<h3>OOPS! Something went wrong!</h3><br>
                    <p>No order selected please return to your <a class='linkBtn' href='account.php'>account</a>.</p>";
            }
        ?>

        <div class='orderItems'>
          <h3>Order Items</h3>
          
          <?php
            //A function to display the order items/lines based on order_id.
            function showOrderLines(){
              $database = new Database();
              $query = new SelectQuery();
              $order_id = $_GET['order_id'];
              $total = 0;
              $conn = $database->connect();
                if(!$conn){
                  echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
                }
                else{
                  $order_row = $query->selectOrder($conn, $order_id);
                  if($order_row['user_id'] != $_SESSION['user_id']){
                    $database->disconnect($conn);
                    echo "<h3>OOPS! Something went wrong!</h3><br>
                          <p>No order selected please return to your <a class='linkBtn' href='account.php'>account</a>.</p>";
                  }
                  else{
                    $order_line = $query->selectOrderLine($conn, $order_id);
                    $database->disconnect($conn);
                    while($row = mysqli_fetch_assoc($order_line)){
                      echo " <div class='orderItem'> 
                              <img class='cartImg' src='../uploads/" . $row['image'] ."' alt='" . $row['name'] . "'>
                              <div class='cartText'>
                                <h5>" . $row['brand'] . " - " . $row['name'] . "</h5>
                                <p>Quantity " . $row['quantity'] . "</p>";
                                if($row['on_sale'] == "yes"){
                                  $salePrice = $row['price'] - $row['sale_reduction'];
                                  $subTotal = $salePrice * $row['quantity'];
                                  $total += $subTotal;
                                echo "<p class='onSale'>£" . number_format($subTotal, 2) . "</p>";
                                }
                                else{
                                  $subTotal = $row['price'] * $row['quantity'];
                                  $total += $subTotal;
                                  echo "<p>£" . number_format($subTotal, 2) . "</p>";
                                }
                        echo "</div>
                                <form name='r".$row['prod_id']."' action='../phpScripts/deleteOrderLine.php' method='post'>
                                  <input type='hidden' name='prod_id' value='".$row['prod_id']."'>
                                  <input type='hidden' name='order_id' value='".$row['order_id']."'>
                                  <input type='hidden' name='quantity' value='".$row['quantity']."'>
                                  <input type='button' id='".$row['prod_id']."' class='cartBtn deleteLineBtn' value='Remove Item'>
                              </form>
                          </div>";
                    }
                  }
                }
              return $total;
            }
            //Condition to run showOrderLines function only if an order_id is present.
            if(!empty($_GET['order_id']) && isset($_GET['order_id'])){
              $total = showOrderLines();
              echo "<div class='cartOrder'>
                  <p> Order Total: £" . number_format($total,2) . "</p>
                  <p>"; if($total > 50){
                          echo "Orders over £50 have free shipping!
                                <input type='hidden' name='totalPrice' value='" . $total . "'>
                                </p>";
                        }
                        else{
                          $total = $total + 25;
                          echo "Total to Pay with p&p: £" . number_format($total, 2) . "
                                <input type='hidden' name='totalPrice' value='" . $total . "'>
                                </p></div>";
                        } 
            }
          ?>
              
        </div>
      </div>
    </div>
    <script src="../jsScripts/editOrder.js"></script>
  </body>
  
  <?php include 'footer.php'?>

</html>
