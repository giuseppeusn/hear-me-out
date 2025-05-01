<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hear me out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.0/datatables.min.css" rel="stylesheet" integrity="sha384-mPVd1IOpiZiKTi2sJ2j+O3fU2HHlfwiazbe3W8M9l0owB/AtGp6x5HnuQkQSDQqB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light">
  <div class="container-fluid justify-content-between">
    <ul class="navbar-nav">
      <!-- <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto">Home</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/artista/">Artista</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/album/">Álbuns</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/hear-me-out/projeto/musica/">Músicas</a>
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
          echo '<li class="nav-item">
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.0/datatables.min.js" integrity="sha384-hoaYlVlYX9uV7LZk+WkfhIkpoIAjDF3ucZaxVL+Oy8eCn0ga4Byj0cexIOr8Mmki" crossorigin="anonymous"></script>
<script>
  $(document).ready(function () {
    if ($('#tabelaAlbum').length) {
      $('#tabelaAlbum').DataTable();
    }
  });
</script>
</body>
</html>
