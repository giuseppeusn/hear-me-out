<?php
  function renderList($musica, $list) {
    if ($musica) {

        $html = '<div class="section mt-4">
            <h4 class="section-title">Músicas</h4>';

        $html .= '<div style="height: 600px; overflow-y: auto;">';

        while ($data = $list->fetch_object()) {
            $html .= coverCard($data->musica_nome, null, $data->musica_capa, '/hear-me-out/projeto/musica?id=' . $data->musica_id . '', $data->musica_duracao, null, $data->musica_id, $data->musica_data);
        }
            
      return $html . '</div></div>';
    }
  }
?>