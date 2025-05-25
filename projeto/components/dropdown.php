<?php
  function dropdown($nome, $user) {
    $dropdown = '<li class="nav-item dropdown">
        <a class="user-wrapper" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <p class="user-text">'. $nome . '</p>
          <div class="user-icon-wrapper">
            <img src="/hear-me-out/projeto/assets/svg/user.svg" alt="User Icon" class="user-icon">
          </div>
        </a>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
          <li>
            <a class="dropdown-item" href="/hear-me-out/projeto/usuario/conta.php">
              <img src="/hear-me-out/projeto/assets/svg/gear.svg" alt="Gear Icon" style="width: 20px;">
              <p>Configurações</p>
            </a>
          </li>';

    switch ($user) {
      case 'artista':
        $dropdown = $dropdown . ' <li>
            <a class="dropdown-item" href="/hear-me-out/projeto/artista/meus-albuns">
              <img src="/hear-me-out/projeto/assets/svg/list-music.svg" alt="List music Icon" style="width: 25px;">
              <p>Meus álbuns</p>
            </a>
          </li>';
        break;
      case 'critico':
        $dropdown = $dropdown . '
          <!--<li>
            <a class="dropdown-item" href="/hear-me-out/projeto/critico/avaliacoes.php">
              <img src="/hear-me-out/projeto/assets/svg/list-star.svg" alt="List Star Icon" style="width: 30px;">
              <p>Minhas avaliações</p>
            </a>
          </li>---!>';  
        break;
      case 'admin':
        $dropdown = $dropdown . ' <li>
            <a class="dropdown-item" href="/hear-me-out/projeto/admin">
              <img src="/hear-me-out/projeto/assets/svg/edit-adm.svg" alt="Edit Icon" style="width: 20px;">
              <p>Gerenciar</p>
            </a>
          </li>';
        break;
      default:
        $dropdown = $dropdown . '
          <!--<li>
            <a class="dropdown-item" href="/hear-me-out/projeto/usuario/avaliacoes.php">
              <img src="/hear-me-out/projeto/assets/svg/list-star.svg" alt="List Star Icon" style="width: 30px;">
              <p>Minhas avaliações</p>
            </a>
          </li>---!>';  
        break;
    }


    $dropdown = $dropdown . '
          <li>
            <a class="dropdown-item" href="/hear-me-out/projeto/logout.php">
              <img src="/hear-me-out/projeto/assets/svg/logout.svg" alt="Logout Icon" style="width: 20px;">
              <p>Sair</p>
            </a>
          </li>
        </ul>
      </li>';

    return $dropdown;
  }
?>