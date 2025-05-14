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
		$query = "INSERT INTO critico (nome,biografia,cpf,email,data_nasc,site,genero,senha,aprovado) 
					VALUES ('" . $_POST['nome'] . "', '" . $_POST['biografia'] . "', '" . $_POST['cpf'] . "','" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $_POST['site'] . "', '" . $_POST['genero'] . "', '" . $_POST['senha'] . "', false)";
		$resultado = $oMysql->query($query);
		header('location: index.php');
	}
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<body>

	<div class="container mt-3">
		<h2>Cadastrar crítico</h2>
		<p></p>

		<form
			method="POST">

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
				onkeypress="MascaraCPF(this, event)"
				maxlength="14"
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
				name="create"
				class="btn btn-primary"> Enviar </button>

		</form>

		<script src="../validarCampos.js"></script>

	</div>

</body>

</html>