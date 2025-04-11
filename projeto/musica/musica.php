<?php
include "connect.php";
include "header.php";

$conexao = connect_db();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD - Músicas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

  <div class="container-md mt-3">
    <?php include "mensagem.php"; ?>
    <div class="row">
      <div class="col">
        <h3 class="">Crud de Músicas</h2>
      </div>
      <div class="col">
        <a class="btn btn-success float-end" href="index.php?page=1">Adicionar</a>
      </div>
    </div>

  </div>

  <div class="container-md mt-3">
    <table class="table table-striped">
      <thead class="table-primary">
        <th>ID</th>
        <th>Nome</th>
        <th>Duração</th>
        <th>Data de Lançamento</th>
        <th>Capa</th>
        <th>Artista</th>
        <th>Álbum</th>
        <th>#</th>
      </thead>
      <tbody>
        <?php
        $musicas = mysqli_query($conexao, "SELECT musica.id, musica.nome, musica.duracao, musica.data_lancamento, musica.capa, artista.nome AS nome_artista, album.nome AS nome_album FROM musica JOIN artista ON musica.id_artista = artista.id JOIN album ON musica.id_album = album.id");
        if (mysqli_num_rows($musicas) > 0) {
          foreach ($musicas as $i) {
            echo "<tr>";
            echo "<td>" . $i["id"] . "</td>";
            echo "<td>" . $i["nome"] . "</td>";
            echo "<td>" . $i["duracao"] . "</td>";
            echo "<td>" . $i["data_lancamento"] . "</td>";
            echo '<td><img src="' . $i["capa"] . '" class="img-thumbnail" width="50"></td>';
            echo "<td>" . $i["nome_artista"] . "</td>";
            echo "<td>" . $i["nome_album"] . "</td>";
            echo '<td>
            <a href="index.php?page=2&id=' . $i["id"] . '"class="btn btn-success btn-sm">Editar</a>
            <a href="index.php?page=3&id=' . $i["id"] . '"class="btn btn-danger btn-sm">Excluir</a>
                  </td>';
            echo "</tr>";
          }
        } else {
          echo '<tr><td colspan="8" class="text-center">Nenhuma música encontrada.</td></tr>';
        }
        ?>
        </tr>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>