<?php
  include("../components/coverCard.php");

  function renderList($musica, $list) {
    if ($musica) {
      $html = '<div class="section mt-4">
          <h4 class="section-title">Músicas</h4>';
        
        $html .= '<div style="height: 600px; overflow-y: auto;">';
        while ($data = $list->fetch_object()) {
          $html .= coverCard($data->musica_nome, null, $data->musica_capa, '/hear-me-out/projeto/musica?id=' . $data->musica_id . '', $data->musica_duracao);
        }
          
      return $html . '</div></div>';
    } else {
      $html = '<div class="section mt-4">
          <h4 class="section-title">Sugestões de álbuns</h4>';
        
        $html .= '<div style="height: 600px; overflow-y: auto;">';
        while ($data = $list->fetch_object()) {
          $html .= coverCard($data->nome_album, $data->nome_artista, $data->capa, '/hear-me-out/projeto/album?id=' . $data->album_id . '', null);
        }
          
      return $html . '</div></div>';
    }
  }
?>