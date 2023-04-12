<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] != "staff"){
    header("location: login.php");
    exit;
  }

  //A post condition to update a product in the database based on prod_id.
  include '../classes/database.php';
  include '../classes/selectQuery.php';
  include '../classes/updateQuery.php';
  include "../classes/validation.php";
  $database = new Database();
  $update = new UpdateQuery();
  $validate = new Validation();
  if(!empty($_POST) && isset($_POST)){
    //Define all variables from POST request. Additionally sorts out any 0 or empty values that are required.
    $prod_id = $_GET['prod_id'];
    $name = $_POST["name"];
    $desc = $_POST["description"];
    $brand = $_POST["brand"];
    $price = $_POST["price"];
    $sku = $_POST["sku"];
    $stock = $_POST["stock"];
    if($stock == 0){
      $stock ="none";
    }
    $type = $_POST["type"];
    $onSale = "no";
    $saleRed = "none";
    if (isset($_POST["on_sale"])){
      $onSale = "yes";
      $saleRed = $_POST["saleReduction"];
    }
    $msg = "";
    //Set validation check boolean.
    $formComplete = true;
    //Check that the length of the description att. does not exceed 1000.
    $desc = $validate->charLimit($desc);  
    //Checks that the product brand name have no numbers or characters.
    $brand = $validate->isName($brand);    
    //Checks the sku att. is a number.
    $sku = $validate->isNumber($sku);
    //Add all variables into a multidimensional array.
    $updateProduct = array(
      "prod_id"=>$prod_id,
      "name" => $name,
      "description" => $desc,
      "brand" => $brand,
      "price" => $price,
      "sku" => $sku,
      "stock" => $stock, 
      "type" => $type,
      "on_sale" => $onSale,
      "sale_reduction" => $saleRed
    );
    //Strips unnecessary whitespace, removes any backslashes and changes any special characters to avoid malicious scripts.
    $updateProduct = $validate->trimStripSpecialArr($updateProduct);
    //Loop checks all values in array to see if any are considered empty or too large("", 0 or over 255 characters).
    //Any values that are considerred empty or too large have failed validation.
    foreach($updateProduct as $key => $value){
      if(empty($value)){     
        $formComplete = false;
      }
      elseif(strlen((string)$value) > 255){
        if($key != "description"){
        $formComplete = false;
        }
      }
    }
    //Updates database if all inputs are validated.
    if($formComplete === true){
      $conn = $database->connect();
      if(!$conn){
        $msg = "Could not connect to the database.";
      }
      else{
        $complete = $update->updateProduct($conn, $updateProduct);
        if($complete != true){
          $msg = "Failed to update product";
        }
        else{
          header("Refresh: 0");
        }
      }
    }
    else{
      $msg = "An input was not entered correctly.";
    }
    $database->disconnect($conn);
  }
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
          //A function to displays a products details from the database based on prod_id.
          function displayProduct(){     
            $db = new Database();
            $query = new SelectQuery();
            $prod_id = $_GET['prod_id'];
            $conn = $db->connect();
            if(!$conn){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              $row = $query->selectId($conn, $prod_id);
              $db->disconnect($conn);
              if(!$row){
                echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
              }
              else{
                echo  "<div class='prodCont'>
                        <form class='form prodCont' name='editProduct' method='post' action='";htmlspecialchars($_SERVER['PHP_SELF']); echo"'>
                          <div class'viewImg'>
                            <img class='viewImg' src='../uploads/" . $row['image'] ."' alt='" . $row['name'] . "'>
                            <a class='confirmBtn' href='editImage.php?prod_id=".$row['prod_id']."'>Change Image</a><br><br>
                            <label for='type'>Product Type: </label><p class='error'>*</p>
                            <select id='productType' name='type' required>
                              <option class='option' value ='electric' "; if($row['type'] == 'electric'){echo "selected";} echo ">Electric</option>
                              <option class='option' value ='acoustic' "; if($row['type'] == 'acoustic'){echo "selected";} echo ">Acoustic</option>
                              <option class='option' value ='amplifier' "; if($row['type'] == 'amplifier'){echo "selected";} echo ">Amplifier</option>
                              <option class='option' value ='strings' "; if($row['type'] == 'strings'){echo "selected";} echo ">Strings</option>
                              <option class='option' value ='part' "; if($row['type'] == 'part'){echo "selected";} echo ">Part</option>
                              <option class='option' value ='accessory' "; if($row['type'] == 'accessory'){echo "selected";} echo ">Accessory</option>
                            </select>
                          </div>
                          <div class='viewText'>
                            <label for='name'>Product Name: </label><p class='error'>*</p>
                            <input type='text' name='name' maxlength='255' value='".$row['name']."' required>
                            <label for='brand'>Product Brand: </label><p class='error'>*</p>
                            <input type='text' name='brand' maxlength='255' value='".$row['brand']."' required>
                            <label for='brand'>Product Description: </label><p class='error'>*</p>
                            <textarea id='productDesc' name='description' maxlength='1000' required>".$row['description']."</textarea>
                            <label for='price'>Product Price: </label><p class='error'>*</p>
                            <input type='number' step='0.01' name='price' placeholder='0.00' maxlength='255' value='".$row['price']."' required><p class='error'>*</p>
                            <label for='price'>On Sale: </label><br><br>
                            <label class='switch'>
                            <input type='checkbox' id='onSale' name='on_sale'";if($row['on_sale'] == "yes"){echo 'checked';} echo ">
                            <span class='slider'></span>
                            </label><br><br>
                            <label id='saleRedLabel' name='saleRedLabel' for='saleReduction'>Reduction Amount:</label><p class='error'>*</p>
                            <input type='number' step='0.01' id='saleReduction' name='saleReduction' placeholder='0.00'  maxlength='255' value='".$row['sale_reduction']."' required>
                            <label for='sku'>SKU: </label><p class='error'>*</p>                              
                            <input type='number' name='sku' placeholder='0' maxlength='255' value='".$row['sku']."' required>
                            <label for='stock'>Stock: </label><p class='error'>*</p>
                            <input type='number' name='stock' placeholder='0' maxlength='255' value='".$row['stock']."' required><br><br><p class='error' id='formValidation'>Fields marked with * must be completed!</p>
                            <p>";if(!empty($msg)){echo $msg;} echo "</p>
                            <input type='button' id='editProductBtn' class='confirmBtn' value='Update Product'><br><br>
                            </form>
                          </div>
                        </div>";
              }
            }
          }
          //A condition to run the displayProduct function only if a prod_id is present.
          if(!empty($_GET['prod_id']) && isset($_GET['prod_id'])){
            displayProduct();
          }
          else{
            echo "<p class='errorShown'>No product selected!</p>";
          }
        ?>

      </div>
    </div>
    <script src="../jsScripts/editProduct.js"></script> 
  </body>

  <?php include 'footer.php'?>

</html>