<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hear me out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="/hear-me-out/projeto/assets/favicon.ico">
  <link rel="stylesheet" href="/hear-me-out/projeto/styles/global.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <nav class="cs-navbar">
    <a href="/hear-me-out/projeto/"  class="navbar-brand">
      <img src="/hear-me-out/projeto/assets/svg/logo.svg" alt="Logo" class="logo">
      <p>Hear Me Out</p>
    </a>
    <?php
      $route = $_SERVER['REQUEST_URI'];

      if ($route != "/hear-me-out/projeto/" && $route != "/hear-me-out/projeto/login.php") {
        include __DIR__ . "/components/search.php";
        echo search(true);
      }
    ?>
    <div class="navbar-btns">
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