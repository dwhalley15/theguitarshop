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
    <h3 class="account-title">Welcome <?php echo htmlspecialchars($_SESSION['name'])?> </h3>
      <ul class='orderNav'>
        <li class='orderLi'><strong>Menu</strong></li>
        <li class='orderLi'> <a class='orderA' id='ordersBtn'>Orders</a></li>
        <li class='orderLi'><a class='orderA' id='detailsBtn'>Details</a></li>
        <li class='orderLi'><a class='orderA' id='optionsBtn'>Options</a></li>
      </ul>
    <div class="form">
        <div class='show' id='ordersTab'>
          
         <?php
          //PHP code block to display purchases from the database in this tab based on the user_id.
          include '../classes/database.php';
          include '../classes/selectQuery.php';
          $database = new Database();
          $query = new SelectQuery();
          $user_id = $_SESSION['user_id'];
          $conn = $database->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
          }
          else{
            $count = $query->countOrders($conn, $user_id);
            if($count == 0){
              $database->disconnect($conn);
              echo "<h3>You currently have no orders!</h3>";
            }
            else{
              $result = $query->selectOrders($conn, $user_id);
              $database->disconnect($conn);
              while($row = mysqli_fetch_assoc($result)){
                echo "<div class='cartCard'>
                        <h4>Order Number: <br>". $row['order_id'] ."</h4><br>
                        <div class='cartText'>
                          <p><strong>Order Date:</strong> <br>" . $row['order_date'] . "</p><br><br>
                          <a class='cartBtn' href='editOrder.php?order_id=" . $row['order_id'] . "'>Edit Order</a>
                          <a class='cartBtn deleteOrderBtn' id='".$row['order_id']."'>Cancel Order</a><br><br><br>
                        </div>
                      </div>";
                }
              }
            }
        ?>
        
      </div>
      
      <div class='hidden' id='detailsTab'>
        
        <?php
          //PHP code block to display user details from the database based on user_id.
          $conn = $database->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
          }
          else{
            $row = $query->selectAccountDetails($conn, $user_id);
            $database->disconnect($conn);
            if(!$row){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              echo "<form name='updateAccount' method='post' action='../phpScripts/updateAccount.php' id='updateAccountForm'>
                        <fieldset class='form'>
                          <legend>Update Account</legend>
                          <label for='first_name'>First Name: </label><span class='error'>*</span>
                          <input type='text' name='first_name' value='".$row['first_name']."' maxlength='255' required>
                          <label for='last_name'>Last Name: </label><span class='error'>*</span>
                          <input type='text' name='last_name' value='".$row['last_name']."' maxlength='255' required>
                          <label for='phone_number'>Phone Number: </label><span class='error'>*</span>
                          <input type='tel' name='phone_number' pattern='^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$' maxlength='11' value='".$row['phone_number']."' maxlength='255' required>
                          <label for='street_address'>Street Address: </label><span class='error'>*</span>
                          <input type='text' name='street_address' value='".$row['street_address']."' maxlength='255' required>
                          <label for='town'>Town: </label><span class='error'>*</span>
                          <input type='text' name='town' value='".$row['town']."' maxlength='255' required>
                          <label for='county'>County: </label><span class='error'>*</span>
                          <input type='text' name='county' value='".$row['county']."' maxlength='255' required>
                          <label for='post_code'>Post Code: </label><span class='error'>*</span>
                          <input type='text' name='post_code' maxlength='9' pattern='[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? [0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}' value='".$row['post_code']."' maxlength='255' required>
                          <p class='error' id='formValidation'>Fields marked with * must be completed!</p>
                      </fieldset>
                      <input type='button' id='updateDetails' class='confirmBtn' value='Update Details'>
                    </form>";
            }
          }
        ?>

      </div>
      <div class='hidden' id='optionsTab'>
        <div class='form'>
          <br><br>
          <a class='confirmBtn' href='../phpScripts/logout.php'>Log Out</a><br><br>
          <a class='confirmBtn' href='changePassword.php'>Change Password</a><br><br>
          <?php if($_SESSION['role'] != "customer") echo "<a class='confirmBtn' href='manageProduct.php'>Manage Products</a><br><br>";?>
          <form name='deleteAccount' method='post' action='../phpScripts/deleteAccount.php'>
          <input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
          <a class='confirmBtn' id='deleteAccount'>Delete Account</a></form><br><br>
        </div>
      </div>
    </div>
    <script src="../jsScripts/account.js"></script>
  </body>
  
  <?php include 'footer.php'?>

</html>