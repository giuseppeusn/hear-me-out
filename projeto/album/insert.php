<?php
$id_artista = $_SESSION['id_artista'];
if(isset($_POST['nome']) && isset($_POST['capa']) && isset($_POST['data_lancamento']) && isset($_POST['duracao']) && isset($_POST['qtd_musicas'])){
			$nome = trim($_POST['nome']);
			$capa = trim($_POST['capa']);
			$data = $_POST['data_lancamento'];
			$duracao = $_POST['duracao'];
			$qtd_musicas = $_POST['qtd_musicas'];

			$erros = [];

			if (empty($nome)) $erros[] = "O nome do álbum é obrigatório.";
			if (empty($capa)) $erros[] = "O link da capa é obrigatório.";
			if (!filter_var($capa, FILTER_VALIDATE_URL)) $erros[] = "O link da capa não é válido.";
			if (empty($data)) $erros[] = "A data de lançamento é obrigatória.";
			if (!is_numeric($duracao) || $duracao <= 0) $erros[] = "A duração deve ser um número positivo.";
			if (!is_numeric($qtd_musicas) || $qtd_musicas <= 0) $erros[] = "A quantidade de músicas deve ser um número positivo.";
			if(empty($erros)){
				$oMysql = connect_db();
				$query = "INSERT INTO album (nome,capa,data_lancamento,duracao,qtd_musicas,id_artista) 
							VALUES ('$nome', '$capa', '$data', '$duracao', '$qtd_musicas','$id_artista')";
				$resultado = $oMysql->query($query);
				header('location: index.php');
				exit; } else { foreach ($erros as $erro){
					echo "<div style='color:red; margin-left:20px;'>$erro</div>";
				}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container mt-3">
  <h2>CADASTRO - ALBUM</h2>
  <p></p>    

		<form
			method="POST"
			action="index.php?page=1">

            <label class="form-label">nome:</label>
			<input
				type="text"
				name="nome"
				class="form-control"
				placeholder="Digite aqui o nome do album">


            <label class="form-label">Capa:</label>
			<input
				type="text"
				name="capa"
				class="form-control"
				placeholder="Digite aqui o link da capa">
            

            <label class="form-label">Data de criação do album:</label>
			<input
				type="date"
				name="data_lancamento"
				class="form-control">


            <label class="form-label">Duração do álbum (em segundos):</label>
            <input
                type="number"
                name="duracao"
                class="form-control"
                placeholder="Digite aqui a duração do álbum">

			<label class="form-label">Número de música:</label>
			<input
				type="number"
				name="qtd_musicas"
				class="form-control"
				placeholder="Digite aqui o número de músicas">
				



			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
