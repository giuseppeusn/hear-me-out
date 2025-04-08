<?php
	if(isset($_POST['nome'], $_POST['biografia'], $_POST['cpf'], $_POST['email'], $_POST['data_nasc'], $_POST['site'], $_POST['genero'])){
		$oMysql = connect_db();
		$query = "UPDATE critico
			SET nome = '".$_POST['nome']."', 
				biografia = '".$_POST['biografia']."', 
				cpf = '".$_POST['cpf']."', 
				email = '".$_POST['email']."', 
				data_nasc = '".$_POST['data_nasc']."', 
				site = '".$_POST['site']."', 
				genero = '".$_POST['genero']."' 
			WHERE id = ".$_GET['id'];
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Atualizar Registro</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>CRUD - Atualizar - ID: <?php echo $_GET['id']; ?></h2>
  <p>Preencha os campos abaixo para atualizar o registro:</p>    

		<form
			method="POST"
			>		
			<div class="mb-3">
				<label for="nome" class="form-label">Nome</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome">
			</div>

			<div class="mb-3">
				<label for="biografia" class="form-label">Biografia</label>
				<textarea name="biografia" class="form-control" placeholder="Digite a biografia"></textarea>
			</div>

			<div class="mb-3">
				<label for="cpf" class="form-label">CPF</label>
				<input type="text" name="cpf" class="form-control" placeholder="Digite o CPF">
			</div>

			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="text" name="email" class="form-control">
			</div>

			<div class="mb-3">
				<label for="data_nasc" class="form-label">Data Nascimento</label>
				<input type="date" name="data_nasc" class="form-control">
			</div>

			<div class="mb-3">
				<label for="site" class="form-label">Site</label>
				<input type="text" name="site" class="form-control" placeholder="Digite o site">
			</div>

			<div class="mb-3">
				<label for="genero" class="form-label">Gênero</label>
				<input type="text" name="genero" class="form-control" placeholder="Digite o gênero">
			</div>

			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
