<?php
  session_start();
  session_regenerate_id();
  //A condition to route an unauthenticated user to the login page.
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: ../pages/login.php");
    exit;
  }
  /*A script to update most attributes in user account in the database based on user_id. 
    Once completed routes the user to the account page.*/
  if(!empty($_POST) && isset($_POST)){
    //Define all variables from POST request
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST['phone_number'];
    $street_address = $_POST['street_address'];
    $town = $_POST['town'];
    $county = $_POST['county'];
    $post_code = $_POST['post_code'];
    //Set validation check boolean
    $formComplete = true;
    //Calls classes
    include "../classes/database.php";
    include "../classes/updateQuery.php";
    include "../classes/validation.php";
    $database = new Database();
    $update = new UpdateQuery();
    $validate = new Validation();
    //Checks the variables that should contain only letters or whitespace.
    $first_name = $validate->isName($first_name);
    $last_name = $validate->isName($last_name);
    $town = $validate->isName($town);
    $county = $validate->isName($county);
    //Checks the telephone number is a valid 11 digit telephone number.
    $phone_number = $validate->isNumber($phone_number);
    //Put all variables into a multidimensional array.
    $update_account = array(
      "user_id"=>$user_id,
      "first_name"=>$first_name,
      "last_name"=>$last_name,
      "phone_number"=>$phone_number,
      "street_address"=>$street_address,
      "town"=>$town,
      "county"=>$county,
      "post_code"=>$post_code
    );
    //Strips unnecessary whitespace, removes any backslashes and changes any special characters to avoid malicious scripts.
    $update_account = $validate->trimStripSpecialArr($update_account);
    //Loop checks all values in array to see if any are considered empty or over 255 characters ("" or 0).
    foreach($update_account as $key => $value){
      if(empty($value)){
        $formComplete = false;
      }
      elseif(strlen((string)$value) > 255){
        $formComplete = false;
      }
    }
    //Once all checks are complete either confirms account creation or shows an error to return to account creation.
    if($formComplete === true){
      $conn = $database->connect();
      if(!$conn){
        header("location: ../pages/account.php");
        exit;
      }
      else{
        $update->updateAccount($conn, $update_account);
        header("location: ../pages/account.php");
        exit;
      }
    }
    else{
      header("location: ../pages/account.php");
      exit;
    }
  }
  else{
    header("location: ../pages/account.php");
    exit;
  }