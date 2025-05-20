<?php
include_once "../header.php";
include_once "../connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: /hear-me-out/projeto/login.php");
    exit();
}
$conexao = connect_db();
$id = $_GET['id'];
$query = "SELECT * FROM artista WHERE id = $id";
$resultado_query = mysqli_query($conexao, $query);
if(!mysqli_num_rows($resultado_query) > 0){
    header("location: index.php?page=1");
    exit;
}
$resultado_array = mysqli_fetch_assoc($resultado_query);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mais informações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/global.css">
    <style>
        body {
            background-color: #212121 !important;
            color: white !important;
        }

        .cor {
            background-color: rgb(56, 56, 56) !important;
        }

        .custom-btn-size {
            font-size: 19px;
        }
    </style>
</head>

<body>
    <div class="container mt-2 pb-4 border-bottom">
        <div class="py-2">
            <a href='index.php?page=1' class='btn btn-danger mb-3'>Voltar</a>
        </div>
        <div class="row">
            <div class="col">
                <img src="<?php echo $resultado_array['imagem']; ?>" alt="imagem" class="img-fluid rounded" style="width: 250px; height: 250px; object-fit: cover;">
            </div>
            <div class="col d-flex align-items-center">
                <h1 class="text-white"><?php echo $resultado_array['nome'] ?></h1>
            </div>
        </div>
    </div>
    </div>
    <div class="container pt-4 ps-5">
        <div class="row py-3 cor border-bottom rounded-top">
            <div class="col-2 fw-bold">Nome:</div>
            <div class="col"><?php echo $resultado_array['nome'] ?></div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-2 fw-bold">Email:</div>
            <div class="col">
                <?php echo $resultado_array['email'] ?>
            </div>
        </div>
        <div class="row py-3 cor border-bottom">
            <div class="col-2 fw-bold">Data de Formação:</div>
            <div class="col">
                <?php echo $resultado_array['data_formacao'] ?>
            </div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-2 fw-bold">País:</div>
            <div class="col">
                <?php echo $resultado_array['pais'] ?>
            </div>
        </div>
        <div class="row py-3 cor border-bottom">
            <div class="col-2 fw-bold">Site Oficial:</div>
            <div class="col">
                <?php echo $resultado_array['site_oficial'] ?>
            </div>
        </div>
        <div class="row py-3 border-bottom">
            <div class="col-2 fw-bold">Gênero Músical:</div>
            <div class="col">
                <?php echo $resultado_array['genero'] ?>
            </div>
        </div>
        <div class="row py-3 cor rounded-bottom">
            <div class="col-2 fw-bold">Biografia:</div>
            <div class="col">
                <?php echo $resultado_array['biografia'] ?>
            </div>
        </div>
        <div class="row py-3">
            <a href="artistasAdmin.php?id=<?php echo $id; ?>&aprovado=1" class="btn btn-success aprovar-link custom-btn-size p-1">Aprovar</a>
        </div>
    </div>
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