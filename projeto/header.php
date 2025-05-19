<!DOCTYPE html>
<html lang="en">

<head>
  <title>Hear me out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="./assets/favicon.ico">
  <link rel="stylesheet" href="styles/global.css">
  <link rel="stylesheet" href="styles/header.css">
  <link rel="stylesheet" href="styles/footer.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</head>

<body>
<nav class="navbar navbar-expand-sm">
  <div class="navbar-brand">
    <img src="./assets/svg/logo.svg" alt="Logo" class="logo">
    <p>Hear Me Out</p>
  </div>
  <div class="container-fluid justify-content-between">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto">Início</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/artista/">Artista</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/album/">Álbuns</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/critico/">Críticos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/usuario/">Usuários</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      
      <?php
        session_start();

        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
          echo '
          <a class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg>' . htmlspecialchars($_SESSION['nome']) . '
          </a>
          <li class="nav-item">
            <a class="nav-link" href="/hear-me-out/projeto/logout.php">Sair</a>
          </li>';
        } else {
          echo '<li class="nav-item">
            <a class="nav-link" href="/hear-me-out/projeto/login.php">Login</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Cadastro
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
              <li><a class="dropdown-item" href="/hear-me-out/projeto/artista?page=1">Artista</a></li>
              <li><a class="dropdown-item" href="/hear-me-out/projeto/critico?page=1">Crítico</a></li>
              <li><a class="dropdown-item" href="/hear-me-out/projeto/usuario?page=1">Usuário</a></li>
            </ul>
          </li>';
        }
        ?>
      </ul>
    </div>
  </nav>
</body>

</html>