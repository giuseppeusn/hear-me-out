<?php

if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['CPF']) && isset($_POST['email']) && isset($_POST['data_nasc']) && isset($_POST['senha']) && isset($_POST['genero']) && isset($_POST['permissao'])){
			$oMysql = connect_db();
			$query = "INSERT INTO usuario (nome,email,CPF) 
						VALUES ('".$_POST['nome']."', '".$_POST['email']."', '".$_POST['CPF']."')";
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
  <h2>CADASTRO - USUÁRIO</h2>
  <p></p>    

		<form
			method="POST"
			action="index.php?page=1">

            <label class="form-label">nome:</label>
			<input
				type="text"
				name="nome"
				class="form-control"
				placeholder="Digite aqui o seu Nome">


            <label class="form-label">Email:</label>
			<input
				type="text"
				name="email"
				class="form-control"
				placeholder="Digite aqui o seu texto.">


            <label class="form-label">CPF:</label>
			<input
				type="text"
				name="CPF"
				class="form-control"
				placeholder="Digite aqui o seu CPF.">
            
			<label class="form-label">Data de nascimento:</label>
			<input
				type="date"
				name="data_nasc"
				class="form-control"
				placeholder="Coloque a sua data de nascimento aqui.">

			<label class="form-label">Coloque a sua senha:</label>
			<input
				type="password"
				name="senha"
				class="form-control"
				placeholder="Digite aqui a sua senha.">

			<div class="mb-3">
			<labe for="genero">	Qual o seu Gênero?</a>
			<select name="genero">
				<option value="M">Masculino</option>
				<option value="F">Feminino</option>
			</select>
			</div>

            

            


			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
