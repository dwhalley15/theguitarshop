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
    <div class ="form">
    
      <?php
        //A post condition to add a user to the database.
        if(!empty($_POST) && isset($_POST)){
          //Define all variables from POST request
          $firstName = $_POST["firstName"];
          $lastName = $_POST["lastName"];
          $email = $_POST["emailAdd"];
          $pNum = $_POST['pNum'];
          $sAdd = $_POST['sAdd'];
          $tAdd = $_POST['tAdd'];
          $cAdd = $_POST['cAdd'];
          $pCode = $_POST['pCode'];
          $firstPass = $_POST["passwordFirst"];
          $conPass = $_POST["passwordConfirm"];
          $role = "customer";
          //Set validation check boolean
          $formComplete = true;
          //Calls classes
          include "../classes/database.php";
          include "../classes/insertQuery.php";
          include "../classes/validation.php";
          $database = new Database();
          $insert = new InsertQuery();
          $validate = new Validation();
          //Checks the variables that should contain only letters or whitespace.
          $firstName = $validate->isName($firstName);
          $lastName = $validate->isName($lastName);
          $tAdd = $validate->isName($tAdd);
          $cAdd = $validate->isName($cAdd);     
          //Checks the email variable is a valid email address.
          $email = $validate->isEmail($email);
          //Checks the telephone number is a valid 11 digit telephone number.
          $pNum = $validate->isNumber($pNum);
          //Checks the passwords match and is correct to the format.
          $firstPass = $validate->passwordCheck($firstPass, $conPass);
          //Put all vairables into a multidimensional array.
          $newAccount = array(
              "first_name" => $firstName,
              "last_name" => $lastName,  
              "email" => $email,
              "phone_number" => $pNum,
              "street_address" => $sAdd,
              "town" => $tAdd,
              "county" => $cAdd,
              "post_code" => $pCode,
              "role" => $role,
              "password" => $firstPass
            );
          //Strips unnecessary whitespace, removes any backslashes and changes any special characters to avoid malicious scripts.
          $newAccount = $validate->trimStripSpecialArr($newAccount);
          //Loop checks all values in array to see if any are considered empty or over 255 characters ("" or 0).
          foreach($newAccount as $key => $value){
              if(empty($value)){
                echo "<p class='errorShown'>" . $key . " was not entered correctly!</p>";
                $formComplete = false;
              }
              elseif(strlen((string)$value) > 255){
                echo "<p class='errorShown'>" . $key . " was too long to store!</p>";
                $formComplete = false;
              }
          }
          //Hashes the password before storage.
          $newAccount['password'] = password_hash($firstPass, PASSWORD_DEFAULT);
          //Once all checks are complete either confirms account creation or shows an error to return to account creation.
          if($formComplete === true){
            $conn = $database->connect();
            if(!$conn){
              echo "<h3>OOPS! Something went wrong!</h3><br><p class='errorShown'>Could not connect to database.</p>";
            }
            else{
              $count = $insert->duplicateUser($conn, $email);
              if($count == 0){
                $userId = $insert->insertUser($conn, $newAccount);
                $database->disconnect($conn);
                if(!empty($userId)){
                  echo "<h3>Congratulations " . $firstName . "! </h3><br>
                        <p>Your account has been created successfully!</p><br>
                        <p>Click <a class-'linkBtn' href='login.php'>here</a> to log in.</p>";
                  }
                  else{
                    echo "<h3>OOPS! Something went wrong!</h3>
                          <p class='errorShown'>Account was not created!</p><br>
                          <p> Please return to <a class='linkBtn' href='createAccount.php'>create new account</a>.</p>";
                  }
                
              }
              else{
                echo "<h3>OOPS! Something went wrong!</h3>
                      <p class='errorShown'>This account already exists!</p>
                      <p> Please return to <a class='linkBtn' href='createAccount.php'>create new account</a>.</p>";
                }
              }
          }
          else{
            echo "<h3>OOPS! Something went wrong!</h3>
                  <p>I am sorry. Due to the above errors the account was not created!</p> <br>
                  <p> Please return to <a class='linkBtn' href='createAccount.php'>create new account</a>.</p>";
          }   
        }
      ?>

    </div>
  </body>

  <?php include 'footer.php'?>

</html>