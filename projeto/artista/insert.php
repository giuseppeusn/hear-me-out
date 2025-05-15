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
<html lang="pt-BR">

<body>
	<div class="container mt-3">
		<h2>Cadastrar artista</h2>
		<p style="color:gray" class="mb-2 mt-0">Campo obrigatório *</p>

		<form method="POST">
			<div class="mb-2">
				<label for="nome" class="form-label">Nome: *</label>
				<input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do artista." value="<?php echo $_POST['nome'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="email" class="form-label">Email: *</label>
				<input type="email" id="email" name="email" class="form-control" placeholder="Digite o email do artista." value="<?php echo $_POST['email'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="biografia" class="form-label">Biografia: *</label>
				<input type="text" id="biografia" name="biografia" class="form-control" placeholder="Digite a biografia." value="<?php echo $_POST['biografia'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="imagem" class="form-label">Imagem (URL): *</label>
				<input type="url" id="imagem" name="imagem" class="form-control" placeholder="Link da imagem (.jpg/.png)" value="<?php echo $_POST['imagem'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="data_formacao" class="form-label">Data de formação da banda: *</label>
				<input type="date" id="data_formacao" name="data_formacao" class="form-control" value="<?php echo $_POST['data_formacao'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="pais" class="form-label">País: *</label>
				<input type="text" id="pais" name="pais" class="form-control" placeholder="Digite o país de origem." value="<?php echo $_POST['pais'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="site_oficial" class="form-label">Site Oficial: *</label>
				<input type="url" id="site_oficial" name="site_oficial" class="form-control" placeholder="Digite o site oficial." value="<?php echo $_POST['site_oficial'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="genero" class="form-label">Gênero: *</label>
				<select id="genero" name="genero" class="form-select">
					<option value="" disabled <?php echo empty($_POST['genero']) ? 'selected' : ''; ?>>Selecione o gênero</option>
					<option value="M" <?php echo ($_POST['genero'] ?? '') === 'M' ? 'selected' : ''; ?>>Masculino</option>
					<option value="F" <?php echo ($_POST['genero'] ?? '') === 'F' ? 'selected' : ''; ?>>Feminino</option>
					<option value="I" <?php echo ($_POST['genero'] ?? '') === 'I' ? 'selected' : ''; ?>>Indefinido</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="senha" class="form-label">Senha: *</label>
				<input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha." autocomplete="off">
			</div>

			<button type="submit" name="create" class="btn btn-primary">Enviar</button>

			<script src="../validarCampos.js"></script>
		</form>
	</div>

	<script src="../validarCampos.js"></script>

</body>
