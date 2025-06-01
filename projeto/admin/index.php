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

$conexao = connect_db();

function mostrarAlerta($titulo, $texto = '', $icone = 'success') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            Swal.fire({
                title: '$titulo',
                text: '$texto',
                icon: '$icone',
                timer: 4000,
                showConfirmButton: false
            });
        </script>";
}

if (isset($_SESSION['sucesso_aprovado'])) {
    mostrarAlerta('Aprovado com sucesso!', '', 'success');
    unset($_SESSION['sucesso_aprovado']);
}

if (isset($_SESSION['erro_aprovado'])) {
    mostrarAlerta('Algo deu errado!', 'Nenhuma alteração foi feita.', 'error');
    unset($_SESSION['erro_aprovado']);
}

if (isset($_GET['id']) && isset($_GET['aprovado']) && isset($_GET['tipo'])) {
    $id = intval($_GET['id']);
    $aprovado = intval($_GET['aprovado']);
    $tipo = $_GET['tipo'];

    if (($aprovado === 0 || $aprovado === 1) && in_array($tipo, ['artista', 'critico'])) {
        $tabela = mysqli_real_escape_string($conexao, $tipo);

        $query_aprovar = "UPDATE $tabela SET aprovado = 1 WHERE id = $id";

        if (mysqli_query($conexao, $query_aprovar)) {
            $_SESSION['sucesso_aprovado'] = true;
        } else {
            $_SESSION['erro_aprovado'] = true;
        }
    } else {
        $_SESSION['erro_aprovado'] = true;
    }
    header("Location: /hear-me-out/projeto/admin/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/hear-me-out/projeto/js/validarCampos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/global.css">
    <style>
        body {
            background-color: #212121 !important;
            color: white !important;
        }

        th {
            background-color: #212121 !important;
            color: white !important;
        }

        .table-striped tbody tr:nth-of-type(odd) td {
            background-color: rgb(56, 56, 56) !important;
            color: white !important;
        }

        .table-striped tbody tr:nth-of-type(even) td {
            background-color: rgb(34, 34, 34) !important;
            color: white !important;
        }

        .card {
            background-color: #2e2e2e !important;
            padding: 10px;
            color: white !important;
            border-radius: 10px;
        }

        img.perfil {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>

<body>
<div class="cs-container mt-4">
    <div class="content">
        <h2 class="mb-4">Aprovações pendentes</h2>

        <div class="row g-4">
            <?php
            $query_artistas = "SELECT * FROM artista WHERE aprovado = 0";
            $resultado_artista = mysqli_query($conexao, $query_artistas);
            
            foreach ($resultado_artista as $artista) {
                include "artista.php";
            }

            $query_criticos = "SELECT * FROM critico WHERE aprovado = 0";
            $resultado_criticos = mysqli_query($conexao, $query_criticos);

            foreach ($resultado_criticos as $critico) {
                include "critico.php";
            }
            ?>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.aprovar-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            Swal.fire({
                title: 'Aprovar este usuário?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, aprovar',
                cancelButtonText: 'Cancelar'
            }).then(r => {
                if (r.isConfirmed) {
                    window.location.href = link.href;
                }
            });
        });
    });
</script>
</body>

</html>
