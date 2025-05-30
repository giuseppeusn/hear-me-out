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

	if (!camposPreenchidos(['nome', 'email', 'cpf', 'data_nasc', 'senha', 'genero'])) {
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
		$query = "INSERT INTO usuario (nome,email,cpf,data_nasc,senha,genero,permissao) 
							VALUES ('" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $cpf . "', '" . $_POST['data_nasc'] . "', '" . mysqli_real_escape_string($oMysql, password_hash($_POST['senha'], PASSWORD_DEFAULT)) . "', '" . $_POST['genero'] . "', 'normal')";
		$resultado = $oMysql->query($query);
		$_SESSION['sucesso_cadastro'] = true;
		header('location: index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<body>

	<div class="container mt-3">
		<h2>Cadastrar usuário</h2>
		<p style="color:gray" class="mb-1">Campo obrigatório *</p>

		<form method="POST">

			<label class="form-label pt-2">Nome: *</label>
			<input type="text" name="nome" class="form-control" placeholder="Digite aqui o seu Nome"
				value="<?php echo $_POST['nome'] ?? ''; ?>">

			<label class="form-label pt-2">Email: *</label>
			<input type="text" name="email" class="form-control" placeholder="Digite aqui o seu texto."
				value="<?php echo $_POST['email'] ?? ''; ?>">

			<label class="form-label pt-2">CPF:</label>
			<input type="text" name="cpf" class="form-control" placeholder="Digite aqui o seu CPF" maxlength="14"
				onkeypress="MascaraCPF(this, event)" value="<?php echo $_POST['cpf'] ?? ''; ?>">

			<label class="form-label pt-2">Data de nascimento: *</label>
			<input type="date" name="data_nasc" class="form-control mb-2"
				placeholder="Coloque a sua data de nascimento aqui." value="<?php echo $_POST['data_nasc'] ?? ''; ?>">

			<label for="genero pt-2">Qual o seu Gênero? *</label>
			<select name="genero" class="form-select mt-2 mb-1">
				<option value="" disabled <?php echo empty($_POST['genero']) ? 'selected' : ''; ?>>Selecione</option>
				<option value="M" <?php if (isset($_POST['genero']) && $_POST['genero'] == 'M')
					echo 'selected'; ?>>
					Masculino</option>
				<option value="F" <?php if (isset($_POST['genero']) && $_POST['genero'] == 'F')
					echo 'selected'; ?>>
					Feminino</option>
				<option value="I" <?php echo ($_POST['genero'] ?? '') === 'I' ? 'selected' : ''; ?>>Indefinido</option>
			</select>
			<label class="form-label pt-2">Coloque a sua senha: *</label>
			<input type="password" name="senha" class="form-control" placeholder="Digite aqui a sua senha."
				class="mb-2">
			<button type="submit" name="create" class="btn btn-primary mt-2">Enviar
			</button>
	</div>

	</form>
	</div>

	<script src="../js/validarCampos.js"></script>
</body>

</html>