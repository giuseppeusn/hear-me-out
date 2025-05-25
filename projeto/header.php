<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hear Me Out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="/hear-me-out/projeto/assets/favicon.ico">
  <link rel="stylesheet" href="/hear-me-out/projeto/styles/global.css">
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
        <a class="nav-link"  href="/hear-me-out/projeto/usuario/">Usuários</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      
      <?php
        session_start();

      if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
        include __DIR__ . "/components/dropdown.php";
        echo dropdown($_SESSION['nome'], $_SESSION['permissao']);
      } else {
        echo '
        <a class="user-wrapper" href="/hear-me-out/projeto/login.php">
          <p class="user-text">LOGIN<p>
          <div class="user-icon-wrapper">
            <img src="/hear-me-out/projeto/assets/svg/user.svg" alt="User Icon" class="user-icon">
          </a>
        </div>
       ';
      }
      ?>
    </div>
  </nav>
</body>
</html>