<?php



if(isset($_POST['nome']) && isset($_POST['biografia']) && isset($_POST['cpf']) && isset($_POST['email']) && isset($_POST['data_nasc']) && isset($_POST['site']) && isset($_POST['genero'])){
			$oMysql = connect_db();
			$query = "INSERT INTO critico (nome,biografia,cpf,email,data_nasc,site,genero) 
						VALUES ('".$_POST['nome']."', '".$_POST['biografia']."', '".$_POST['cpf']."','".$_POST['email']."', '".$_POST['data_nasc']."', '".$_POST['site']."', '".$_POST['genero']."' )";
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
  <h2>CADASTRO - CRITICO</h2>
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


            <label class="form-label">cpf:</label>
			<input
				type="text"
				name="cpf"
				class="form-control"
				placeholder="Digite aqui o seu cpf.">
            

            <label class="form-label">email:</label>
			<input
				type="text"
				name="email"
				class="form-control">

            <label class="form-label">data nascimento:</label> 
            <input
                type="date"
                name="data_nasc"
                class="form-control">

            <label class="form-label">Site</label>
            <input
                type="text"
                name="site"
                class="form-control"
                placeholder="Digite aqui seu site">

            <label class="form-label">Gênero:</label>
            <label class="form-label">Gênero:</label>

			<select name="genero" class="form-control">
    			<option value="M">Masculino</option>
    			<option value="F">Feminino</option>
    			<option value="I">Indefinido</option>
			</select>
		
			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
