<?php
function searchCard($nome, $artista, $capa, $link, $duracao) {
  $duracaoHtml = '';

  if ($duracao) {
    $minutos = floor($duracao / 60);
    $segundos = str_pad($duracao % 60, 2, '0', STR_PAD_LEFT);
    $duracaoFormatada = ($duracao >= 60) ? "$minutos:$segundos" : $duracao;

    $duracaoHtml = '
      <div class="search-card-duration">
        <p>' . htmlspecialchars($duracaoFormatada) . '</p>
      </div>';
  }

  return '
    <a href="'. htmlspecialchars($link) . '" class="search-card-wrapper">
      <div class="search-card">
        <img class="search-card-img" src="' . htmlspecialchars($capa) . '" alt="Capa da música">
        <div class="search-card-text">
          <h5>' . htmlspecialchars($nome) . '</h5>
          <p>' . htmlspecialchars($artista) . '</p>
        </div>
        </div>
        ' . $duracaoHtml . '
    </a>';
  }
?>
