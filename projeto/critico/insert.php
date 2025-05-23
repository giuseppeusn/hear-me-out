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

	function validarCPF($cpf)
	{
		$cpf = preg_replace('/[^0-9]/', '', $cpf);
		if (strlen($cpf) !== 11) {
			return true;
		}
		if (preg_match('/^(\d)\1{10}$/', $cpf)) {
			return true;
		}
		for ($t = 9; $t < 11; $t++) {
			$soma = 0;
			for ($c = 0; $c < $t; $c++) {
				$soma += $cpf[$c] * (($t + 1) - $c);
			}
			$digito = (10 * $soma) % 11;
			if ($digito == 10)
				$digito = 0;
			if ($cpf[$t] != $digito) {
				return true;
			}
		}
		return false; # false = nao vai entrar no if // cpf correto
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


	if (!camposPreenchidos(['nome', 'biografia', 'cpf', 'email', 'data_nasc', 'site', 'genero', 'senha'])) {
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
	} elseif (validarCPF($_POST['cpf'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Erro!',
				text: 'CPF inválido!',
				draggable: true
				})
				</script>";
	} elseif (validarData($_POST['data_nasc'])) {
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
		$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']); // remove mascara do cpf
		$query = "INSERT INTO critico (nome,biografia,cpf,email,data_nasc,site,genero,senha,aprovado) 
					VALUES ('" . $_POST['nome'] . "', '" . $_POST['biografia'] . "', '" . $cpf . "','" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $_POST['site'] . "', '" . $_POST['genero'] . "', '" . mysqli_real_escape_string($oMysql, password_hash($_POST['senha'], PASSWORD_DEFAULT)) . "', false)";
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
		<h2>Cadastrar crítico</h2>
		<p style="color:gray" class="mb-2">Campo obrigatório *</p>

		<form method="POST">
			<div class="mb-2">
				<label for="nome" class="form-label">Nome: *</label>
				<input type="text" id="nome" name="nome" class="form-control" placeholder="Digite aqui o seu nome."
					value="<?php echo $_POST['nome'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="biografia" class="form-label">Biografia: *</label>
				<textarea type="text" id="biografia" name="biografia" class="form-control"
					placeholder="Digite a biografia." value="<?php echo $_POST['biografia'] ?? ''; ?>"></textarea>
			</div>

			<div class="mb-2">
				<label for="cpf" class="form-label">CPF: *</label>
				<input type="text" id="cpf" name="cpf" class="form-control" onkeypress="MascaraCPF(this, event)"
					maxlength="14" placeholder="Digite seu CPF." value="<?php echo $_POST['cpf'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="email" class="form-label">Email:</label>
				<input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email."
					value="<?php echo $_POST['email'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="data_nasc" class="form-label">Data de nascimento: *</label>
				<input type="date" id="data_nasc" name="data_nasc" class="form-control"
					value="<?php echo $_POST['data_nasc'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="site" class="form-label">Site: *</label>
				<input type="url" id="site" name="site" class="form-control" placeholder="Digite o link do seu site."
					value="<?php echo $_POST['site'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="genero" class="form-label">Gênero: *</label>
				<select name="genero" id="genero" class="form-select">
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

</html>