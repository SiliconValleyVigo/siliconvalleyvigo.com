<?php

class ServiceDb {
  static public function connect(){
      global $conn;
      if(isset($conn)) {
        return $conn;
      }
      $dbname= DB_NAME;
      $username= DB_USER;
      $password= DB_PASS;
      $servername= DB_HOST;
      $conn = mysqli_connect($servername, $username, $password, $dbname);
      // Check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }
      return $conn;
  }
}