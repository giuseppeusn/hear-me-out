<?php
if (isset($_POST['edit'])) {
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

	function validarEmailCPF($cpf, $email, $id)
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
			WHERE (cpf = '$cpf' OR email = '$email') AND id != '$id'
			
			UNION
			
			SELECT id, cpf, email,
				CASE
					WHEN cpf = '$cpf' AND email = '$email' THEN 'CPF e email'
					WHEN cpf = '$cpf' THEN 'CPF'
					WHEN email = '$email' THEN 'email'
				END AS duplicado
			FROM critico
			WHERE (cpf = '$cpf' OR email = '$email') AND id != '$id'

			UNION
			
			SELECT id, '' AS CPF, email,
				CASE
					WHEN email = '$email' THEN 'email'
					ELSE ''
				END AS duplicado
			FROM artista
			WHERE email = '$email' AND id != '$id'";

			$resultado = mysqli_query($oMysql, $query);

			if (mysqli_num_rows($resultado) > 0) {
				$resultado_array = mysqli_fetch_assoc($resultado);
				$duplicado = $resultado_array['duplicado'];
				return [true, $duplicado];
			}

			return [false, ""];
		}
	}

	if (!camposPreenchidos(['nome', 'biografia', 'cpf', 'email', 'data_nasc', 'site', 'genero'])) {
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
	} elseif (($array = validarEmailCPF($_POST['cpf'], $_POST['email'], $_GET['id'])) && $array[0] === true) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Erro!',
				text: 'Já existe outro usuário com esse " . $array[1] . ".',
				draggable: true
				})
				</script>";
	} else {
		$oMysql = connect_db();
		$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']); // remove mascara do cpf
		$query = "UPDATE critico
			SET nome = '" . $_POST['nome'] . "', 
				biografia = '" . $_POST['biografia'] . "', 
				cpf = '" . $cpf . "', 
				email = '" . $_POST['email'] . "', 
				data_nasc = '" . $_POST['data_nasc'] . "', 
				site = '" . $_POST['site'] . "', 
				genero = '" . $_POST['genero'] . "',
				senha = '" . $_POST['senha'] . "' 
			WHERE id = " . $_GET['id'];
		$resultado = $oMysql->query($query);
		$_SESSION['sucesso_edit'] = true;
		header('location: index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<body>

	<div class="container mt-3">
		<h2>Atualizar cadastro crítico - ID: <?php echo $_GET['id']; ?></h2>
		<p>Preencha os campos abaixo para atualizar o registro:</p>

		<form method="POST">
			<div class="mb-2">
				<label for="nome" class="form-label">Nome:</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome">
			</div>

			<div class="mb-2">
				<label for="biografia" class="form-label">Biografia:</label>
				<textarea name="biografia" class="form-control" placeholder="Digite a biografia"></textarea>
			</div>

			<div class="mb-2">
				<label for="cpf" class="form-label">CPF: </label>
				<input type="text" name="cpf" class="form-control" placeholder="Digite o CPF" maxlength="14"
					onkeypress="MascaraCPF(this, event)">
			</div>

			<div class="mb-2">
				<label for="email" class="form-label">Email: </label>
				<input type="text" name="email" class="form-control" placeholder="Digite o email">
			</div>

			<div class="mb-2">
				<label for="data_nasc" class="form-label">Data Nascimento: </label>
				<input type="date" name="data_nasc" class="form-control">
			</div>

			<div class="mb-2">
				<label for="site" class="form-label">Site: </label>
				<input type="text" name="site" class="form-control" placeholder="Digite o site">
			</div>

			<div class="mb-2">
				<label for="genero" class="">Qual o seu Gênero?</a>
					<select name="genero" class="form-select mt-1">
						<option value="" disabled selected>Selecione</option>
						<option value="M">Masculino</option>
						<option value="F">Feminino</option>
						<option value="I">Indefinido</option>
					</select>
			</div>
			<div class="mb-3">
				<label for="senha" class="form-label">Senha: (vazio para não mudar)</label>
				<input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha."
					autocomplete="off">
			</div>
			<button type="submit" name="edit" class="btn btn-primary">Atualizar</button>
			<script src="../validarCampos.js"></script>
		</form>
	</div>

</body>

</html>