<?php
  session_start();
  include("header.php");
  include("connect.php");
  include("footer.php");

  if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("location: /hear-me-out/projeto/login.php");
    exit();
  }
  
  include("home.php")
?>