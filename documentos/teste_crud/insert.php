<?php



if(isset($_POST['nome']) && isset($_POST['biografia']) && isset($_POST['imagem']) && isset($_POST['data_formacao']) && isset($_POST['pais']) && isset($_POST['site_oficial']) && isset($_POST['genero'])){
			$oMysql = connect_db();
			$query = "INSERT INTO artista (nome,biografia,imagem,data_formacao,pais,site_oficial,genero) 
						VALUES ('".$_POST['nome']."', '".$_POST['biografia']."', '".$_POST['imagem']."','".$_POST['data_formacao']."', '".$_POST['pais']."', '".$_POST['site_oficial']."', '".$_POST['genero']."' )";
			$resultado = $oMysql->query($query);
			header('location: index.php');
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Lista de Registros</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>CADASTRO - ARTISTA</h2>
  <p></p>    

		<form
			method="POST"
			action="index.php?page=1">

            <label class="form-label">nome:</label>
			<input
				type="text"
				name="nome"
				class="form-control"
				placeholder="Digite aqui o seu texto.">


            <label class="form-label">biografia:</label>
			<input
				type="text"
				name="biografia"
				class="form-control"
				placeholder="Digite aqui o seu texto.">


            <label class="form-label">imagem:</label>
			<input
				type="text"
				name="imagem"
				class="form-control"
				placeholder="Digite aqui o seu texto.">
            

            <label class="form-label">Data de formacao da banda:</label>
			<input
				type="date"
				name="data_formacao"
				class="form-control">

            <label class="form-label">País:</label> 
            <input
                type="text"
                name="pais"
                class="form-control"
                placeholder="Digite aqui o país de origem do artista.">

            <label class="form-label">Site Oficial:</label>
            <input
                type="text"
                name="site_oficial"
                class="form-control"
                placeholder="Digite aqui o site oficial do artista.">

            <label class="form-label">Gênero:</label>
            <input
                type="text"
                name="genero"
                class="form-control"
                placeholder="Digite aqui o gênero musical do artista.">

				
			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
