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
			if ($digito == 10) $digito = 0;
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
	} else {
		$oMysql = connect_db();
		$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']); // remove mascara do cpf
		$query = "INSERT INTO critico (nome,biografia,cpf,email,data_nasc,site,genero,senha,aprovado) 
					VALUES ('" . $_POST['nome'] . "', '" . $_POST['biografia'] . "', '" . $cpf . "','" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $_POST['site'] . "', '" . $_POST['genero'] . "', '" . $_POST['senha'] . "', false)";
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
				<input type="text" id="nome" name="nome" class="form-control" placeholder="Digite aqui o seu nome." value="<?php echo $_POST['nome'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="biografia" class="form-label">Biografia: *</label>
				<input type="text" id="biografia" name="biografia" class="form-control" placeholder="Digite aqui sua biografia." value="<?php echo $_POST['biografia'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="cpf" class="form-label">CPF: *</label>
				<input type="text" id="cpf" name="cpf" class="form-control" onkeypress="MascaraCPF(this, event)" maxlength="14"
					placeholder="Digite seu CPF." value="<?php echo $_POST['cpf'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="email" class="form-label">Email:</label>
				<input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email." value="<?php echo $_POST['email'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="data_nasc" class="form-label">Data de nascimento: *</label>
				<input type="date" id="data_nasc" name="data_nasc" class="form-control" value="<?php echo $_POST['data_nasc'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="site" class="form-label">Site: *</label>
				<input type="url" id="site" name="site" class="form-control" placeholder="Digite o link do seu site." value="<?php echo $_POST['site'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="genero" class="form-label">Gênero: *</label>
				<select name="genero" id="genero" class="form-select">
					<option value="" disabled <?php echo empty($_POST['genero']) ? 'selected' : ''; ?>>Selecione</option>
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
		</form>

		<script src="../validarCampos.js"></script>
	</div>

	<script src="../validarCampos.js"></script>


</body>

</html>
