<?php
function infoCard($titulo, $nome, $artista, $data, $resumo) {
  $lancamento = new DateTime($data);

  return '
  <div class="section">
    <h4 class="section-title">' . htmlspecialchars($titulo) . '</h4>
    <p class="info-nome">' . htmlspecialchars($nome) . '</p>
    <div class="info-texts">
      <span class="fw-bold">' . htmlspecialchars($artista) . '</span>
    </div>
    <div class="info-texts mt-5">
      <span>' . $lancamento->format('Y') . '</span>
      <span class="mt-3">• ' . $resumo->musicas_total . ' ' . 
        ($resumo->musicas_total == 1 ? "música" : "músicas") . ', ' . formatarDuracao($resumo->duracao_total) . '
      </span>
    </div>
  </div>';
}
?>
