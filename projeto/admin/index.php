<?php
include("../header.php");
include("../connect.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: /hear-me-out/projeto/");
    exit();
}

if (isset($_GET['page'])) {
    if ($_GET['page'] == 1) {
        include "artistasAdmin.php";
    }
    if ($_GET['page'] == 2) {
        include "criticosAdmin.php";
    }
} else {
    include "admin.php";
}

if (isset($_SESSION['sucesso_aprovado'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            Swal.fire({
                title: 'Aprovado com sucesso!',
                icon: 'success',
                draggable: true,
          timer: 4000,
                });
            </script>";
    unset($_SESSION['sucesso_aprovado']);
}
if (isset($_SESSION['erro_aprovado'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            Swal.fire({
                title: 'Algo deu errado!',
                text: 'Nenhuma alteração foi feita.',
                icon: 'error',
                draggable: true,
          timer: 4000,
                });
            </script>";
    unset($_SESSION['erro_aprovado']);
}
