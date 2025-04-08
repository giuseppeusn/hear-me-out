<?php
include "navbar.php";
include "conexao.php";

$id = $_GET["id"];
$query_read = "SELECT * from musica where id = $id";
$resultado_musicas = mysqli_query($conexao, $query_read);
$musicas = mysqli_fetch_assoc($resultado_musicas);
if (!$musicas) {
    session_start();
    $_SESSION['mensagem'] = "muda na url nao seu puto";
    header("Location: index.php");
    die;
}

if ($musicas) {
    $id_artista = $musicas["id_artista"];
    $id_album = $musicas["id_album"];
}

$query_artista = mysqli_query($conexao, "SELECT nome FROM artista WHERE id = $id_artista");
if ($naosei1 = mysqli_fetch_assoc($query_artista)) {
    $nome_artista = $naosei1['nome'];
}

$query_album = mysqli_query($conexao, "SELECT nome FROM album WHERE id = $id_album");
if ($naosei2 = mysqli_fetch_assoc($query_album)) {
    $nome_album = $naosei2['nome'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud - músicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container-md mt-3">
        <?php
        include "mensagem.php";
        ?>
        <div class="row">
            <div class="col">
                <h3 class="">Editar música:</h2>
            </div>
            <div class="col">
                <a class="btn btn-danger float-end" href="index.php">Voltar</a>
            </div>
        </div>
        <div class="container-md mt-3 border">
            <form action="action.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="id_hidden" value='<?php echo $_GET['id'] ?>' hidden>
                    <label for="name" class="form-label pt-2">Nome da música:</label>
                    <input type="name" class="form-control" id="nome_musica" name="nome_musica"
                        placeholder="Nome da música." value='<?php echo $musicas["nome"] ?>'>
                    <label for="name" class="form-label pt-2">Duração:</label>
                    <input type="number" class="form-control" id="duracao_musica" name="duracao_musica"
                        placeholder="Duração da musica em segundos." value='<?php echo $musicas["duracao"] ?>'>
                    <label for="name" class="form-label pt-2">Data de lançamento:</label>
                    <input type="date" class="form-control" id="data_lancamento_musica" name="data_lancamento_musica"
                        placeholder="" value='<?php echo $musicas["data_lancamento"] ?>'>
                    <label for="formFile" class="form-label pt-2">Capa da música:</label>
                    <input class="form-control" type="text" id="capa_musica_arquivo" name="capa_musica_arquivo"
                        placeholder="Link (.png/.jpg)" value='<?php echo $musicas["capa"] ?>'>
                    <label for="name" class="form-label pt-2">Artista:</label>
                    <input type="name" class="form-control" id="artista_musica" name="artista_musica"
                        placeholder="Artista." value='<?php echo $nome_artista ?>'>
                    <label for="name" class="form-label pt-2">Álbum:</label>
                    <input type="name" class="form-control" id="album_musica" name="album_musica" placeholder="Álbum."
                        value='<?php echo $nome_album ?>'>
                    <button type="submit" name="edit" class="btn btn-primary mt-3">Concluir</button>
                </div>
            </form>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous"></script>
</body>

</html>