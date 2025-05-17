<?php
	$oMysql = connect_db();
	$queryReadAlbum = mysqli_query($oMysql,"SELECT * from album where id = ".$_GET['id']);
	if ($listaReadAlbum = mysqli_fetch_assoc($queryReadAlbum)) {
		$nome = $listaReadAlbum['nome'];
		$capa = $listaReadAlbum['capa'];
		$data_lancamento = $listaReadAlbum['data_lancamento'];
	}
	if(isset($_POST['nome'], $_POST['capa'], $_POST['data_lancamento'])){
		$query = "UPDATE album
			SET nome = '".$_POST['nome']."', 
				capa = '".$_POST['capa']."', 
				data_lancamento = '".$_POST['data_lancamento']."'
			WHERE id = ".$_GET['id'];
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container mt-3">
  <h2>CRUD - Atualizar Álbum - ID: <?php echo $_GET['id']; ?></h2>
  <p>Preencha os campos abaixo para atualizar o registro:</p>    

		<form
			method="POST"
			>		
			<div class="mb-3">
				<label for="nome" class="form-label">Nome</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome" value='<?php echo ($nome) ?>'>
			</div>

			<div class="mb-3">
				<label for="imagem" class="form-label">capa</label>
				<input type="text" name="capa" class="form-control" placeholder="URL da imagem" value='<?php echo ($capa) ?>'>
			</div>

			<div class="mb-3">
				<label for="data_lancamento" class="form-label">Data de lançamento</label>
				<input type="date" name="data_lancamento" class="form-control" value='<?php echo ($data_lancamento)?>'>
			</div>

			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
