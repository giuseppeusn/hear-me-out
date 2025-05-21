<?php
  function search($header) {
    $className = "";

    if ($header) {
      $className = "navbar-search";
    }

    return '<form action="pesquisa" method="GET" class="' . $className . '">
      <div class="search-container">
        <input 
          type="text" 
          name="search" 
          class="search" 
          placeholder="Pesquisar"
          required
        >
        <button type="submit" class="search-btn">
          <img src="/hear-me-out/projeto/assets/svg/magnifier.svg" alt="Pesquisa" class="search-icon">
        </button>
      </div>
    </form>';
  }
?>