<?php
  $route = $_SERVER['REQUEST_URI'];

  if (strpos($route, '/hear-me-out/projeto/artista/meus-albuns/album.php') !== false) {
    header("Location: /hear-me-out/projeto/artista/meus-albuns");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>  
  <link rel="stylesheet" href="/hear-me-out/projeto/styles/my-albums.css">
  <link rel="stylesheet" href="/hear-me-out/projeto/styles/form.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="cs-container">
  <div class="content">
    <?php
    $conexao = connect_db(); 
    $id_artista = intval($_SESSION['id']);
    echo "<div class='albums-header'>
      <h1 class='text-white'>Meus álbuns</h1>
      <button type='button' class='cs-btn confirm' onclick='abrirFormularioAlbum()'>Novo álbum</button>
    </div>";
    $query = "
      SELECT 
        album.id AS album_id,
        album.nome AS album_nome,
        album.capa,
        album.data_lancamento,
        artista.nome AS artista_nome
      FROM album
      JOIN artista ON album.id_artista = artista.id
      WHERE artista.id = $id_artista";

    $resultado = $conexao->query($query);

    echo "<div class='cs-card-wrapper'>";
    if ($resultado) {
        while ($linha = $resultado->fetch_object()) {
            $lancamento = new DateTime($linha->data_lancamento);
            echo "<div class='cs-card'>
              <div id='album-{$linha->album_id}' 
                data-nome='" . htmlspecialchars($linha->album_nome, ENT_QUOTES) . "' 
                data-capa='" . htmlspecialchars($linha->capa, ENT_QUOTES) . "' 
                data-data='" . $linha->data_lancamento . "' 
                style='display: none;'></div>";

            echo "<a href='musicas?id=" . $linha->album_id . "' class='cs-card-link'>
                <img class='cs-card-img' src='{$linha->capa}' alt='Capa do álbum'>
                <div class='cs-card-text'>
                  <h4 class='card-title'>{$linha->album_nome}</h4>
                  <p class='cs-card-year'>{$lancamento->format('Y')}</p>
                </div>
              </a>
              <div class='cs-card-btns'>
                <button type='button' class='btn-update' onclick='abrirAlterarAlbum({$linha->album_id})'>Alterar</button>
                <button type='button' class='btn-delete' onclick='deleteAlbum({$linha->album_id})'>Excluir</button>
              </div>
            </div>";
        }
    }
    echo "</div>";
    ?>
  </div>
</div>
<?php include "script.php"; ?>
</body>
</html>