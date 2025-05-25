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
if (isset($_SESSION['sucesso_cadastro2'])) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
		Swal.fire({
			title: 'Cadastrado com sucesso!',
      text: 'Seu cadastro passará por uma revisão para ser aprovado.',
			icon: 'success',
			draggable: true,
      timer: 4000,
			});
			</script>";
  unset($_SESSION['sucesso_cadastro2']);
}


$mysql = connect_db();
if ((isset($_POST['email']) && isset($_POST['senha'])) && (!empty($_POST['email']) && !empty($_POST['senha']))) {
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
      $_SESSION['nome'] = $usuario_array['nome'];
      $_SESSION['id'] = $usuario_array['id'];
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
      $_SESSION['id'] = $artista_array['id'];
      $_SESSION['aprovado'] = $artista_array['aprovado']; // 0 para nao aprovado e 1 para aprovado
      $_SESSION['permissao'] = 'artista';
      $_SESSION['nome'] = $artista_array['nome'];
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
      $_SESSION['id'] = $critico_array['id'];
      $_SESSION['aprovado'] = $critico_array['aprovado']; // 0 para nao aprovado e 1 para aprovado
      $_SESSION['permissao'] = 'critico';
      $_SESSION['nome'] = $critico_array['nome'];
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
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <style>
    .custom-container {
      width: 30%;
      min-width: 25%;
      margin-top: 100px;
      background-color: #2e2e2e;
      color: white;
    }

    .custom-input {
      padding: 10px;
    }

    .custom-button {
      font-size: 18px;
      padding: 7px 20px;
    }

    .custom-padding-right {
      padding-right: 4px;
    }

    .custom-padding-left {
      padding-left: 0px;
    }
  </style>
</head>

<body class="custom-body">
  <div class="container h-auto p-4 pb-2 pt-4 rounded-5 custom-container">
    <div class="text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-circle"
        viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
        <path fill-rule="evenodd"
          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
      </svg>
      <h1 class="mt-2">Login</h1>
    </div>
    <form method="POST">
      <div class="input-group has-validation pt-3 pb-3">
        <span class="input-group-text" id="inputGroupPrepend">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill"
            viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
          </svg>
        </span>
        <input type="email" name="email" class="form-control border-0 custom-input" id="validationCustomUsername"
          aria-describedby="inputGroupPrepend" placeholder="Digite o seu email.">
        <div class="invalid-feedback">
          Por favor digite seu email.
        </div>
      </div>
      <div class="input-group has-validation pt-1 pb-3">
        <span class="input-group-text" id="inputGroupPrepend">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
              d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3" />
          </svg>
        </span>
        <input type="password" name="senha" class="form-control border-0 custom-input" id="validationCustomPassword"
          aria-describedby="inputGroupPrepend" placeholder="Digite a sua senha.">
        <div class="invalid-feedback">
          Por favor digite sua senha.
        </div>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-success mt-2 custom-button">Entrar</button>
      </div>
    </form>
    <div class="row mt-3 ms-2">
      <div class="col-7 text-end custom-padding-right">
        <p>Não possui uma conta?</p>
      </div>
      <div class="col-5 custom-padding-left">
        <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
          href="cadastro.php">Registrar-se</a>
      </div>
    </div>
  </div>
 
</body>

</html>