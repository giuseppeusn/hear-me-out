<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="stylesheet" href="../styles/card.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
  include_once("../header.php");
  $conexao = new mysqli("localhost:3306", "root", "", "hear_me_out");
    $musica_id = $_GET['id'];

    $queryMusicas = "SELECT 
        musica.id AS musica_id,
        musica.nome AS musica_nome,
        musica.capa AS musica_capa,
        musica.duracao AS musica_duracao,
        artista.nome AS artista_nome,
        album.nome AS album_nome,
        album.capa AS album_capa
    FROM musica
    JOIN artista ON musica.id_artista = artista.id
    JOIN album ON musica.id_album = album.id
    WHERE musica.id = $musica_id";
    $resultadoMusicas = $conexao->query($queryMusicas);
    if ($resultadoMusicas && $resultadoMusicas->num_rows > 0) {
    $musica = $resultadoMusicas->fetch_object();
    } else {
        echo "Nenhuma música encontrada.";
        exit;
    }


    $duracao_total = $musica->musica_duracao;
    $minutosMusica = floor($duracao_total / 60);
    $segundosMusica = $duracao_total % 60;
    $btnAddAvaliacao = "<button type='button' class='btn btn-success me-2' onclick='addAvaliacao(<?= $musica->musica_id ?>)'>Inserir avaliação</button>";

    echo "
    <div class='container' style='color: white'>
      <div class='row align-items-start'>
        <div class='col-auto' name='Capa da musica'>
          <img class='img-fluid border border-dark' src='{$musica->musica_capa}' alt='capa da musica' 
            style='width: 400px; height: 400px; border: 8px solid black; display: block;'>
          

          <div name='Caixa de avaliação 'class='border border-3 mt-3 p-2 text-center' style='border-color: black; display: inline-block; width: 100%;'>
            <div class='fw-bold mb-2' style='font-size: 18px;'>avaliações</div>
            <div class='row'>

              <div class='col'>
                <div class='fw-bold' style='font-size: 18px;'>Publico nota</div>
                <div style='font-size: 14px;'>público</div>
              </div>

              <div class='col'>
                <div class='fw-bold' style='font-size: 18px;'>Critico nota</div>
                <div style='font-size: 14px;'>crítica</div>
              </div>

            </div>
          </div> 
          <br><br>
          <div name='Botao avaliacao' style='display: flex; align-items: center; justify-content: center;'>{$btnAddAvaliacao}</div>
        </div>


        <div class='col' name='Info da musica'>
          <p class='text-uppercase mb-0' style='font-size: 14px;'>música</p>
          <h1 style='font-size: 55px; font-weight: bold; margin-top: -15px; margin-left: -5px;'>{$musica->musica_nome}</h1>
          <p class='mt-3' style='font-size: 16px; font-weight:bold;'>{$musica->artista_nome}</p>
          <p class='mt-3' style='font-size: 16px;'>" . ($duracao_total >= 60 ? "{$minutosMusica} min {$segundosMusica} sec" : "{$segundosMusica} sec") . "</p>
            </tbody>
          </table>
          <p>Lista de sugestão: <p/>";

    $query = "SELECT  * FROM view_albuns_com_nomes ORDER BY RAND()";
    $resultado = $conexao->query($query);
    include "../components/cauroselCard.php";

    if ($resultado && $resultado->num_rows > 0) {
        while ($data = $resultado->fetch_object()) {
        echo '<div class="swiper-slide">';
        echo cauroselCard($data->nome_album, $data->nome_artista, $data->capa ?? '#', '/hear-me-out/projeto/paginaUser/album.php?id=' . $data->album_id . '');
        echo '</div>';
        }
    } else {
        echo "<p>Nenhum álbum encontrado.</p>";
    }

    echo "
        </div>
      </div>
    </div> 
    ";
?>


</html>