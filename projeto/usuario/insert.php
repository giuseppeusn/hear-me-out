<?php

if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['CPF'])){
			$oMysql = connect_db();
			$query = "INSERT INTO usuario (nome,email,CPF) 
						VALUES ('".$_POST['nome']."', '".$_POST['email']."', '".$_POST['CPF']."')";
			$resultado = $oMysql->query($query);
			header('location: index.php');
		}
		
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container mt-3">
  <h2>Cadastrar usuário</h2>
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
            

			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
