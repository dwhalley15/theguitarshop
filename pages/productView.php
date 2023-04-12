<?php
  session_start();
  session_regenerate_id();
?>

<!DOCTYPE html>
<html>
  
  <?php 
    include 'head.php';
    include 'header.php';
  ?>

    <body>
    <div class="content">
      <div class="productView">
        
        <?php

          include '../classes/database.php';
          include '../classes/selectQuery.php';
          //A function to displays a product from the database based on prod_id.
          function displayProduct(){     
            $database = new Database();
            $query = new SelectQuery();
            $prod_id = $_GET['prod_id'];
            $conn = $database->connect();
            if(!$conn){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              $row = $query->selectId($conn, $prod_id);
              if(!$row){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                echo  "<div class='prodCont'>
                            <img class='viewImg' src='../uploads/" . $row['image'] ."' alt='" . $row['name'] . "'>
                            <div class='viewText'>
                              <h3>" . $row['brand'] . " - " . $row['name'] . "</h3><br>";
                              if($row['on_sale'] == "yes"){
                                  $salePrice = $row['price'] - $row['sale_reduction'];
                                  echo "<p class='line'>£" . number_format($row['price'], 2) . "</p>
                                        <p class='onSale'>Now only £" . number_format($salePrice, 2) . " that is a saving of £" . number_format($row['sale_reduction'], 2) ."!</p><br>";
                              }
                              else{
                                  echo "<p>£" . number_format($row['price'], 2) . "</p><br>";
                                }
                              if($row['stock'] == 'none'){
                                  echo "<p class='oos'>Out of Stock</p>";
                              }
                              else{
                                  echo "<p>" . $row['stock'] . " Available</p><br>
                                        <form action='../phpScripts/addToCart.php' method='post'>
                                          <input type='hidden' name='prod_id' value='".$row['prod_id']."'>
                                          <input type='number' name='quantity' value='1' min='1' max='".$row['stock']."' placeholder='quantity' required><br>
                                          <input type='submit' class='buyBtn' value='Add to Cart'><br><br>
                                        </form>";
                              }
                            echo "<p>SKU: " . $row['sku'] . "</p>
                                  <p class='viewDesc'>" . $row['description'] . "</p>
                                </div>
                          </div>";
                $database->disconnect($conn);
              }
            }
          }
          //A condition to run displayProduct only when a prod_id is present.
          if(!empty($_GET['prod_id']) && isset($_GET['prod_id'])){
            displayProduct();
          }
          else{
            echo "<p class='errorShown'>No product selected!</p>";
          }
        ?>

      </div>
    </div>
  </body>

  <?php include 'footer.php'?>

</html>