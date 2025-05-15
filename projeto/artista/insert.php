<?php
if (isset($_POST['create'])) {
	function camposPreenchidos($campos)
	{
		$campos_vazios = array();
		foreach ($campos as $i) {
			if (!isset($_POST[$i]) || empty(trim($_POST[$i]))) {
				$campos_vazios[] = $i;
			}
		}
		if (count($campos_vazios) > 0) {
			$_SESSION['campos_vazios'] = $campos_vazios;
			return false;
		} else {
			return true;
		}
	}
	function validarData($data)
	{
		$d = new DateTime($data);
		$dataAtual = new DateTime();

		if ($d->format('Y') < 1900) {
			return true;
		}
		if ($d > $dataAtual) {
			return true;
		}
		return false; // false = nao vai entrar no if / data correta
	}

	if (!camposPreenchidos(['nome', 'email', 'biografia', 'imagem', 'data_formacao', 'pais', 'site_oficial', 'genero', 'senha'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
			Swal.fire({
					icon: 'error',
					title: 'Erro!',
					text: 'Os campos " . implode(', ', $_SESSION['campos_vazios']) . " não foram preenchidos!',
					draggable: true
					})
				</script>";
		unset($_SESSION['campos_vazios']);
	}elseif (validarData($_POST['data_formacao'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
				Swal.fire({
					icon: 'error',
					title: 'Erro!',
					text: 'Data de nascimento inválida!',
					draggable: true
					})
					</script>";
	} else {
		$oMysql = connect_db();
		$query = "INSERT INTO artista (nome,email,biografia,imagem,data_formacao,pais,site_oficial,genero,senha) 
						VALUES ('" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['biografia'] . "', '" . $_POST['imagem'] . "','" . $_POST['data_formacao'] . "', '" . $_POST['pais'] . "', '" . $_POST['site_oficial'] . "', '" . $_POST['genero'] . "', '" . $_POST['senha'] . "' )";
		$resultado = $oMysql->query($query);
		$_SESSION['sucesso_cadastro'] = true;
		header('location: index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<body>

	<div class="container mt-3">
		<h2>Cadastrar artista</h2>
		<p style="color:gray" class="mb-2 mt-0">campo obrigatório*</p>
		<form method="POST">

			<label class="form-label">Nome: *</label>
			<input type="text" name="nome" class="form-control mb-2" placeholder="Digite aqui o seu texto.">
			<label class="form-label">Email: *</label>
			<input type="email" name="email" class="form-control mb-2" placeholder="Digite aqui o seu texto.">
			<label class="form-label">Biografia: *</label>
			<input type="text" name="biografia" class="form-control mb-2" placeholder="Digite aqui o seu texto.">
			<label class="form-label">Imagem: *</label>
			<input type="text" name="imagem" class="form-control mb-2" placeholder="Digite aqui o seu texto.">
			<label class="form-label">Data de formacao da banda: *</label>
			<input type="date" name="data_formacao" class="form-control mb-2">
			<label class="form-label">País: *</label>
			<input type="text" name="pais" class="form-control mb-2"
				placeholder="Digite aqui o país de origem do artista.">
			<label class="form-label">Site Oficial: *</label>
			<input type="text" name="site_oficial" class="form-control mb-2"
				placeholder="Digite aqui o site oficial do artista.">
			<label class="form-label">Gênero: *</label>
			<select name="genero" class="form-select mb-2">
				<option value="M">Masculino</option>
				<option value="F">Feminino</option>
				<option value="I">Indefinido</option>
			</select>
			<label class="form-label">Senha: *</label>
			<input type="password" name="senha" class="form-control mb-3" placeholder="Digite aqui a sua senha.">
			<button type="submit" name="create" class="btn btn-primary"> Enviar </button>
			<script src="../validarCampos.js"></script>
		</form>
	</div>

</body>

</html>