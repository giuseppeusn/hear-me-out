<?php
include("../header.php");
include("../connect.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ((!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) && $_GET["page"] != 1) {
  header("location: /hear-me-out/projeto/login.php");
  exit();
}

if (isset($_SESSION["sucesso_edit"])){
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
    Swal.fire({
      title: 'Crítico editado com sucesso!',
      icon: 'success',
      draggable: true,
      timer: 4000,
      });
      </script>";
  unset($_SESSION['sucesso_edit']);
}

if (isset($_GET["page"])) {
  if ($_GET["page"] == 1) {
    include("insert.php");
  } else if ($_GET["page"] == 2) {
    include("update.php");
  } else if ($_GET["page"] == 3) {
    include("delete.php");
  } else {
    include("critico.php");
  }
} else {
  include("critico.php");
}
