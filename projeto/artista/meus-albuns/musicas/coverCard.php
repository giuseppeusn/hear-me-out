<?php
function coverCard($nome, $capa, $link, $duracao, $pesquisa = false, $musica_id, $data) {
  $duracaoHtml = '';

  if ($duracao) {
    $minutos = floor($duracao / 60);
    $segundos = str_pad($duracao % 60, 2, '0', STR_PAD_LEFT);
    $duracaoFormatada = ($duracao >= 60) ? "$minutos:$segundos" : $duracao;

    $duracaoHtml = '
      <div class="cover-card-duration" style="margin-right: 12px">
        <p>' . htmlspecialchars($duracaoFormatada) . '</p>
      </div>';
  }

  return '
    <div class="cover-card-wrapper ' . ($pesquisa ? 'cover-search' : 'cover-list') . '">
      <a target="_blank" href="'. htmlspecialchars($link) . '" class="text-decoration-none text-white">
        <div class="cover-card">
          <img class="cover-card-img" src="' . htmlspecialchars($capa) . '" alt="Capa da música">
          <div class="cover-card-text">
            <h5>' . htmlspecialchars($nome) . '</h5>
            ' . $duracaoHtml . '
          </div>
        </div>
      </a>
      <div class="cover-card-btns">
        <button type="button" class="btn-update" onclick="abrirAlterarMusica(this, ' . $musica_id . ')" 
          data-nome="' . htmlspecialchars($nome) . '" 
          data-capa="' . htmlspecialchars($capa) . '" 
          data-duracao="' . htmlspecialchars($duracao) . '" 
          data-data="' . htmlspecialchars($data) . '">
          <img src="/hear-me-out/projeto/assets/svg/edit.svg" alt="Editar" class="action-icon">
          <span class="action-text">Alterar<span>
        </button>
        <button type="button" class="btn-delete" onclick="deleteMusica(' . $musica_id . ')">
          <img src="/hear-me-out/projeto/assets/svg/trash.svg" alt="Excluir" class="action-icon">
          <span class="action-text">Excluir<span>
        </button>
      </div>  
    </div>';
  }
?>
