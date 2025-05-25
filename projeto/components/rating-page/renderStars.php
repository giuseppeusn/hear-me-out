<?php
function renderStars($nota) {
    $html = '<div class="stars">';

    $cheias = floor($nota);
    $temMeia = ($nota - $cheias) > 0 ? 1 : 0;
    $vazias = 5 - $cheias - $temMeia;

    for ($i = 0; $i < $cheias; $i++) {
        $html .= '<img src="../assets/svg/star-fill.svg" alt="★">';
    }

    if ($temMeia) {
        $html .= '<img src="../assets/svg/star-half.svg" alt="☆">';
    }

    for ($i = 0; $i < $vazias; $i++) {
        $html .= '<img src="../assets/svg/star.svg" alt="☆">';
    }

    $html .= '</div>';
    return $html;
}
?>
