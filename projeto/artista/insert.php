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

	function validarEmailCPF($cpf, $email)
	{
		if ($oMysql = connect_db()) {
			$cpf = preg_replace('/[^0-9]/', '', $cpf);
			$email = mysqli_real_escape_string($oMysql, $email);

			$query = "SELECT id, cpf, email,
				CASE
					WHEN cpf = '$cpf' AND email = '$email' THEN 'CPF e email'
					WHEN cpf = '$cpf' THEN 'CPF'
					WHEN email = '$email' THEN 'email'
				END AS duplicado
			FROM usuario
			WHERE cpf = '$cpf' OR email = '$email'
			
			UNION
			
			SELECT id, cpf, email,
				CASE
					WHEN cpf = '$cpf' AND email = '$email' THEN 'CPF e email'
					WHEN cpf = '$cpf' THEN 'CPF'
					WHEN email = '$email' THEN 'email'
				END AS duplicado
			FROM critico
			WHERE cpf = '$cpf' OR email = '$email'
			
			UNION
			
			SELECT id, '' AS CPF, email,
				CASE
					WHEN email = '$email' THEN 'email'
					ELSE ''
				END AS duplicado
			FROM artista
			WHERE email = '$email'";

			$resultado = mysqli_query($oMysql, $query);

			if (mysqli_num_rows($resultado) > 0) {
				$resultado_array = mysqli_fetch_assoc($resultado);
				$duplicado = $resultado_array['duplicado'];
				return [true, $duplicado];
			}

			return [false, ""];
		}
	}

	function validarSenha($senha)
	{
		$erros = [];

		if (strlen($senha) < 8) {
			$erros[] = "mínimo de 8 caracteres";
		}
		if (!preg_match('/[a-z]/', $senha)) {
			$erros[] = "uma letra minúscula";
		}
		if (!preg_match('/[A-Z]/', $senha)) {
			$erros[] = "uma letra maiúscula";
		}
		if (!preg_match('/[0-9]/', $senha)) {
			$erros[] = "um número";
		}
		if (!preg_match('/[\W_]/', $senha)) {
			$erros[] = "um caractere especial (ex: !@#$%)";
		}

		if (!empty($erros)) {
			return [true, $erros];
		} else {
			return [false, []];
		}
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
	} elseif (validarData($_POST['data_formacao'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
				Swal.fire({
					icon: 'error',
					title: 'Erro!',
					text: 'Data de nascimento inválida!',
					draggable: true
					})
					</script>";
	} elseif (($array = validarEmailCPF($_POST['cpf'], $_POST['email'])) && $array[0] === true) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Erro!',
				text: 'Já existe um usuário com esse " . $array[1] . ".',
				draggable: true
				})
				</script>";
	} elseif (($validaSenha = validarSenha($_POST['senha'])) && $validaSenha[0] === true) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Erro!',
			html: 'A senha deve conter:<br><ul style=\"text-align: left;\">";
		foreach ($validaSenha[1] as $erro) {
			echo "<li>$erro</li>";
		}
		echo "</ul>',
		})
		</script>";
	} else {
		$oMysql = connect_db();
		$query = "INSERT INTO artista (nome,email,biografia,imagem,data_formacao,pais,site_oficial,genero,senha,aprovado) 
						VALUES ('" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['biografia'] . "', '" . $_POST['imagem'] . "','" . $_POST['data_formacao'] . "', '" . $_POST['pais'] . "', '" . $_POST['site_oficial'] . "', '" . $_POST['genero'] . "', '" . mysqli_real_escape_string($oMysql, password_hash($_POST['senha'], PASSWORD_DEFAULT)) . "', false)";
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
				<input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do artista."
					value="<?php echo $_POST['nome'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="email" class="form-label">Email: *</label>
				<input type="email" id="email" name="email" class="form-control"
					placeholder="Digite o email do artista." value="<?php echo $_POST['email'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="biografia" class="form-label">Biografia: *</label>
				<textarea type="text" id="biografia" name="biografia" class="form-control"
					placeholder="Digite a biografia." value="<?php echo $_POST['biografia'] ?? ''; ?>"></textarea>
			</div>

			<div class="mb-2">
				<label for="imagem" class="form-label">Imagem (URL): *</label>
				<input type="url" id="imagem" name="imagem" class="form-control"
					placeholder="Link da imagem (.jpg/.png)" value="<?php echo $_POST['imagem'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="data_formacao" class="form-label">Data de formação da banda: *</label>
				<input type="date" id="data_formacao" name="data_formacao" class="form-control"
					value="<?php echo $_POST['data_formacao'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="pais" class="form-label">País: *</label>
				<input type="text" id="pais" name="pais" class="form-control" placeholder="Digite o país de origem."
					value="<?php echo $_POST['pais'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="site_oficial" class="form-label">Site Oficial: *</label>
				<input type="url" id="site_oficial" name="site_oficial" class="form-control"
					placeholder="Digite o site oficial." value="<?php echo $_POST['site_oficial'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="genero" class="form-label">Gênero: *</label>
				<select id="genero" name="genero" class="form-select">
					<option value="" disabled <?php echo empty($_POST['genero']) ? 'selected' : ''; ?>>Selecione
					</option>
					<option value="M" <?php echo ($_POST['genero'] ?? '') === 'M' ? 'selected' : ''; ?>>Masculino</option>
					<option value="F" <?php echo ($_POST['genero'] ?? '') === 'F' ? 'selected' : ''; ?>>Feminino</option>
					<option value="I" <?php echo ($_POST['genero'] ?? '') === 'I' ? 'selected' : ''; ?>>Indefinido
					</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="senha" class="form-label">Senha: *</label>
				<input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha."
					autocomplete="off">
			</div>

			<button type="submit" name="create" class="btn btn-primary">Enviar</button>
			
		</form>
	</div>

	<script src="../validarCampos.js"></script>

</body>