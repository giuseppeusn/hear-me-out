<?php
include_once "../header.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: /hear-me-out/projeto/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar cadastros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/global.css">
</head>
<body>
    <div class="container text-center pt-3" style="color: white">
        <h1 style="font-size: 50px;">Gerenciar cadastros</h1>
    </div>
    <div class="container text-center align-items-center p-2">
        <a class="btn btn-primary btn-lg m-2" role="button" href="?page=1" style="font-size: 25px;">Artistas</a>
        <a class="btn btn-primary btn-lg m-2" role="button" href="?page=2" style="font-size: 25px;">Criticos</a>
    </div>
</body>

</html>