<?php
include("../header.php");
include_once("../connect.php");

if (session_status() === PHP_SESSION_DISABLED) {
  session_start();
}

if ((!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) && $_GET["page"] != 1) {
  header("location: /hear-me-out/projeto/login.php");
  exit();
}

if (isset($_GET["page"])) {
  if ($_GET["page"] == 1) {
    include("insert.php");
  } else if ($_GET["page"] == 2) {
    include("update.php");
  } else if ($_GET["page"] == 3) {
    include("delete.php");
  } else {
    include("usuario.php");
  }
} else {
  include("usuario.php");
}
