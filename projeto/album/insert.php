<?php
	if(isset($_POST['nome']) && isset($_POST['capa']) && isset($_POST['data_lancamento']) && isset($_POST['duracao']) && isset($_POST['qtd_musicas']) && isset($_POST['id_artista'])){
		$oMysql = connect_db();
		$query = "INSERT INTO album (nome,capa,data_lancamento,duracao,qtd_musicas,id_artista) 
					VALUES ('".$_POST['nome']."', '".$_POST['capa']."', '".$_POST['data_lancamento']."', '".$_POST['duracao']."', '".$_POST['qtd_musicas']."', '".$_POST['id_artista']."')";
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container mt-3">
  <h2>Cadastrar álbum</h2>
  <p></p>    

		<form
			method="POST"
			>

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
				

			<label class="form-label">ID do artista:</label>
			<input
				type="number"
				name="id_artista"
				class="form-control"
				placeholder="Digite aqui o número do id do artista">

			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
