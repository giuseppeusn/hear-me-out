<?php
  function connect_db() {
    $db_name = "db_teste";
    $db_user = "root";
    $db_pass = "";
    $db_host = "localhost:3306";
    
    $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

    return $connection;
  }
?>