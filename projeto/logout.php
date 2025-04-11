<?php
  session_start();

  session_destroy();

  header("location: /hear-me-out/projeto/login.php");
?>