<?php
  session_start();
  session_regenerate_id();
?>

<!DOCTYPE>
<html>
  
<?php 
  include 'head.php';
  include 'header.php';
?>

<body>
    <div class="content"> 
      <div class='slideshow'>   
        <div class='slide fade'>
          <img src="../images/guitarslide1.jpg" style="width:100%">
          <div class="slideText">Visit our store</div>
        </div>
        <div class='slide fade'>
          <img src="../images/guitarslide3.jpg" style="width:100%">
          <div class="slideText">Electric</div>
        </div>
        <div class='slide fade'>
          <img src="../images/guitarslide4.jpg" style="width:100%">
          <div class="slideText">Acoustic</div>
        </div>
        <div class='slide fade'>
          <img src="../images/guitarslide2.jpg" style="width:100%">
          <div class="slideText">Parts & Accessories</div>
        </div>
        <div style="text-align:center">
          <span class="slideDot"></span> 
          <span class="slideDot"></span> 
          <span class="slideDot"></span>
          <span class="slideDot"></span>
      </div>
   </div> 
    <script src="../jsScripts/home.js"></script> 
    <div class="content"> 
      <div class="listing">
        <h3 class="onSale">Currently on Sale!</h3><br>
        <?php
          include '../classes/database.php';
          include '../classes/selectQuery.php';
          //A function to display all products from the database where the on_sale is set to yes.
          function displayOnSale(){
            $database = new Database();
            $query = new SelectQuery();
            $conn = $database->connect();
            if(!$conn){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              $result = $query->selectOnSale($conn);
              if(!$result){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                  while($row = mysqli_fetch_assoc($result)){
                           echo "<div class='card'>
                                   <img class='cardImg' src='../uploads/" . $row['image'] ."' alt='" . $row['name'] . "'>
                                   <div class='cardCont'>
                                    <h4><b>" . $row['brand'] . " - " . $row['name'] . "</b></h4>";
                                    if($row['on_sale'] == "yes"){
                                      $salePrice = $row['price'] - $row['sale_reduction'];
                                       echo "<p class='onSale'>ON SALE! £" . number_format($salePrice, 2) . "</p>";
                                    }
                                    else{
                                      echo "<p>£" . number_format($row['price'], 2) . "</p>";
                                     }
                                    echo "<a class='confirmBtn' href='productView.php?prod_id=" . $row["prod_id"] . "'>View</a>
                                  </div>
                                 </div>";         
                              }
                  $database->disconnect($conn);
                }
              }
            }
          //Always runs the displayOnSale function.
          displayOnSale();
        ?>
      </div>
    </div> 
  </body>
      
  <?php include 'footer.php'?>

</html>