<?php
function connect_db()
{
  $db_name = "hear_me_out";
  $db_user = "root";
  $db_pass = "";
  $db_host = "localhost:3307";

  $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

  return $connection;
}
?>
