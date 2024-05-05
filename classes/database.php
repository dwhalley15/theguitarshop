<?php

  class Database {

    //A function to connect to the database.
    function connect(){
      $username = getenv("USER_NAME");
      $password = getenv("PASS");
      $host = getenv("HOST");
      $port = getenv("PORT");
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