<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="/hear-me-out/projeto/album/insert.js"></script>
  <script src="/hear-me-out/projeto/album/update.js"></script>
  <script src="/hear-me-out/projeto/album/delete.js"></script>

</head>
<body>
<div class="container mt-3">
  <h2>Meus álbuns</h2>
  <button type="button" class="btn btn-success" onclick="abrirFormularioAlbum()">Cadastrar Álbum</button> <br><br>

  <div class="row">

    <?php
    include_once("../connect.php");
    $conexao = connect_db(); 

    $query = "
      SELECT 
        album.id AS album_id,
        album.nome AS album_nome,
        album.capa,
        album.data_lancamento,
        artista.nome AS artista_nome
      FROM album
      JOIN artista ON album.id_artista = artista.id
    ";

    $resultado = $conexao->query($query);

    if ($resultado) {
        while ($linha = $resultado->fetch_object()) {
            echo "<div id='album-{$linha->album_id}' 
                    data-nome='" . htmlspecialchars($linha->album_nome, ENT_QUOTES) . "' 
                    data-capa='" . htmlspecialchars($linha->capa, ENT_QUOTES) . "' 
                    data-data='" . $linha->data_lancamento . "' 
                    style='display: none;'></div>";
            $btnAlterar = "<button type='button' class='btn btn-warning me-2' onclick='abrirAlterarAlbum({$linha->album_id})'>Alterar</button>";
            $btnExcluir = "<button type='button' class='btn btn-danger me-2' onclick='deleteAlbum({$linha->album_id})'>Excluir</button>";
            $btnVerAlbum = "<a href='index.php?page=4&id=" . $linha->album_id . "' class='btn btn-primary'>Ver álbum</a>";


            echo "
            <div class='col-md-4 mb-4'>
              <div class='card' style='width:100%'>
                <img class='card-img-top' src='{$linha->capa}' alt='Capa do álbum'>
                <div class='card-body'>
                  <h4 class='card-title'>{$linha->album_nome}</h4>
                  <p class='card-text'><b>Artista:</b> {$linha->artista_nome}</p>
                  $btnVerAlbum
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