<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles/pagAlbum.css" >
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="alterarComentario.js"></script>
  <script src="excluirComentario.js"></script>
  <script src="verComentario.js"></script>

</head>
<body>
<?php
include_once("../header.php");
$conexao = new mysqli("localhost:3306", "root", "", "hear_me_out");

$id_usuario_logado = $_SESSION['id'];
$nome_usuario_logado = $_SESSION['nome'];

$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$queryAlbum = "SELECT 
    album.id AS album_id,
    album.nome AS album_nome,
    album.capa AS album_capa,
    album.data_lancamento AS album_data,
    artista.nome AS artista_nome
FROM album
INNER JOIN artista ON album.id_artista = artista.id
WHERE album.id = $album_id";
$resultadoAlbum = $conexao->query($queryAlbum);
$dadosAlbum = $resultadoAlbum->fetch_object();
  if (!$dadosAlbum) {
  echo "Álbum não encontrado.";
  exit; }

$queryResumo = "SELECT 
    COUNT(id) AS musicas_total,
    IFNULL(SUM(duracao), 0) AS duracao_total
FROM musica
WHERE id_album = $album_id";
$resultadoResumo = $conexao->query($queryResumo);
$resumo = $resultadoResumo->fetch_object();


$queryMusicas = "SELECT 
    musica.id AS musica_id,
    musica.nome AS musica_nome,
    musica.capa AS musica_capa,
    musica.duracao AS musica_duracao,
    musica.data_lancamento AS musica_data
FROM musica
WHERE musica.id_album = $album_id";
$resultadoMusicas = $conexao->query($queryMusicas);

$queryComentarioAlbum = "SELECT 
    comentario.id AS comentario_id,
    comentario.mensagem AS comentario_mensagem,
    comentario.nome_autor AS comentario_nome,
    comentario.id_autor AS comentario_idAutor,
    comentario_album.id_album AS comentario_IdAlbum,
    comentario_album.id_comentario AS comentario_IdComentario
FROM comentario
INNER JOIN comentario_album ON comentario.id = comentario_album.id_comentario
INNER JOIN album ON comentario_album.id_album = album.id
WHERE comentario_album.id_album = $album_id;";

$resultadoComentario = $conexao->query($queryComentarioAlbum);
$dadosComentario = $resultadoComentario->fetch_object();


$musicas_total = $resumo->musicas_total;
$duracao_total = $resumo->duracao_total;
$minutosAlbum = floor($duracao_total / 60);
$segundosAlbum = $duracao_total % 60;

$btnAddAvaliacao = "<button type='button' class='btn btn-success me-2' onclick='addAvaliacao(<?= $dadosAlbum->album_id ?>)'>Inserir avaliação</button>";
$btnAlterarComentario = "<button type='button' class='btn btn-warning me-2' onclick='alterarComentario(<?= $dadosComentario->comentario_id ?>)'>Alterar</button>";
$btnExcluirComentario = "<button type='button' class='btn btn-danger me-2' onclick='excluirComentario(<?= $dadosComentario->comentario_id ?>)'>Deletar</button>";
$btnVerComentario = "<a href='verComentario.php?album_id=" . $dadosAlbum->album_id . "' class='btn btn-primary me-2'>Meus comentários</a>";

    echo "
    <div class='container'>
      <div class='row align-items-start'>
        <div class='col-md-4'>
          <img id='capa' class='img-fluid border border-white' src='{$dadosAlbum->album_capa}' alt='capa'>
          
          <div name='Caixa de avaliação 'class='border border-3 mt-3 p-2 text-center' id='avaliacao'>
            <div class='fw-bold mb-2'>avaliações</div>
            <div class='row'>

              <div class='col'>
                <div class='fw-bold'>Publico nota</div>
                <p >público</p>
              </div>

              <div class='col'>
                <div class='fw-bold' >Critico nota</div>
                <p>crítica</p>
              </div>

            </div>
          </div> <br>
          <div name='Caixa de comentário' class='border border-3 mt-3 p-2' id='comentario'>
            <div class='fw-bold mb-2' >Comentários</div>
            <form id='comentarioForm'>
              <div id='comentArea' class='d-flex gap-2'>
                <textarea class ='form-control 'name='comentario_mensagem' id='comentario_mensagem' maxlength='250' rows='5' cols='40' placeholder='Digite o seu comentário aqui'></textarea>
                <button id='enviarComent' type='submit' class='btn btn-primary'>Enviar</button>
              </div>
              <input type='hidden' name='album_id' value='$dadosAlbum->album_id'> <br>";
$comentarios = [];
$comentariosUsuario = [];
$comentou = false;
$usuarioComentou = false;

$resultadoComentario = $conexao->query($queryComentarioAlbum);

