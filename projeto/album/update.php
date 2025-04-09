<?php
	if(isset($_POST['nome'], $_POST['capa'], $_POST['data_lancamento'], $_POST['duracao'], $_POST['qtd_musicas'])){
		$oMysql = connect_db();
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
  <h2>Atualizar cadastro álbum - ID: <?php echo $_GET['id']; ?></h2>
  <p>Preencha os campos abaixo para atualizar o registro:</p>    

		<form
			method="POST"
			>		
			<div class="mb-3">
				<label for="nome" class="form-label">Nome</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome">
			</div>

			<div class="mb-3">
				<label for="imagem" class="form-label">capa</label>
				<input type="text" name="capa" class="form-control" placeholder="URL da imagem">
			</div>

			<div class="mb-3">
				<label for="data_lancamento" class="form-label">Data de lançamento</label>
				<input type="date" name="data_lancamento" class="form-control">
			</div>

			<div class="mb-3">
				<label for="duracao" class="form-label">Duração (em segundos)</label>
				<input type="number" name="duracao" class="form-control" placeholder="Digite a duração">
			</div>

			<div class="mb-3">
				<label for="qtd_musicas" class="form-label">Número de músicas</label>
				<input type="number" name="qtd_musicas" class="form-control" placeholder="Digite a quantidade de músicas">
			</div>


			<button type="submit" class="btn btn-primary">Atualizar</button>
		</form>
</div>

</body>
</html>
