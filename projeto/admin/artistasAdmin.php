<?php
include_once "../header.php";
include_once "../connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: /hear-me-out/projeto/login.php");
    exit();
}

$conexao = connect_db();
if ((isset($_SESSION['authenticated']) || $_SESSION['authenticated'] = true) && isset($_SESSION['admin'])) {
    if (isset($_GET['id']) && isset($_GET['aprovado'])) {
        $aprovado = $_GET['aprovado'];
        $id = $_GET['id'];
        if ($aprovado == 0 || $aprovado == 1) {
            $query_aprovar = "UPDATE artista SET aprovado = 1 WHERE id = $id";
            if (mysqli_query($conexao, $query_aprovar)) {
                $_SESSION['sucesso_aprovado'] = true;
            } else {
                $_SESSION['erro_aprovado'] = true;
            }
        } else {
            $_SESSION['erro_aprovado'] = true;
        }
        header("Location: index.php?page=1");
    }
} else {
    header("Location: /hear-me-out/projeto/index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
    </style>
</head>

<body>
    <div class="container mt-3">
        <a class="btn btn-danger" href="index.php">Voltar</a>
    </div>
    <div class="container mt-3">
        <table class="table table-striped">
            <colgroup>
                <col style="width: 16%">
                <col style="width: 16%">
                <col style="width: 16%">
                <col style="width: 5%">
                <col style="width: 2%">
            </colgroup>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>País</th>
                    <th>Mais informações</th>
                    <th>Aprovar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM artista WHERE aprovado = 0"; // 0 = false
                $resultado_query = mysqli_query($conexao, $query);
                foreach ($resultado_query as $i) {
                    echo "<tr>";
                    echo "<td>" . $i['nome'] . "</td>";
                    echo "<td>" . $i['email'] . "</td>";
                    echo "<td>" . $i['pais'] . "</td>";
                    echo '<td><a href="maisInfo.php?id=' . $i['id'] . '" class="btn btn-info btn-sm text-black dropdown-toggle ms-4">Detalhes </a></td>';
                    echo '<td>                        <a class="btn btn-success btn-sm me-1 aprovar-link ms-2" href="artistasAdmin.php?id=' . $i['id'] . '&aprovado=1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="23" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                            </svg>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.aprovar-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();

                Swal.fire({
                    title: 'Aprovar este artista?',
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