<?php
	if(isset($_POST['nome'], $_POST['email'], $_POST['CPF'])){
		$oMysql = connect_db();
		$query = "UPDATE usuario 
			SET nome = '".$_POST['nome']."', 
				email = '".$_POST['email']."', 
				CPF = '".$_POST['CPF']."'
			WHERE id = ".$_GET['id'];
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container mt-3">
  <h2>Atualizar cadastro usuário - ID: <?php echo $_GET['id']; ?></h2>
  <p>Preencha os campos abaixo para atualizar o registro:</p>    

		<form
			method="POST"
			>		
			<div class="mb-3">
				<label for="nome" class="form-label">Nome</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome">
			</div>
			

			<div class="mb-3">
				<label for="email" class="form-label">Email:</label>
				<textarea name="email" class="form-control" placeholder="Digite o email"></textarea>
			</div>

			<div class="mb-3">
				<label for="CPF" class="form-label">CPF</label>
				<input type="text" name="CPF" class="form-control" placeholder="CPF">
			</div>


			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
