<?php
  include("../header.php");
  include("../connect.php");
  include("../components/coverCard.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../styles/search.css">
</head>
  <div class="cs-container">
    <div class="content">
      <?php
        if (isset($_GET['search'])) {
          $term = $_GET['search'];

          $connection = connect_db();
          $query_musica = "SELECT * FROM view_musicas_com_nomes WHERE nome_musica LIKE '%$term%' OR nome_artista LIKE '%$term%'";
          $query_album = "SELECT * FROM view_albuns_com_nomes WHERE nome_album LIKE '%$term%' OR nome_artista LIKE '%$term%'";
          $resultado_musica = $connection->query($query_musica);
          $resultado_album = $connection->query($query_album);

          echo '<p class="text-white mt-3">Resultados para: ' . $term . '</p>';

          if ($resultado_musica->num_rows > 0) {
            echo '<div class="search-list-wrapper">';
            echo '<h2 class="text-white mb-3 mt-3 fw-bold">Músicas</h2>';

            while ($data = $resultado_musica->fetch_object()) {
              echo coverCard($data->nome_musica, $data->nome_artista, $data->capa, '/hear-me-out/projeto/musica?id=' . $data->id_musica . '', $data->duracao, 'pesquisa');
            }

            echo '</div>';
          }

          if ($resultado_album->num_rows > 0) {
            echo '<div class="search-list-wrapper">';
            echo '<h2 class="text-white mb-3 mt-3 fw-bold">Álbuns</h2>';

            while ($data = $resultado_album->fetch_object()) {
              echo coverCard($data->nome_album, $data->nome_artista, $data->capa, '/hear-me-out/projeto/album?id=' . $data->album_id . '', null, 'pesquisa');
            }

            echo '</div>';
          }

          if ($resultado_musica->num_rows == 0 && $resultado_album->num_rows == 0) {
            echo '<div style="display:flex">
              <h4 class="text-white mt-3 text-center">Nenhum resultado encontrado.</h4>
            </div>';
          }

        } else {
          echo '<div style="display:flex">
            <h4 class="text-white mt-3 text-center">Nenhum resultado encontrado.</h4>
          </div>';
        }
      ?>
    </div>
  </div>
</html>