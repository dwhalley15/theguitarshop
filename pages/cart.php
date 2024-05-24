<?php
  session_start();
  session_regenerate_id();
  //A post condition to remove an item from the cart session array.
  if(!empty($_POST) && isset($_POST)){
    $prod_to_remove = $_POST['prod_id'];
    unset($_SESSION['cart'][$prod_to_remove]);
    session_write_close();
    header("Refresh: 0");
  }
?>
    
<!DOCTYPE html>
<html>
    
    <?php 
      include 'head.php';
      include 'header.php';
    ?>

    <body>
    <div class="form" id="cart-page">
      
      <?php
        //A function to display all products based on any prod_ids preset in the cart session array.
        function displayCart(){
          include '../classes/database.php';
          include '../classes/selectQuery.php';
          $cart_products = $_SESSION['cart'];
          $total = 0;
          foreach($cart_products as $product_id_key => $quantity){
            $prod_ids[] = $product_id_key;
          }
          $prod_ids_string = implode(', ', $prod_ids);
          $database = new Database();
          $query = new SelectQuery();
          $conn = $database->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
          }
          else{
            $result = $query->selectIds($conn, $prod_ids_string);
            $database->disconnect($conn);
            if(!$result){
                  echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";      
                }
                else{
                  while($row = mysqli_fetch_assoc($result)){
                    echo "<div class='cartCard'>
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
                              echo "</div>
                                      <form action='";htmlspecialchars($_SERVER['PHP_SELF']); echo"' method='post'>
                                        <input type='hidden' name='prod_id' value='".$row['prod_id']."'>
                                        <input type='submit' class='cartBtn' value='Remove Item'>
                                      </form>
                          </div>";
                  }
                  echo "<div class='cartOrder'><p> Order Total: £" . number_format($total,2) . "</p>";
                      if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                        echo "<p>You must log in to an account to place an order. Click <a class='linkBtn' href='login.php'>HERE</a> to log in.</p></div>";
                      }
                      else{ 
                        echo "<a href='placeOrder.php' class='buyBtn'>Place Order</a>
                        </div>";
                      }
            }
          }
        }
        //Condition to run the displayCart function if session cart array has items present. 
        //Otherwise display cart is empty message.
        if(isset($_SESSION['cart'])&& !empty($_SESSION['cart'])){
          displayCart();
        }
        else{
          echo "<h3>Your cart is empty!</h3>";
        } 
      ?>

    </div>
  </body>
  
  <?php include 'footer.php'?>

</html>