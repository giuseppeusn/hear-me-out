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
	} else {
		$oMysql = connect_db();
		$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']); // remove mascara do cpf
		$query = "UPDATE usuario 
			SET nome = '" . $_POST['nome'] . "', 
				email = '" . $_POST['email'] . "', 
				CPF = '" . $cpf . "',
				data_nasc = '" . $_POST['data_nasc'] . "',
				senha = '" . $_POST['senha'] . "',
				genero = '" . $_POST['genero'] . "'
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
		<h2>Atualizar cadastro usuário - ID: <?php echo $_GET['id']; ?></h2>
		<p>Preencha os campos abaixo para atualizar o registro:</p>

		<form method="POST">
			<div class="mb-3">
				<label for="nome" class="form-label">Nome</label>
				<input type="text" name="nome" class="form-control" placeholder="Digite o nome">
			</div>

			<div class="mb-3">
				<label for="email" class="form-label">Email:</label>
				<input type="email" name="email" class="form-control" placeholder="Digite o email"></input>
			</div>

			<div class="mb-3">
				<label for="cpf" class="form-label">CPF</label>
				<input type="text" name="cpf" class="form-control" placeholder="CPF" maxlength="14"
					onkeypress="MascaraCPF(this, event)">
			</div>

			<div class="mb-3">
				<label for="data_nasc" class="form-label">data_nasc</label>
				<input type="date" name="data_nasc" class="form-control" placeholder="data_nasc">
			</div>

			<div class="mb-3">
				<label for="senha" class="form-label">Senha:</label>
				<input type="password" name="senha" class="form-control" placeholder="Senha">
			</div>

			<div class="mb-3">
				<label for="genero">Qual o seu Gênero?</a>
					<select name="genero">
						<option value="M">Masculino</option>
						<option value="F">Feminino</option>
					</select>
			</div>

			<div class="mb-3">
				<label for="permissoes" value="normal"></label>
			</div>

			<button type="submit" name="edit" class="btn btn-primary">Atualizar</button>
		</form>
		<script src="../validarCampos.js"></script>
	</div>

</body>

</html>