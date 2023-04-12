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
      <form class="form" name="searchForm" action="search.php" method="get">
            <input type="text" name="search" placeholder="Search...">
            <input type="submit" class="confirmBtn" alt="Search" name="searchSubmit" value="Search">
          </form>

      <?php
        include '../classes/database.php';
        include '../classes/selectQuery.php';
        include '../classes/validation.php';
        //A function to display products from the database based on a search term.
        function displaySearch($search, $offset, $total_products_per_page){
          $database = new Database();
          $query = new SelectQuery();
          $conn = $database->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
          }
          else{
            $result = $query->selectSearch($conn, $search, $offset, $total_products_per_page);
            $total_products = $query->searchCount($conn, $search);
            echo "<h3>Number of Search Results: ".$total_products['total_products']."</h3>";
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
                  return $total_products;
                }
          }
        }
        //A condition to run the displaySearch and pagination functions only when a search term is present.
        if(!empty($_POST['search']) || !empty($_GET['search'])){
                $validate = new Validation();
                if (isset($_GET['page_no']) && !empty($_GET['page_no'])){
                  $page_no = $validate->trimStripSpecial($_GET['page_no']);
                }
                else{
                  $page_no = 1;
                }
                if(!empty($_GET['search'])){
                  $search = $validate->trimStripSpecial($_GET['search']);
                }
                elseif(!empty($_POST['search'])){
                  $search = $validate->trimStripSpecial($_POST['search']);
                }
                $total_products_per_page = 8;    
                $offset = ($page_no - 1) * $total_products_per_page;
                $previous_page = $page_no - 1;
                $next_page = $page_no + 1;
                $total_products = displaySearch($search, $offset, $total_products_per_page);
                $total_products = $total_products['total_products'];
                $total_pages = ceil($total_products / $total_products_per_page);
        }
        else{
          echo "<h3>No search results!</h3>";
        }
      ?>

      <div class="pages">
            <strong>Page <?php echo $page_no." of ".$total_pages; ?></strong><br>
            <tr class="pagination">
                <?php if($page_no > 1){
                    echo "<th><a class='arrow' href='?page_no=1&search=" . $search . "'>&#8617;</a></th>";
                } ?>

                <th <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
                    <a class='arrow' <?php if($page_no > 1){
                        echo "href='?page_no=" . $previous_page . "&search=" . $search . "'";
                    } ?>>&#8592;</a>
                </th>

                <th <?php if($page_no >= $total_pages){
                    echo "class='disabled'";
                } ?>>
                    <a class='arrow' <?php if($page_no < $total_pages) {
                        echo "href='?page_no=" . $next_page . "&search=" . $search . "'";
                    } ?>>&#8594;</a>
                </th>

                <?php if($page_no < $total_pages){
                    echo "<th><a class='arrow'  href='?page_no=" . $total_pages . "&search=" . $search . "'>&#8618;</a></th>";
                } ?>
            </tr>
        </div>
    </div>
  </body>

  <?php include 'footer.php'?>

</html>