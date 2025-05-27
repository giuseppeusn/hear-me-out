<?php
function coverCard($nome, $artista, $capa, $link, $duracao, $pesquisa = false, $musica_id, $data) {
  $duracaoHtml = '';

  if ($duracao) {
    $minutos = floor($duracao / 60);
    $segundos = str_pad($duracao % 60, 2, '0', STR_PAD_LEFT);
    $duracaoFormatada = ($duracao >= 60) ? "$minutos:$segundos" : $duracao;

    $duracaoHtml = '
      <div class="cover-card-duration">
        <p>' . htmlspecialchars($duracaoFormatada) . '</p>
      </div>';
  }

  return '
    <a href="'. htmlspecialchars($link) . '" class="cover-card-wrapper ' . ($pesquisa ? 'cover-search' : 'cover-list') . '">
      <div class="cover-card">
        <img class="cover-card-img" src="' . htmlspecialchars($capa) . '" alt="Capa da música">
        <div class="cover-card-text">
          <h5>' . htmlspecialchars($nome) . '</h5>
          ' . ($artista ? '<p>' . htmlspecialchars($artista) . '</p>' : '') . '
        </div>
        </div>
        ' . $duracaoHtml . '
    </a>
    <button type="button" class="btn btn-warning me-2" onclick="abrirAlterarMusica(this, ' . $musica_id . ')" 
    data-nome="' . htmlspecialchars($nome) . '" 
    data-capa="' . htmlspecialchars($capa) . '" 
    data-duracao="' . htmlspecialchars($duracao) . '" 
    data-data="' . htmlspecialchars($data) . '">Alterar</button>
    <button type="button" class="btn btn-danger me-2" onclick="deleteMusica(' . $musica_id . ')">Excluir</button>
    ';
  }
?>
