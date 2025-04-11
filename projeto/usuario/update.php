<?php
	if(isset($_POST['nome'], $_POST['email'], $_POST['CPF'], $_POST['data_nasc'], $_POST['senha'], $_POST['genero'], $_POST['permissao'])){
		$oMysql = connect_db();
		$query = "UPDATE usuario 
			SET nome = '".$_POST['nome']."', 
				email = '".$_POST['email']."', 
				CPF = '".$_POST['CPF']."',
				data_nasc = '".$_POST['data_nasc']."',
				senha = '".$_POST['senha']."',
				genero = '".$_POST['genero']."'
				permissao = '".$_POST['permissao']."'  

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
				<input type="number" name="CPF" class="form-control" placeholder="CPF">
			</div>


			<div class="mb-3">
				<label for="data_nasc" class="form-label">data_nasc</label>
				<input type="date" name="data_nasc" class="form-control" placeholder="data_nasc">
			</div>

			<div class="mb-3">
				<label for="senha" class="form-label">Senha:</label>
				<input type="password" name="senha" class="form-control" placeholder="senha">
			</div>

			<div class="mb-3">
			<labe for="genero">	Qual o seu Gênero?</a>
			<select name="genero">
				<option value="M">Masculino</option>
				<option value="F">Feminino</option>
			</select>
			</div>

			<div class="mb-3">
			<labe for="permissoes" value="normal"></labe>
			</div>		


			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
