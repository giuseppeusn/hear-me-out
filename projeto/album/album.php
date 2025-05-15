<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-3">
  <h2>Lista de álbuns</h2>
  <a type="button" class="btn btn-success mb-3" href="index.php?page=1">Inserir novo álbum</a>

  <div class="row">
    <?php
    include_once("../connect.php");
    $conexao = connect_db(); 

    $query = "
      SELECT 
        album.id AS album_id,
        album.nome AS album_nome,
        album.duracao,
        album.capa,
        album.data_lancamento,
        album.qtd_musicas,
        artista.nome AS artista_nome
      FROM album
      JOIN artista ON album.id_artista = artista.id
    ";

    $resultado = $conexao->query($query);

    if ($resultado) {
        while ($linha = $resultado->fetch_object()) {
            $btnAlterar = "<a href='index.php?page=2&id=" . $linha->album_id . "' class='btn btn-warning me-2'>Alterar</a>";
            $btnExcluir = "<a href='index.php?page=3&id=" . $linha->album_id . "' class='btn btn-danger'>Excluir</a>";

            echo "
            <div class='col-md-4 mb-4'>
              <div class='card' style='width:100%'>
                <img class='card-img-top' src='{$linha->capa}' alt='Capa do álbum'>
                <div class='card-body'>
                  <h4 class='card-title'>{$linha->album_nome}</h4>
                  <p class='card-text'><strong>Qtd de músicas:</strong> {$linha->qtd_musicas}</p>
                  <p class='card-text'><strong>Artista:</strong> {$linha->artista_nome}</p>
                  <a href='#' class='btn btn-primary me-2'>Ver álbum</a>
                  $btnAlterar
                  $btnExcluir
                </div>
              </div>
            </div>
            ";
        }
    }
    ?>
  </div>
</div>

</body>
</html>
