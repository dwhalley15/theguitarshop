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
      <ul class='orderNav'>
        <li class='orderLi'><strong>Sort By</strong></li>
        <li class='orderLi'><a class='orderA' href='productListing.php?type=<?php echo $_GET['type']?>&order_by=name'>Model</a></li>
        <li class='orderLi'><a class='orderA' href='productListing.php?type=<?php echo $_GET['type']?>&order_by=price'>Price</a></li>
        <li class='orderLi'><a class='orderA' href='productListing.php?type=<?php echo $_GET['type']?>&order_by=brand'>Brand</a></li>
      </ul> 
      <div class="listing">

      <?php
        //PHP code block to display products from the database based on prod_id.
        //Also uses pagination and order by display products.
        include '../classes/database.php';
        include '../classes/selectQuery.php';
        $database = new Database();
        $query = new SelectQuery();
        //Sets the values for pagination.
        if (isset($_GET['page_no']) && !empty($_GET['page_no'])){
          $page_no = $_GET['page_no'];
        }
        else{
          $page_no = 1;
        } 
        $total_products_per_page = 8;
        $offset = ($page_no - 1) * $total_products_per_page;
        $previous_page = $page_no - 1;
        $next_page = $page_no + 1;
        //Sets the values for getting the data based on the type att. and ordering by another att. if applicable.
        //Completed Mysql query based on applicable inputs.
        $conn = $database->connect();
        if(!$conn){
          echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
        }
        else{
          if(!empty($_GET['type']) && !empty($_GET['order_by'])){
            $productType = $_GET['type'];
            $orderBy = $_GET['order_by'];
            $count = $query->typeCount($conn, $productType);
            $result = $query->selectType($conn, $productType, $offset, $total_products_per_page, $orderBy);
          }
          elseif(!empty($_GET['type'])){
            $productType = $_GET['type'];
            $count = $query->typeCount($conn, $productType);
            $result = $query->selectType($conn, $productType, $offset, $total_products_per_page);
          }
          else{
            $count = $query->countAll($conn);
            $result = $query->selectAllProducts($conn, $offset, $total_products_per_page);
          }
          $total_products = mysqli_fetch_array($count);
          $total_products = $total_products['total_products'];
          $total_pages = ceil($total_products / $total_products_per_page); 
          //Loops through all results of the Mysql query results and displays them onscreen              
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
      ?>
        <div class="pages">
            <strong>Page <?php echo $page_no." of ".$total_pages; ?></strong><br>
            <tr class="pagination">
                <?php if($page_no > 1){
                    echo "<th><a class='arrow' href='?page_no=1&type=" . $productType . "&order_by=" . $orderBy . "'>&#8617;</a></th>";
                } ?>

                <th <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
                    <a class='arrow' <?php if($page_no > 1){
                        echo "href='?page_no=" . $previous_page . "&type=" . $productType . "&order_by=" . $orderBy . "'";
                    } ?>>&#8592;</a>
                </th>

                <th <?php if($page_no >= $total_pages){
                    echo "class='disabled'";
                } ?>>
                    <a class='arrow' <?php if($page_no < $total_pages) {
                        echo "href='?page_no=" . $next_page . "&type=" . $productType . "&order_by=" . $orderBy . "'";
                    } ?>>&#8594;</a>
                </th>

                <?php if($page_no < $total_pages){
                    echo "<th><a class='arrow'  href='?page_no=" . $total_pages . "&type=" . $productType . "&order_by=" . $orderBy . "'>&#8618;</a></th>";
                } ?>
        
            </tr>
        </div>
      </div>
  </body>

  <?php include 'footer.php'?>

</html>