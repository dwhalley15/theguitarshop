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
    <div class ="form">
      
      <?php
      //A post condition to add a new product to the database.
      if(!empty($_POST) && isset($_POST)){
        //Define all variables from POST request. Additionally sorts out any 0 or empty values that are required.
        $name = $_POST["productName"];
        $desc = $_POST["productDesc"];
        $brand = $_POST["productBrand"];
        $price = $_POST["productPrice"];
        $sku = $_POST["productSKU"];
        $stock = $_POST["productStock"];
        if($stock == 0){
          $stock ="none";
        }
        $image = $_POST["productImage"];
        $type = $_POST["productType"];
        if($type == "none"){
          $type = "";
        }
        $onSale = "no";
        $saleRed = "none";
        if (isset($_POST["onSale"])){
          $onSale = "yes";
          $saleRed = $_POST["saleReduction"];
        }
        //Set validation check boolean.
        $formComplete = true;
        //Calls classes
        include "../classes/database.php";
        include "../classes/insertQuery.php";
        include "../classes/validation.php";
        $database = new Database();
        $insert = new InsertQuery();
        $validate = new Validation();
        //Check that the length of the description att. does not exceed 1000.
        $desc = $validate->charLimit($desc);       
        //Checks that the product brand name have no numbers or characters.
        $brand = $validate->isName($brand);         
        //Checks the sku att. is a number.
        $sku = $validate->isNumber($sku);
        //Set up variables for image upload.
        $path = "../uploads/";
        $file = $path . basename($_FILES['productImage']['name']);
        $imageExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $imageCheck = getimagesize($_FILES['productImage']['tmp_name']);
        $file_size = $_FILES['productImage']['size'];
        //Performs all checks on an image file.
        $upload = $validate->imageCheck($file, $imageExt, $imageCheck, $file_size);
        //If all upload checks are ok upload the image.
        if($upload == false){
          $image = "";
        }
        else{
          $image = $_FILES['productImage']['name'];
          move_uploaded_file($_FILES['productImage']['tmp_name'], $file);
        }
        //Add all variables into a multidimensional array.
        $newProduct = array(
          "name" => $name,
          "description" => $desc,
          "brand" => $brand,
          "price" => $price,
          "sku" => $sku,
          "stock" => $stock,
          "image" => $image,
          "type" => $type,
          "on_sale" => $onSale,
          "sale_reduction" => $saleRed
        );
        //Strips unnecessary whitespace, removes any backslashes and changes any special characters to avoid malicious scripts.
        $newProduct = $validate->trimStripSpecialArr($newProduct);
        //Loop checks all values in array to see if any are considered empty or too large("", 0 or over 255 characters).
        //Any values that are considerred empty or too large have failed validation.
        foreach($newProduct as $key => $value){
          if(empty($value)){
            echo "<p class='errorShown'>" . $key . " was not entered correctly!</p>";
            $formComplete = false;
          }
          elseif(strlen((string)$value) > 255){
            if($key != "description"){
              echo "<p class='errorShown'>" . $key . " was too long to store!</p>";
              $formComplete = false;
            }
          }
        }
        //Once all checks are complete either confirms product creation or shows what att. had errors and prompts to return to product creation.
        if($formComplete === true){
          $conn = $database->connect();
          if(!$conn){
            echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
          }
          else{
            $count = $insert->duplicate($conn, $name, $brand);
            if($count == 0){
              $prodId = $insert->insertProduct($conn, $newProduct);
              $database->disconnect($conn);
              if(!empty($prodId)){
                echo "<h3>Congratulations!</h3><br>
                      <p>The product " . $brand . " - " . $name . " has been added successfully!</p><br>
                      <a class='confirmBtn' href='productView.php?prod_id=" . $prodId . "'>View</a><br><br>
                      <a class='confirmBtn' href='editProduct.php?prod_id=" . $prodId . "'>Edit</a><br><br>
                      <a class='confirmBtn' href='createProduct.php'>Create New</a>";
              }
                else{
                  echo "<h3>OOPS! Something went wrong!</h3>
                        <p class='errorShown'>Product was not added to the database!</p><br>
                        <p> Please return to <a class='linkBtn' href='createProduct.php'>add new product</a>.</p>";
                }              
            }
            else{
              echo "<h3>OOPS! Something went wrong!</h3>
                    <p class='errorShown'>This product already exists!</p>
                    <p> Please return to <a class='linkBtn' href='createProduct.php'>add new product</a>.</p>";
            }
          }     
        }
        else{
          echo "<h3>OOPS! Something went wrong!</h3>
                <p>I am sorry. Due to the above errors the product has <strong>not</strong> been created.</p> <br>
                <p> Please return to <a class='linkBtn' href='createProduct.php'>add new product</a>.</p>";
        }
      }
      else{
        echo "<h3>OOPS! Something went wrong!</h3>
              <p class='errorShown'>No product added!</p>
              <p> Please return to <a class='linkBtn' href='createProduct.php'>add new product</a>.</p>";
      }
      ?>

    </div>
  </body>
  
  <?php include 'footer.php'?>

</html>