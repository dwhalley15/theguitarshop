<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
    exit;
  }
    //A post condition to update a users password in the database based on user_id.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
      include '../classes/database.php';
      include '../classes/updateQuery.php';
      include '../classes/validation.php';
      $database = new Database();
      $update = new UpdateQuery();
      $validate = new Validation();
      $user_id = $_SESSION['user_id'];
      $pass = $_POST['password'];
      $cPass = $_POST['passwordCon'];
      $pass = $validate->passwordCheck($pass, $cPass);
      $pass = $validate->trimStripSpecial($pass);
      if(!empty($pass)){
        $conn = $database->connect();
          if(!$conn){
            $login_error = "Could not connect to the database!";
          }
          else{
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $complete = $update->updatePassword($conn, $pass, $user_id);
            if($complete != true){
              $login_error = "Password was not updated. Please try again.";
            }
            else{
              header("location: account.php");
              exit;
            }
        }
      }
      else{
        $login_error = "Password was not confirmed. Please try again.";
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
    <div class="content">
      <form id="multiPageForm" name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h3>Change Password</h3>
        <fieldset class="form">
          <label for="password">Password:</label><span class="errorShown"><?php if(!empty($login_error)) echo "*";?></span>
          <input type="password" name='password' placeholder="Password">
          <label for="passwordCon">Confirm Password:</label><span class="errorShown"><?php if(!empty($login_error)) echo "*";?></span>
          <input type="password" name='passwordCon' placeholder="Confirm Password">
          <input type='submit'class="confirmBtn" value='Change Password'>
          <p class='errorShown'><?php if(!empty($login_error)) echo $login_error;?></p>
        </fieldset>
      </form>
    </div>
  </body>
  
  <?php include 'footer.php'?>

</html>