while ($dadosComentario = $resultadoComentario->fetch_object()) {
    $comentarios[] = $dadosComentario;

    if (
        $dadosComentario->comentario_idAutor == $id_usuario_logado && $dadosComentario->comentario_nome == $nome_usuario_logado
    ) {
        $usuarioComentou = true;
        $comentariosUsuario[] = [
          'id' => $dadosComentario->comentario_id,
          'mensagem' => $dadosComentario->comentario_mensagem,
          'autor_id' => $dadosComentario->comentario_idAutor,
          'autor_nome' => $dadosComentario->comentario_nome
        ];
    }

    $comentou = true;
}

if ($usuarioComentou) {
    $btnVerComentario = "<button type='button' class='btn btn-primary me-2' onclick='verComentario(" . $dadosAlbum->album_id . ")'>Meus comentários</button>";

    echo $btnVerComentario;

    echo "<script>
      if (!window.comentariosPorAlbum) window.comentariosPorAlbum = {};
      comentariosPorAlbum[" . $dadosAlbum->album_id . "] = " . json_encode($comentariosUsuario) . ";
    </script>";
}

if ($comentou) {
    foreach ($comentarios as $comentario) {
        echo "<br><p>•{$comentario->comentario_nome}<br>{$comentario->comentario_mensagem}</p>";
    }
} else {
    echo "<p style='text-align: center;'>Seja o primeiro a comentar!</p>";
}


        echo "
            </form>
            <div id='mensagem'></div>
          </div>
        </div>
      </div>";
        
        echo"
        <div class='col-md-8' name='Info do album' style='color: white;'>
          <p class='text-uppercase mb-0' style='font-size: 14px;'>álbum</p>
          <h1 style='font-size: 55px; font-weight: bold; margin-top: -15px; margin-left: -5px;'>{$dadosAlbum->album_nome}</h1>
          <p class='mt-3' style='font-size: 16px; font-weight:bold;'>{$dadosAlbum->artista_nome}</p>
          <p class='mt-3' style='font-size: 16px;'>" . ($duracao_total >= 60 ? "{$minutosAlbum} min {$segundosAlbum} sec" : "{$segundosAlbum} sec") . "</p>
          <p class='mt-3' style='font-size: 16px;'>". ($musicas_total == 1 ? "{$musicas_total} música" : "{$musicas_total} músicas") . "</p> ";
        
        echo "
        <div name='Lista de musicas' class='table-responsive mt-4'>
          <table class='table table-striped'>
            <thead>
              <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Duração</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>";
        if ($resultadoMusicas->num_rows > 0) {

            while ($musica = $resultadoMusicas->fetch_object()) {
                echo "<div id='musica-{$musica->musica_id}' 
                data-nome='" . htmlspecialchars($musica->musica_nome, ENT_QUOTES) . "' 
                data-capa='" . htmlspecialchars($musica->musica_capa, ENT_QUOTES) . "' 
                data-duracao='" . htmlspecialchars($musica->musica_duracao, ENT_QUOTES) . "'
                data-data='" . $musica->musica_data . "' 
                style='display: none;'></div>";
                $minutosMusica = floor($musica->musica_duracao / 60);
                $segundosMusica = $musica->musica_duracao % 60;
                echo "<tr>";
                echo "<td><img src='{$musica->musica_capa}' style='width: 50px; lenght:50px'></td>";
                echo "<td><a href='/hear-me-out/projeto/paginaUser/musica.php?id={$musica->musica_id}'>{$musica->musica_nome}</a></td>";
                echo "<td>" . ($musica->musica_duracao >= 60 
                                ? "{$minutosMusica} min {$segundosMusica} sec" 
                                : "{$segundosMusica} sec") . "</td>";
                echo "<td>AINDA NAO FEITO</td>";
                
            }
        }

    echo "
            </tbody>
          </table>
        </div>
      </div>
    </div> 
    ";
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("comentarioForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("insertComent.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      if (data.includes("erro:usuario_nao_logado")) {
        Swal.fire({
          icon: "warning",
          title: "Você precisa estar logado para comentar!",
          showCancelButton: true,
          confirmButtonText: "Fazer login",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../login.php";
          }
        });
        return;
      }

      Swal.fire({
        icon: data.includes("sucesso") ? "success" : "error",
        title: data,
        timer: 2500,
        showConfirmButton: false
      });

      if (data.includes("sucesso")) {
        form.reset();
        window.location.reload();
      }

      document.getElementById("mensagem").innerHTML = "";
    })
    .catch(err => {
      Swal.fire({
        icon: "error",
        title: "Erro ao enviar comentário.",
        text: err.toString()
      });
    });
  });
});
</script>
<?php if (isset($_SESSION['id']) && isset($_SESSION['nome'])): ?>
<script>
  sessionStorage.setItem('id_usuario', <?= json_encode($_SESSION['id']) ?>);
  sessionStorage.setItem('nome_usuario', <?= json_encode($_SESSION['nome']) ?>);
</script>
<?php endif; ?>

</body>
</html>