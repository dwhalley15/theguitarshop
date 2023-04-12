<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] != "staff"){
    header("location: login.php");
    exit;
  }
  //A post condition that updates the image attribute for a product in the database based on prod_id.
  include '../classes/database.php';
  include '../classes/selectQuery.php';
  include '../classes/updateQuery.php';
  include "../classes/validation.php";
  $database = new Database();
  $select = new SelectQuery();
  $update = new UpdateQuery();
  $validate = new Validation();
  if(!empty($_POST['hidden']) && isset($_POST)){
    $prod_id = $_GET['prod_id'];
    $image = $_POST['image'];
    $msg = "";
    //Set up variables for image upload.
    $path = "../uploads/";
    $file = $path . basename($_FILES['image']['name']);
    $imageExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $imageCheck = getimagesize($_FILES['image']['tmp_name']);
    $file_size = $_FILES['image']['size'];
    //Performs all checks on an image file.
    $upload = $validate->imageCheck($file, $imageExt, $imageCheck, $file_size);
    if($upload != true){
      $msg = "Invalid image file please try again!";
    }
    else{
      $conn = $database->connect();
      
      if(!$conn){
        $msg = "Could not connect to the database!";
      }
      else{ 
        //Removes old image related to this product.
        $row = $select->selectId($conn, $prod_id);
        $old_image = $row['image'];
        $old_image_path = "../uploads/".$old_image;
        unlink($old_image_path);    
        //Uploads new image.
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
        $complete = $update->updateImage($conn, $image, $prod_id);
        $database->disconnect($conn);
        if($complete != true){
          $msg = "Failed to update image please try again and change image file name!";
        }
        else{  
          header("location: editProduct.php?prod_id=$prod_id");
          exit;
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html>
  
  <?php 
    include 'head.php';
    include 'header.php';
  ?>

  <body>
    <div class="form">
      <h3>Change Product Image</h3>
        
      <?php
        //PHP code block to display update image options only if a prod_id is present.
        if(!empty($_GET['prod_id']) && isset($_GET['prod_id'])){
          echo "<form enctype='multipart/form-data' method='post' name='editImageForm' action='editImage.php?prod_id=";echo $_GET['prod_id']; echo"'>
                  <input type='hidden' name='hidden' value='hidden'>
                  <input type='file' class='confirmBtn' id='image' name='image' accept='image/*'><br><br>
                  <input type='button' class='confirmBtn' id='editImageBtn' value='Change Image'><br><br>
                  <a href='editProduct.php?prod_id=";echo $_GET['prod_id']; echo"' class='confirmBtn' style='margin-left:40px;'>Cancel</a>
                </form>";
        }
        else{
          echo "<p class='errorShown'>No product selected!</p>";
        }
      ?>

      <p><?php if(!empty($msg)) echo $msg;?>
    </div>
    <script src="../jsScripts/editImage.js"></script> 
  </body>
  
  <?php include 'footer.php'?>

</html>