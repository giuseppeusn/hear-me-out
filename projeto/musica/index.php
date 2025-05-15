<?php
include("../header.php");
include_once("../connect.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ((!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) && $_GET["page"] != 1) {
  header("location: /hear-me-out/projeto/login.php");
  exit();
}

if (isset(($_SESSION['erro_nPreenchido']))) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
          Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Os campos " . implode(', ', $_SESSION['campos_vazios']) . " não foram preenchidos!',
                draggable: true
                })
        </script>";
  unset($_SESSION["erro_nPreenchido"]);

}
if (isset($_SESSION["erro_data"])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
          Swal.fire({
              icon: 'error',
              title: 'Erro!',
              text: 'Data de lançamento inválida!',
              draggable: true
              })
        </script>";
  unset($_SESSION["erro_data"]);
}

if (isset($_SESSION["erro_artista"])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
  Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: 'Nenhum artista com o nome " . $_SESSION["erro_artista"] . " encontrado!',
      draggable: true
      })
</script>";
  unset($_SESSION["erro_artista"]);
}

if (isset($_SESSION["erro_album"])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
  Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: 'Nenhum álbum com o nome " . $_SESSION["erro_album"] . " encontrado!',
      draggable: true
      })
</script>";
  unset($_SESSION["erro_album"]);
}

if (isset($_SESSION["erro_edit"])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
          Swal.fire({
              icon: 'error',
              title: 'Erro!',
              text: 'Música não encontrada!',
              draggable: true
              })
        </script>";
  unset($_SESSION["erro_edit"]);
}

if (isset($_GET["page"])) {
  if ($_GET["page"] == 1) {
    include("insert.php");
  } else if ($_GET["page"] == 2) {
    include("update.php");
  } else if ($_GET["page"] == 3) {
    include("delete.php");
  } else {
    include("musica.php");
  }
} else {
  include("musica.php");
}
