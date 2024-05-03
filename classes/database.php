<?php

  class Database {

    //A function to connect to the database.
    function connect(){
      $username = "your_username_here";
      $password = "your_password_here";
      $host = "your_hostame_here";
      $port = 0000;
      $database = $username;
      $conn = new mysqli();
      $conn->init();
      if(!$conn){
        return "";
      }
      else{
        $conn->ssl_set(NULL, NULL, NULL, '/public_html/sys_tests', NULL);
        $conn->real_connect($host, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
        return $conn;    
      }
    }

    //A function to disconnect from the database.
    function disconnect($conn){
        $conn->close();
    }
    
  }

?>