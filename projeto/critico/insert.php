<?php
	if(isset($_POST['nome']) && isset($_POST['biografia']) && isset($_POST['cpf']) && isset($_POST['email']) && isset($_POST['data_nasc']) && isset($_POST['site']) && isset($_POST['genero'])){
		$oMysql = connect_db();
		$query = "INSERT INTO critico (nome,biografia,cpf,email,data_nasc,site,genero,senha,aprovado) 
					VALUES ('".$_POST['nome']."', '".$_POST['biografia']."', '".$_POST['cpf']."','".$_POST['email']."', '".$_POST['data_nasc']."', '".$_POST['site']."', '".$_POST['genero']."', '".$_POST['senha']."', false)";
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<body>

<div class="container mt-3">
  <h2>Cadastrar crítico</h2>
  <p></p>    

			<form
				method="POST"
			>

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

            <label class="form-label">Gênero:</label><br>

			<select name="genero" class="form-select">
    			<option value="M">Masculino</option>
    			<option value="F">Feminino</option>
    			<option value="I">Indefinido</option>
			</select><br>
			<label class="form-label">Senha:</label>
			<input
					type="password"
					name="senha"
					class="form-control"
					placeholder="Digite aqui a sua senha.">
		
			<button
				type="submit"
				class="btn btn-primary"> Enviar </button>
		
		</form>


  
</div>

</body>
</html>
