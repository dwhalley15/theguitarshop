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
      <form action='completeOrder.php' name='new_order' method='post'>
        <div class='orders'>
          
          <?php
            
            include '../classes/database.php';
            include '../classes/selectQuery.php';
            $database = new Database();
            $query = new SelectQuery();
            $total = 0;
            //A function to display a user address from the database based on user_id. 
            function displayPaymentDetails(){
              $database = new Database();
              $query = new SelectQuery();
              $user_id = $_SESSION['user_id'];
              $conn = $database->connect();
              if(!$conn){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                $row = $query->orderDetails($conn, $user_id);
                $database->disconnect($conn);
                if(!$row){
                  echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
                }
                else{
                  echo "<div class='payment'>
                          <h3>Shipping Address</h3>
                            <label for='street_address'>Street Address</label><span class='error'>*</span>
                            <input type='text' maxlength='255' name='street_address' value='".$row['street_address']."'>
                            <label for='town'>Town</label><span class='error'>*</span>
                            <input type='text' maxlength='255' name='town' value='".$row['town']."'>
                            <label for='county'>County</label><span class='error'>*</span>
                            <input type='text' maxlength='255' name='county' value='".$row['county']."'>
                            <label for='post_code'>Post Code</label><span class='error'>*</span>
                            <input type='text' maxlength='9' name='post_code' value='".$row['post_code']."'>
                            <br><h3>Payment Details</h3>
                            <label for='payment'>Card Details</label><span class='error'>*</span>
                            <input type='text' maxlength='255' name='payment' value='Disabled as this site does not take real money' disabled>
                            <input type='text' style='width: 30%'; disabled>&nbsp<input type='text' style='width: 30%'; disabled>
                            <p class='error' id='formValidation'>Fields marked with * must be completed!</p>
                            <br><img src='../images/cardicon.png' alt='cardicon' style='width:110px;, height:60px;'>
                        </div>";
                }
              }
            }
          //A condition to run displayPaymentDetails only if a user_id is present.
          if(!empty($_SESSION['user_id'])){
             displayPaymentDetails();
          }
          else{
             echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Please log in or create a new account.</p>";
          }
          ?>

        <div class='orderItems'>
          <h3>Order Items</h3>
          
          <?php
            //A function to display all items in the cart session array.
            //Returns the total price of all products present in the cart.
            function displayCart(){
              $databaseCart = new Database();
              $queryCart = new SelectQuery();
              $cart_products = $_SESSION['cart'];
              foreach($cart_products as $product_id_key => $quantity){
                $prod_ids[] = $product_id_key;
              }
              $prod_ids_string = implode(', ', $prod_ids);
              $conn = $databaseCart->connect();
              if(!$conn){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                $result = $queryCart->selectIds($conn, $prod_ids_string);
                $databaseCart->disconnect($conn);
                if(!$result){
                      echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";      
                    }
                    else{
                      while($row = mysqli_fetch_assoc($result)){
                        echo " <div class='orderItem'> 
                                <img class='cartImg' src='../uploads/" . $row['image'] ."' alt='" . $row['name'] . "'>
                                <div class='cartText'>
                                  <h5>" . $row['brand'] . " - " . $row['name'] . "</h5>
                                  <p>Quantity " . $cart_products[$row['prod_id']] . "</p>";
                                  if($row['on_sale'] == "yes"){
                                    $salePrice = $row['price'] - $row['sale_reduction'];
                                    $subTotal = $salePrice * $cart_products[$row['prod_id']];
                                    $total += $subTotal;
                                  echo "<p class='onSale'>£" . number_format($subTotal, 2) . "</p>";
                                  }
                                  else{
                                    $subTotal = $row['price'] * $cart_products[$row['prod_id']];
                                    $total += $subTotal;
                                    echo "<p>£" . number_format($subTotal, 2) . "</p>";
                                  }
                        echo "</div></div>";
                      }
                }
              }
              return $total;
            }
            //A condition to run displayCart only if cart session array is set.
            if(!empty($_SESSION['cart'])){    
              $total = displayCart();
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
                  echo "<input type='button' id='confirmBtn' class='buyBtn' value='Place Order'>";
            }
            else{
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Your Cart is empty.</p>";
            }
            ?>

            </div>
          </div>
        </form>
      </div>
    </div>
    <script src="../jsScripts/addOrder.js"></script>
  </body>
  
  <?php include 'footer.php'?>

</html>