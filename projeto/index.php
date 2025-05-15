<?php
include("header.php");
include("connect.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
  header("location: /hear-me-out/projeto/login.php");
  exit();
}

include("album/album.php")
  ?>