<?php
  include("../header.php");
  include("../connect.php");

  session_start();

  if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("location: /hear-me-out/projeto/login.php");
    exit();
  }

  if(isset($_GET["page"])) {
    if($_GET["page"] == 1) {
      include("insert.php");
    } else if($_GET["page"] == 2) {
      include("update.php");
    } else if($_GET["page"] == 3) {
      include("delete.php");
    } else {
      include("album.php");
    }
  } else {
    include("album.php");
  }

?>