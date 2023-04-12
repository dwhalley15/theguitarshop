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
    <div class="content" id="multiPageForm">
      <form action="newProduct.php" method="post" name="newProductForm" id="newProductForm" enctype="multipart/form-data">
        <h3>Add New Product</h3>
        <div class="show" id="tabOne">
          <fieldset class="form">
            <legend>Product Description</legend>
            <label for="productName">Name:</label><p class="error">*</p>
            <input type="text" id="productName" name="productName" placeholder="Product Name" maxlength="255" value="<?php if(isset($_POST['productName'])) echo $_POST['productName']; ?>" required>
            <br>
            <label for="productBrand">Brand:</label><p class="error">*</p>
            <input type="text" id="productBrand" name="productBrand" placeholder="Product Brand" maxlength="255" value="<?php if(isset($_POST['productBrand'])) echo $_POST['productBrand']; ?>" required>
            <br>
            <label for="productDesc">Description:</label><p class="error">*</p>
            <textarea id="productDesc" name="productDesc" placeholder="Product Description" maxlength="1000" value="<?php if(isset($_POST['productDesc'])) echo $_POST['productDesc']; ?>" required></textarea>
            <br>
            <p class="error" id="formValidation">Fields marked with * must be completed!</p>
          </fieldset>
        </div>
        <div class="hidden" id="tabTwo">
          <fieldset class="form">
            <legend>Product Pricing</legend>
            <label for="productPrice">Price:</label><p class="error">*</p>
            <input type="number" step="0.01" id="productPrice" name="productPrice" placeholder="0.00" maxlength="255" value="<?php if(isset($_POST['productPrice'])) echo $_POST['productPrice']; ?>" required>
            <br>
            <br>
            <label for="onSale">On sale:</label>
            <br>
            <br>
            <label class="switch">
            <input type="checkbox" id="onSale" name="onSale">
            <span class="slider"></span>
            </label>
            <br>
            <br>
            <label id="saleRedLabel" name="saleRedLabel" for="saleReduction">Reduction Amount:</label><p class="error">*</p>
            <input type="number" step="0.01" id="saleReduction" name="saleReduction" placeholder="0.00"  maxlength="255"value="<?php if(isset($_POST['saleReduction'])) echo $_POST['saleReduction']; ?>" required>
            <br>
            <p class="error" id="formValidation">Fields marked with * must be completed!</p>
          </fieldset>
        </div>
        <div class="hidden" id="tabThree">
          <fieldset class="form">
            <legend>Product Details</legend>
            <label for="productSKU">SKU:</label><p class="error">*</p>
            <input type="number" id="productSKU" name="productSKU" placeholder="0" maxlength="255" value="<?php if(isset($_POST['productSKU'])) echo $_POST['productSKU']; ?>" required>
            <br>
            <label for="productStock">Stock:</label><p class="error">*</p>
            <input type="number" id="productStock" name="productStock" placeholder="0" maxlength="255" value="<?php if(isset($_POST['productStock'])) echo $_POST['productStock']; ?>" required>
            <br>
            <label for="productImage">Image:</label><p class="error">*</p>
            <input type="file" class="confirmBtn" id="productImage" name="productImage" accept="image/*" required>
            <div id="imagePreview"></div>
            <br>
            <label for="productType">Type:</label><p class="error">*</p>
            <select id="productType" name="productType" required>
              <option class="option" value ="none">Select Product Type...</option>
              <option class="option" value ="electric">Electric</option>
              <option class="option" value ="acoustic">Acoustic</option>
              <option class="option" value ="amplifier">Amplifier</option>
              <option class="option" value ="strings">Strings</option>
              <option class="option" value ="part">Part</option>
              <option class="option" value ="accessory">Accessory</option>
            </select>
            <br>
            <p class="error" id="formValidation">Fields marked with * must be completed!</p>
          </fieldset>
        </div>   
        <div>
          <input type="button" id="prevBtn" name="prevBtn" class="confirmBtn" value="Previous">
          <input type="button" id="nextBtn" name="nextBtn" class="confirmBtn" value="Next">
          <input type="button" id="submitBtn" name="submitBtn" class="confirmBtn" value="Submit">
      </div>
      </form>
      <div style="text-align:center;margin-top:40px;">
        <span class="step" id="stepOne"></span>
        <span class="step" id="stepTwo"></span>
        <span class="step" id="stepThree"></span>
      </div> 
      <br>
    </div>
    <script src="../jsScripts/addProduct.js"></script> 
  </body>

  <?php include 'footer.php'?>

</html>