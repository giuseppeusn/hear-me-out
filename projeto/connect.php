<?php
function connect_db()
{
  $db_name = "hear_me_out";
  $db_user = "root";
  $db_pass = "";
  $db_host = "localhost:3307";
  try {
    $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
  } catch (mysqli_sql_exception $a) {
    echo "<h4 class='text-danger text-center'>Erro na conexão com o banco de dados:</h4>" . "<p class='text-white px-2 text-center'>" . $a->getMessage() . "</p>";
    die;
  }
  return $connection;
}
