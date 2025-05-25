<?php

  if (session_status() === PHP_SESSION_DISABLED) {
    session_start();
  }

  include_once("../header.php");
  include_once("../footer.php");
  include_once("../connect.php");
  include_once("functions.php");

  $connection = connect_db();

  $musica_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $musica = obterMusica($connection, $musica_id);

  if (!$musica) {
      echo '<div class="cs-container" style="height: 100vh; self-align: center;">
        <h4 class="text-white mt-3 text-center">Música não encontrada.<h4>
      </div>';
      exit;
  }

  $resumo = obterResumoMusica($connection, $musica_id);
  $musicas = obterMusicas($connection, $musica_id);
  $comentarios = obterComentarios($connection, $musica_id);

  $tipoAvaliador = null;
  $user_id = null;

  if (isset($_SESSION['permissao']) && isset($_SESSION['id'])) {
    $tipoAvaliador = $_SESSION['permissao'] != 'critico' && $_SESSION['permissao'] != 'usuario' ? 'usuario' : $_SESSION['permissao'];
    $user_id = $_SESSION['id'];
  }

  $avaliacoes = obterAvaliacoesAMusica($connection, $musica_id, $user_id, $tipoAvaliador);	
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
        <img id='capa' class='rating-cover' src='<?= $musicas->musica_capa ?>' alt='capa'>
        <?php include "../components/rating-page/rating.php"; ?>
        <?php include "../components/rating-page/comments.php"; ?>
      </div>
      <div class='col-md-6'>
        <?php
          include "../components/rating-page/info.php";
          include "../components/rating-page/renderList.php"; 

          echo infoCard('Álbum', $musicas->musica_nome, $musica->artista_nome, $musica->musica_data, null, $resumo);
          echo renderList(true, $album);
        ?>
      </div>
    </div>
  </div>
</div>
<?php include "comments/script.php"; ?>
<?php include "reviews/script.php"; ?>
</body>
</html>
