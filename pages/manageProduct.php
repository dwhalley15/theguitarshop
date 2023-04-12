<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] != "staff"){
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
      <div class='form'>
        <h3>Product Management</h3><br><br>
        <a class='confirmBtn' style='margin-left:2%;' href='createProduct.php'>Add New Product</a><br><br>
        <div class='allOrders'>
          <table class='allOrdersTable'>
            <tr id='tableTh'>
              <th><strong>Product Name</strong></th>
              <th><strong>Product Brand</strong></th>
              <th><strong>Edit Product</strong></th>
              <th><strong>Delete Product</strong></th>
            </tr>
            
            <?php
              //PHP code block to display all products from the database.
              include '../classes/database.php';
              include '../classes/selectQuery.php';
              $database = new Database();
              $query = new SelectQuery();
              $conn = $database->connect();
              if(!$conn){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                $result = $query->selectProducts($conn);
                $database->disconnect($conn);
                if(!$result){
                  echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
                }
                else{
                  while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>
                            <td>".$row['name']."</td>
                            <td>".$row['brand']."</td>
                            <td><a class='linkBtn' href='editProduct.php?prod_id=".$row['prod_id']."'>Edit</a></td>
                            <td><a class='linkBtn deleteProdBtn' id='".$row['prod_id']."'>Delete</a></td>
                          </tr>";
                  }
                }
              }
            ?>

          </table>
        </div>
      </div>
      <script src="../jsScripts/manageProduct.js"></script>
  </body>

  <?php include 'footer.php'?>

</html>