<?php
include("header.php");
include("connect.php");

if (isset($_SESSION['sucesso_cadastro'])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
		Swal.fire({
			title: 'Cadastrado com sucesso! Por favor faça o login.',
			icon: 'success',
			draggable: true,
      timer: 4000,
			});
			</script>";
  unset($_SESSION['sucesso_cadastro']);
}



$mysql = connect_db();
if (isset($_POST['email']) && isset($_POST['senha'])) {
  $email = mysqli_real_escape_string($mysql, $_POST['email']);
  $senha = $_POST['senha'];

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $queryUsuario = "SELECT * FROM usuario WHERE email = '$email'";
  $usuario = $mysql->query($queryUsuario);
  $usuario_array = mysqli_fetch_assoc($usuario);

  if ($usuario->num_rows > 0) {
    if (password_verify($senha, $usuario_array['senha'])) {
      $_SESSION['authenticated'] = true;
      $nome = explode(' ', $usuario_array['nome'])[0];
      $_SESSION['nome'] = $nome;
      $_SESSION['permissao'] = $usuario_array['permissao'];
      if ($usuario_array['permissao'] == 'admin') {
        $_SESSION['admin'] = true;
      }
      $mysql->close();
      header("location: /hear-me-out/projeto");
      exit();
    }
  }

  $queryArtista = "SELECT * FROM artista WHERE email = '$email'";
  $artista = $mysql->query($queryArtista);
  $artista_array = mysqli_fetch_assoc($artista);

  if ($artista->num_rows > 0) {
    if (password_verify($senha, $artista_array['senha'])) {
      $_SESSION['authenticated'] = true;
      $_SESSION['id_artista'] = $artista_array['id'];
      $_SESSION['aprovado'] = $artista_array['aprovado']; // 0 para nao aprovado e 1 para aprovado
      $_SESSION['permissao'] = 'artista';
      $nome = explode(' ', $artista_array['nome'])[0];
      $_SESSION['nome'] = $nome;
      $mysql->close();
      header("location: /hear-me-out/projeto");
      exit();
    }
  }

  $queryCritico = "SELECT * FROM critico WHERE email = '$email'";
  $critico = $mysql->query($queryCritico);
  $critico_array = mysqli_fetch_assoc($critico);

  if ($critico->num_rows > 0) {
    if (password_verify($senha, $critico_array['senha'])) {
      $_SESSION['authenticated'] = true;
      $_SESSION['id_critico'] = $critico_array['id'];
      $_SESSION['aprovado'] = $critico_array['aprovado']; // 0 para nao aprovado e 1 para aprovado
      $_SESSION['permissao'] = 'critico';
      $nome = explode(' ', $critico_array['nome'])[0];
      $_SESSION['nome'] = $nome;
      $mysql->close();
      header("location: /hear-me-out/projeto");
      exit();
    }
  }
  $mysql->close();
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
  Swal.fire({
      title: 'Email ou senha incorretos!',
      icon: 'error',
      draggable: true      
      });
  </script>";
}
?>

<!DOCTYPE html>
<<<<<<< HEAD
<html lang="en">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
=======
<html lang="pt-BR">

>>>>>>> 2652ed858fa4e641f409827ba9d1d7eb7624c4dc
<body>
  <div class="container w-50 h-auto mt-5">
    <h2 class="text-center">Login</h2>
    <form method="POST">
      <div class="form-group pb-3">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
          placeholder="Digite o seu email">
      </div>
      <div class="form-group pb-3">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="senha" class="form-control" id="exampleInputPassword1"
          placeholder="Digite a sua senha">
      </div>
      <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
  </div>
 
</body>

</html>