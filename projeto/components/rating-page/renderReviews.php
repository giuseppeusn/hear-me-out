<?php
function renderReviews(array $lista) {
    if (count($lista) === 0) {
        return "<p style='text-align: center;'>Nenhuma avaliação encontrada.</p>";
    }

    $html = "<div class='review-wrapper'>";

    foreach ($lista as $avaliacao) {
        $mensagem = htmlspecialchars($avaliacao['mensagem']);
        $nome = htmlspecialchars($avaliacao['nome_avaliador']);
        $nota = number_format($avaliacao['nota'], 1);
        $estrelas = renderStars($nota);

        $html .= "
        <div class='review'>
          <div class='review-content'>
            <img class='user' src='../assets/svg/user.svg' alt='User'>
            <div class='review-info-card'>
              <div class='review-header'>
                <span class='name'>{$nome}</span>
                <div class='review-stars'>
                  {$estrelas}
                  <span>{$nota}</span>
                </div>
              </div>
              <p class='review-msg'>{$mensagem}</p>
            </div>
          </div>
        </div>";
    }

    $html .= "</div>";

    return $html;
}
?>
