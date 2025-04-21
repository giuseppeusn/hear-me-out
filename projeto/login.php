<?php
  include("header.php");
  include("connect.php");

  
  if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    session_start();

    $mysql = connect_db();
    $queryUsuario = "SELECT * FROM usuario WHERE email = '".$email."' AND senha = '".$senha."'";
    $usuario = $mysql->query($queryUsuario);

    if ($usuario->num_rows > 0) {
      $_SESSION['authenticated'] = true;
      $mysql->close();
      header("location: /hear-me-out/projeto/index.php");
      exit();
    }

    $queryArtista = "SELECT * FROM artista WHERE email = '".$email."' AND senha = '".$senha."'";
    $artista = $mysql->query($queryArtista);

    if ($artista->num_rows > 0) {
      $_SESSION['authenticated'] = true;
      $mysql->close();
      header("location: /hear-me-out/projeto/artista/index.php");
      exit();
    }

    $queryCritico = "SELECT * FROM critico WHERE email = '".$email ."' AND senha = '".$senha."'";
    $critico = $mysql->query($queryCritico);

    if ($critico->num_rows > 0) {
      $_SESSION['authenticated'] = true;
      $mysql->close();
      header("location: /hear-me-out/projeto/critico/index.php");
      exit();
    }

    $mysql->close();
    echo "<script>alert('Email ou senha incorretos!');</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<body>
  <div class="container w-50 h-auto mt-5"> 
    <h2 class="text-center">Login</h2>
    <form
      method="POST"
    >
      <div class="form-group pb-3">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Digite o seu email">
      </div>
      <div class="form-group pb-3">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="senha" class="form-control" id="exampleInputPassword1" placeholder="Digite a sua senha">
      </div>
      <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
  </div>
</body>
</html>