<?php

  class Validation{

    //A function to remove unnecessary whitespace, any backslashes and converts any special characters to avoid malicious scripts.
    function trimStripSpecial($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    //Same as trimStripSpecial but for an array input.
    function trimStripSpecialArr($data){
      foreach($data as $key => $value){
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
      }
      return $data;
    }

    //A function to validate an input length is not over 1000 characters.
    function charLimit($data){
      if(strlen((string)$data) > 1000){
        $data = "";
      }
      return $data;
    }

    //A function to validate an input is a number.
    function isNumber($data){
      if(!preg_match('/^\d+$/', $data)){
        $data = "";
      }
      return $data;
    }

    //A function to validate an image size, extension and if the same image already exists on the server.
    function imageCheck($file, $imageExt, $imageCheck, $file_size){
      $upload = true;
      if($imageCheck == false){
        $upload = false;
      }
      if(file_exists($file)){
          $upload = false;
        }
      if($file_size > 500000){
          $upload = false;
        }
      if($imageExt != 'jpg' && $imageExt != 'png' && $imageExt != 'jpeg' && $imageExt != 'gif'){
          $upload = false;
        }
      return $upload;
    }

    //A function to validate a string has no numbers or special characters.
    function isName($data){
      if(!preg_match("/^[a-zA-Z-' ]*$/", $data)){
          $data = "";
        }
      return $data;
    }

    //A function to Validate an email address is in a valid email address format.
    function isEmail($data){
      if(!filter_var($data, FILTER_VALIDATE_EMAIL)){
          $data = "";
        }
      return $data;
    }

    //A function to validate the password has least 8 characters, upper and lower case letters, and atleast one number.
    //Also confirms two password inputs match.
    function passwordCheck($pass, $cPass){
      $uppercase = preg_match('@[A-Z]@', $pass);
      $lowercase = preg_match('@[a-z]@', $pass);
      $number = preg_match('@[0-9]@', $pass);
      if($pass != $cPass){
        $pass = "";
      }
      else if(!$uppercase || !$lowercase || !$number || strlen($pass) < 8){
        $pass = "";
      }
      return $pass;
    }

  }
  
?>