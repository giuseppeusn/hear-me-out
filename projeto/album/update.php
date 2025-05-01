<?php
	$oMysql = connect_db();
	$queryReadAlbum = mysqli_query($oMysql,"SELECT * from album where id = ".$_GET['id']);
	if ($listaReadAlbum = mysqli_fetch_assoc($queryReadAlbum)) {
		$nome = $listaReadAlbum['nome'];
		$capa = $listaReadAlbum['capa'];
		$duracao = $listaReadAlbum['duracao'];
		$data_lancamento = $listaReadAlbum['data_lancamento'];
		$qtd_musicas = $listaReadAlbum['qtd_musicas'];
	}
	if(isset($_POST['nome'], $_POST['capa'], $_POST['data_lancamento'], $_POST['duracao'], $_POST['qtd_musicas'])){
		$query = "UPDATE album
			SET nome = '".$_POST['nome']."', 
				capa = '".$_POST['capa']."', 
				data_lancamento = '".$_POST['data_lancamento']."', 
				duracao = '".$_POST['duracao']."',
				qtd_musicas = '".$_POST['qtd_musicas']."'
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
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome" value='<?php echo htmlspecialchars($nome) ?>'>
			</div>

			<div class="mb-3">
				<label for="imagem" class="form-label">capa</label>
				<input type="text" name="capa" class="form-control" placeholder="URL da imagem" value='<?php echo htmlspecialchars($capa) ?>'>
			</div>

			<div class="mb-3">
				<label for="data_lancamento" class="form-label">Data de lançamento</label>
				<input type="date" name="data_lancamento" class="form-control" value='<?php echo htmlspecialchars($data_lancamento)?>'>
			</div>

			<div class="mb-3">
				<label for="duracao" class="form-label">Duração (em segundos)</label>
				<input type="number" name="duracao" class="form-control" placeholder="Digite a duração" value='<?php echo htmlspecialchars($duracao)?>'>
			</div>

			<div class="mb-3">
				<label for="qtd_musicas" class="form-label">Número de músicas</label>
				<input type="number" name="qtd_musicas" class="form-control" placeholder="Digite a quantidade de músicas" value='<?php echo htmlspecialchars($qtd_musicas)?>'>
			</div>


			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
