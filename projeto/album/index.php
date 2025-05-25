<?php

  if (session_status() === PHP_SESSION_DISABLED) {
    session_start();
  }

  include_once("../header.php");
  include_once("../footer.php");
  include_once("../connect.php");
  include_once("functions.php");

  $connection = connect_db();

  $album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $album = obterAlbum($connection, $album_id);

  if (!$album) {
      echo '<div class="cs-container" style="height: 100vh; self-align: center;">
        <h4 class="text-white mt-3 text-center">Álbum não encontrado.<h4>
      </div>';
      exit;
  }

  $resumo = obterResumoAlbum($connection, $album_id);
  $musicas = obterMusicas($connection, $album_id);
  $comentarios = obterComentarios($connection, $album_id);

  $tipoAvaliador = null;
  $user_id = null;

  if (isset($_SESSION['permissao']) && isset($_SESSION['id'])) {
    $tipoAvaliador = $_SESSION['permissao'] != 'critico' && $_SESSION['permissao'] != 'usuario' ? 'usuario' : $_SESSION['permissao'];
    $user_id = $_SESSION['id'];
  }

  $avaliacoes = obterAvaliacoesAlbum($connection, $album_id, $user_id, $tipoAvaliador);	
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="stylesheet" href="../styles/rate-page.css">
  <link rel="stylesheet" href="../styles/search.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class='cs-container'>
  <div class='content'>
    <div class='row justify-content-center'>
      <div class='cs-col'>
        <img id='capa' class='rating-cover' src='<?= $album->album_capa ?>' alt='capa'>
        <?php include "../components/rating-page/rating.php"; ?>
        <?php include "../components/rating-page/comments.php"; ?>
      </div>
      <div class='col-md-6'>
        <?php
          include "../components/rating-page/info.php";
          include "../components/rating-page/renderList.php"; 

          echo infoCard('Álbum', $album->album_nome, $album->artista_nome, $album->album_data, null, $resumo);
          echo renderList(true, $musicas);
        ?>
      </div>
    </div>
  </div>
</div>
<?php include "comments/script.php"; ?>
<?php include "reviews/script.php"; ?>
</body>
</html>
