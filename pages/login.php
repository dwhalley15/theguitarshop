<?php
  session_start();
  session_regenerate_id();
  //A condition to route an authenticated user to account page.
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("location: account.php");
    exit;
  }
    //A post condition to log a user from the database in by confirming email and password match.
    //Then sets a session array with user attribute values and a loggedin boolean.
    if($_SERVER['REQUEST_METHOD'] == "POST"){
      include '../classes/database.php';
      include '../classes/selectQuery.php';
      $email = $_POST['email'];
      $pass = $_POST['password'];
      $login_error = "";
      $database = new Database();
      $select = new SelectQuery();
      $conn = $database->connect();
      if(!$conn){
        $login_error = "Could not connect to the database!";
      }
      else{
        $account = $select->logIn($conn, $email);
        $verified = password_verify($pass, $account['password']);
        $database->disconnect($conn);
        if(empty($account)){
          $login_error = "This account does not exist";
        }
        else if(!$verified){
          $login_error = "Password is incorrect!";
        }
        else{
          $_SESSION['loggedin'] = true;
          $_SESSION['user_id'] = $account['user_id'];
          $_SESSION['name'] = $account['first_name'] . " " . $account['last_name'];
          $_SESSION['role'] = $account['role'];
          header("location: account.php");
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
      <div class="content">
        <form id="multiPageForm" name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <h3>Account Login</h3>
          <fieldset class="form">
            <label for="emailLog">Email Address:</label><span class="errorShown"><?php if(!empty($login_error)) echo "*";?></span>
            <input type="email" name='email' placeholder="Email Address">
            <br>
            <label for="password">Password:</label><span class="errorShown"><?php if(!empty($login_error)) echo "*";?></span>
            <input type="password" name='password' placeholder="Password">
            <a class="linkBtn" href="#newPass">Forgot password?</a>
            <input type='submit'class="confirmBtn" value='Log In'>
            <p class='errorShown'><?php if(!empty($login_error)) echo $login_error;?></p>
            <p>New Customer? <a class="linkBtn" href="createAccount.php">Create an account</a></p>
          </fieldset>
        </form>
      </div>
  </body>

  <?php include 'footer.php'?>

</html>