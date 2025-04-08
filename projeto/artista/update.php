<?php
	if(isset($_POST['nome'], $_POST['biografia'], $_POST['imagem'], $_POST['data_formacao'], $_POST['pais'], $_POST['site_oficial'], $_POST['genero'])){
		$oMysql = connect_db();
		$query = "UPDATE artista 
			SET nome = '".$_POST['nome']."', 
				biografia = '".$_POST['biografia']."', 
				imagem = '".$_POST['imagem']."', 
				data_formacao = '".$_POST['data_formacao']."', 
				pais = '".$_POST['pais']."', 
				site_oficial = '".$_POST['site_oficial']."', 
				genero = '".$_POST['genero']."' 
			WHERE id = ".$_GET['id'];
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
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
				<label for="imagem" class="form-label">Imagem</label>
				<input type="text" name="imagem" class="form-control" placeholder="URL da imagem">
			</div>

			<div class="mb-3">
				<label for="data_formacao" class="form-label">Data de Formação</label>
				<input type="date" name="data_formacao" class="form-control">
			</div>

			<div class="mb-3">
				<label for="pais" class="form-label">País</label>
				<input type="text" name="pais" class="form-control" placeholder="Digite o país">
			</div>

			<div class="mb-3">
				<label for="site_oficial" class="form-label">Site Oficial</label>
				<input type="text" name="site_oficial" class="form-control" placeholder="Digite o site oficial">
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
