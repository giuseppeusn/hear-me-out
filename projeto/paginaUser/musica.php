<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="stylesheet" href="../styles/card.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  function addAvaliacao(musicaId) {
    Swal.fire({
      title: 'Avaliar Música',
      input: 'number',
      inputLabel: 'Dê uma nota de 0 a 10',
      inputAttributes: {
        min: 0,
        max: 10,
        step: 0.1
      },
      inputValidator: (value) => {
        if (!value || value < 0 || value > 10) {
          return 'Insira uma nota entre 0 e 10!';
        }
      },
      showCancelButton: true,
      confirmButtonText: 'Enviar Avaliação',
      cancelButtonText: 'Cancelar',
    preConfirm: (nota) => {
      return fetch(window.location.pathname, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'avaliacao=' + encodeURIComponent(nota) + '&musica_id=' + encodeURIComponent(musicaId)
      }).then(response => {
        if (!response.ok) {
          throw new Error('Erro ao enviar avaliação.');
        }
        return true;
      });
    }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('Obrigado!', 'Sua avaliação foi registrada.', 'success').then(() => {
          window.location.reload(); 
        });
      }
    });
  }
  </script>


</head>
<body>
<?php
  include_once("../header.php");
  $conexao = new mysqli("localhost:3306", "root", "", "hear_me_out");
    $musica_id = $_SERVER["REQUEST_METHOD"] === "POST" 
      ? intval($_POST['musica_id']) 
      : intval($_GET['id']);


    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['avaliacao'], $_POST['musica_id'])) {
    $avaliacao = floatval($_POST['avaliacao']);
    $musica_id = intval($_POST['musica_id']);

    $conexao->query("INSERT INTO avaliacao (nota, id_usuario) VALUES ($avaliacao, NULL)");
    $avaliacao_id = $conexao->insert_id;
    $conexao->query("INSERT INTO avaliacao_musica (id_avaliacao, id_musica) VALUES ($avaliacao_id, $musica_id)");

    // Retorna um JSON para o fetch
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
    }
    
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

    $sqlMediaPublico = "SELECT AVG(a.nota) AS media
      FROM avaliacao a
      JOIN avaliacao_musica am ON a.id = am.id_avaliacao
      WHERE am.id_musica = $musica_id AND a.id_usuario IS NULL";


    $resPublico = $conexao->query($sqlMediaPublico);
    $mediaPublico = $resPublico ? $resPublico->fetch_object()->media : null;

    $sqlMediaCritico = "SELECT AVG(a.nota) AS media
      FROM avaliacao a
      JOIN avaliacao_musica am ON a.id = am.id_avaliacao
      WHERE am.id_musica = $musica_id AND a.id_usuario IS NOT NULL";

    $resCritico = $conexao->query($sqlMediaCritico);
    $mediaCritico = $resCritico ? $resCritico->fetch_object()->media : null;


    $duracao_total = $musica->musica_duracao;
    $minutosMusica = floor($duracao_total / 60);
    $segundosMusica = $duracao_total % 60;
    $btnAddAvaliacao = "<button type='button' class='btn btn-success me-2' onclick='addAvaliacao(" . $musica->musica_id . ")'>Inserir avaliação</button>";

    echo "
    <div class='container'>
      <div class='row align-items-start'>
        <div class='col-auto' name='Capa da musica'>
          <img class='img-fluid border border-dark' src='{$musica->musica_capa}' alt='capa da musica' 
            style='width: 400px; height: 400px; border: 8px solid black; display: block;'>
          

          <div name='Caixa de avaliação 'class='border border-3 mt-3 p-2 text-center' style='border-color: black; display: inline-block; width: 100%;'>
            <div class='fw-bold mb-2' style='font-size: 18px;'>avaliações</div>
            <div class='row'>

              <div class='col'>
                <div class='fw-bold' style='font-size: 18px;'>Público nota</div>
                <div style='font-size: 14px;'>" . ($mediaPublico !== null ? number_format($mediaPublico, 2) : "Sem avaliações") . "</div>
              </div>

              <div class='col'>
                <div class='fw-bold' style='font-size: 18px;'>Critico nota</div>
                <div style='font-size: 14px;'>" . ($mediaCritico !== null ? number_format($mediaCritico, 2) : "Sem avaliações") . "</div>
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
    include "../components/card.php";

    if ($resultado && $resultado->num_rows > 0) {
        while ($data = $resultado->fetch_object()) {
        echo '<div class="swiper-slide">';
        echo card($data->nome_album, $data->nome_artista, $data->capa ?? '#', '/hear-me-out/projeto/paginaUser/album.php?id=' . $data->album_id . '');
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