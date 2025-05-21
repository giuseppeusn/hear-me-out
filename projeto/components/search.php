<?php
  function search($header) {
    $formClass = "";
    $btnClass = "search-btn";

    if ($header) {
      $formClass = "navbar-search";
      $btnClass = "search-btn search-btn-header";
    }

    return '<form action="/hear-me-out/projeto/pesquisa" method="GET" class="' . $formClass . '">
      <div class="search-container">
        <input 
          type="text" 
          name="search" 
          class="search" 
          placeholder="Pesquisar"
          required
        >
        <button type="submit" class="'. $btnClass .'">
          <img src="/hear-me-out/projeto/assets/svg/magnifier.svg" alt="Pesquisa" class="search-icon">
        </button>
      </div>
    </form>';
  }
?>