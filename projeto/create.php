<?php
include "header.php";
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
                <h3 class="">Adicionar nova música:</h2>
            </div>
            <div class="col">
                <a class="btn btn-danger float-end" href="index.php">Voltar</a>
            </div>
        </div>

        <div class="container-md mt-3 border">
            <form action="action.php" method="POST">

                <div class="mb-3">
                    <label for="name" class="form-label pt-2">Nome da música:</label>
                    <input type="name" class="form-control" id="nome_musica" name="nome_musica"
                        placeholder="Nome da música.">
                    <label for="name" class="form-label pt-2">Duração:</label>
                    <input type="number" class="form-control" id="duracao_musica" name="duracao_musica"
                        placeholder="Duração da musica em segundos.">
                    <label for="name" class="form-label pt-2">Data de lançamento:</label>
                    <input type="date" class="form-control" id="data_lancamento_musica" name="data_lancamento_musica"
                        placeholder="">
                    <label for="formFile" class="form-label pt-2">Capa da música:</label>
                    <input class="form-control" type="text" id="capa_musica_arquivo" name="capa_musica_arquivo"
                        placeholder="Link (.png/.jpg)">
                    <label for="name" class="form-label pt-2">Artista:</label>
                    <input type="name" class="form-control" id="artista_musica" name="artista_musica"
                        placeholder="Artista.">
                    <label for="name" class="form-label pt-2">Álbum:</label>
                    <input type="name" class="form-control" id="album_musica" name="album_musica" placeholder="Álbum.">
                    <button type="submit" name="create" class="btn btn-primary mt-3">Enviar</button>
                </div>

            </form>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous"></script>
</body>

</html>