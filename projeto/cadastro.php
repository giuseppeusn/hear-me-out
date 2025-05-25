<?php
include_once("header.php");
include_once("connect.php");
$oMysql = connect_db();

$cadastroSelecionado = 'usuario';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cadastroSelecionado = $_POST['tipo_cadastro'] ?? 'usuario';

    if (isset($_POST['create_usuario'])) {
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
            header('location: login.php');
        }
    }

    if (isset($_POST['create_critico'])) {
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
            $_SESSION['sucesso_cadastro2'] = true;
            header('location: login.php');
        }
    }

    if (isset($_POST['create_artista'])) {
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
					text: 'Data de formação inválida!',
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
            $query = "INSERT INTO artista (nome,email,biografia,imagem,data_formacao,pais,site_oficial,genero,senha) 
						VALUES ('" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['biografia'] . "', '" . $_POST['imagem'] . "','" . $_POST['data_formacao'] . "', '" . $_POST['pais'] . "', '" . $_POST['site_oficial'] . "', '" . $_POST['genero'] . "', '" . mysqli_real_escape_string($oMysql, password_hash($_POST['senha'], PASSWORD_DEFAULT)) . "' )";
            $resultado = $oMysql->query($query);
            $_SESSION['sucesso_cadastro2'] = true;
            header('location: login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .borda {
            border-width: 2px !important;
        }

        .custom-container {
            width: 50%;
        }

        .custom-btn-size {
            font-size: 18px;
            width: 100%;
        }

        .custom-background {
            background-color: #2e2e2e;
            color: white
        }

        .select-user-button {
            margin-top: 25px;
            margin-bottom: 30px;
        }
    </style>
</head>

<div class="container text-center select-user-button">
    <form method="POST" id="roleForm">
        <div class="btn-group btn-group-lg" role="group" aria-label="Seleção de papel">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
            <label class="btn btn-outline-light borda" for="btnradio1">Usuário</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
            <label class="btn btn-outline-light borda" for="btnradio2">Crítico</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
            <label class="btn btn-outline-light borda" for="btnradio3">Artista</label>
        </div>
    </form>
</div>

<div class="container mt-3 custom-container rounded-5 pt-3 pb-3 px-4 custom-background form-usuario" hidden>
    <div class="text-center">
        <h2>Cadastre-se</h2>
    </div>
    <p style="color:gray" class="mb-1">Campo obrigatório *</p>

    <form method="POST">
        <input type="hidden" name="tipo_cadastro" value="usuario">

        <label class="form-label pt-2">Nome: *</label>
        <input type="text" name="nome" class="form-control" placeholder="Digite aqui o seu nome."
            value="<?php echo $_POST['nome'] ?? ''; ?>">

        <label class="form-label pt-2">Email: *</label>
        <input type="text" name="email" class="form-control" placeholder="Digite aqui o seu email."
            value="<?php echo $_POST['email'] ?? ''; ?>">

        <label class="form-label pt-2">CPF: *</label>
        <input type="text" name="cpf" class="form-control" placeholder="Digite aqui o seu CPF." maxlength="14"
            onkeypress="MascaraCPF(this, event)" value="<?php echo $_POST['cpf'] ?? ''; ?>">

        <label class="form-label pt-2">Data de nascimento: *</label>
        <input type="date" name="data_nasc" class="form-control mb-2"
            placeholder="Coloque a sua data de nascimento aqui." value="<?php echo $_POST['data_nasc'] ?? ''; ?>">

        <label for="genero pt-2">Gênero: *</label>
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
        <input type="password" name="senha" class="form-control" placeholder="Digite aqui a sua senha." class="mb-2">
        <div class="text-center">
            <button type="submit" name="create_usuario" class="btn btn-success mt-4 custom-btn-size">Cadastrar</button>
        </div>
        <div class="mt-2 text-center">
            <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                href="login.php">Já possuo uma conta.</a>
        </div>

    </form>
</div>

<div class="container mt-3 mb-5 custom-container rounded-5 pt-3 pb-3 px-4 custom-background form-critico" hidden>
    <div class="text-center">
        <h2>Cadastre-se como crítico</h2>
    </div>
    <p style="color:gray" class="mb-1">Campo obrigatório *</p>

    <form method="POST">
        <input type="hidden" name="tipo_cadastro" value="critico">

        <label for="nome" class="form-label pt-2">Nome: *</label>
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite aqui o seu nome."
            value="<?php echo $_POST['nome'] ?? ''; ?>">

        <label for="cpf" class="form-label pt-2">CPF: *</label>
        <input type="text" id="cpf" name="cpf" class="form-control" onkeypress="MascaraCPF(this, event)" maxlength="14" placeholder="Digite seu CPF."
            value="<?php echo $_POST['cpf'] ?? ''; ?>">

        <label for="email" class="form-label pt-2">Email: *</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email."
            value="<?php echo $_POST['email'] ?? ''; ?>">

        <label for="data_nasc" class="form-label pt-2">Data de nascimento: *</label>
        <input type="date" id="data_nasc" name="data_nasc" class="form-control"
            value="<?php echo $_POST['data_nasc'] ?? ''; ?>">

        <label for="biografia" class="form-label pt-2">Biografia: *</label>
        <textarea id="biografia" name="biografia" class="form-control" placeholder="Digite a biografia." rows="3"><?php echo $_POST['biografia'] ?? ''; ?></textarea>


        <label for="site" class="form-label pt-2">Portifólio (site, linkedin, redes sociais): *</label>
        <input type="url" id="site" name="site" class="form-control" placeholder="Digite o link."
            value="<?php echo $_POST['site'] ?? ''; ?>">

        <label for="genero" class="form-label pt-2">Gênero: *</label>
        <select name="genero" id="genero" class="form-select mb-1">
            <option value="" disabled <?php echo empty($_POST['genero']) ? 'selected' : ''; ?>>Selecione</option>
            <option value="M" <?php echo ($_POST['genero'] ?? '') === 'M' ? 'selected' : ''; ?>>Masculino</option>
            <option value="F" <?php echo ($_POST['genero'] ?? '') === 'F' ? 'selected' : ''; ?>>Feminino</option>
            <option value="I" <?php echo ($_POST['genero'] ?? '') === 'I' ? 'selected' : ''; ?>>Indefinido</option>
        </select>

        <label for="senha" class="form-label pt-2">Senha: *</label>
        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha."
            autocomplete="off">

        <div class="text-center">
            <button type="submit" name="create_critico" class="btn btn-success mt-4 custom-btn-size">Cadastrar</button>
        </div>

        <div class="mt-2 text-center">
            <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                href="login.php">Já possuo uma conta.</a>
        </div>

    </form>
</div>

<div class="container mt-3 mb-5 custom-container rounded-5 pt-3 pb-3 px-4 custom-background form-artista" hidden>
    <div class="text-center">
        <h2>Cadastre-se como artista</h2>
    </div>
    <p style="color:gray" class="mb-1">Campo obrigatório *</p>

    <form method="POST">
        <input type="hidden" name="tipo_cadastro" value="artista">

        <label for="nome" class="form-label pt-2">Nome: *</label>
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do artista."
            value="<?php echo $_POST['nome'] ?? ''; ?>">

        <label for="email" class="form-label pt-2">Email: *</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Digite o email do artista."
            value="<?php echo $_POST['email'] ?? ''; ?>">

        <label for="biografia" class="form-label pt-2">Biografia: *</label>
        <textarea id="biografia" name="biografia" class="form-control" placeholder="Digite a biografia."
            rows="3"><?php echo $_POST['biografia'] ?? ''; ?></textarea>

        <label for="imagem" class="form-label pt-2">Imagem (URL): *</label>
        <input type="url" id="imagem" name="imagem" class="form-control" placeholder="Link da imagem (.jpg/.png)"
            value="<?php echo $_POST['imagem'] ?? ''; ?>">

        <label for="data_formacao" class="form-label pt-2">Data de formação da banda: *</label>
        <input type="date" id="data_formacao" name="data_formacao" class="form-control"
            value="<?php echo $_POST['data_formacao'] ?? ''; ?>">

        <label for="pais" class="form-label pt-2">País: *</label>
        <input type="text" id="pais" name="pais" class="form-control" placeholder="Digite o país de origem."
            value="<?php echo $_POST['pais'] ?? ''; ?>">

        <label for="site_oficial" class="form-label pt-2">Site Oficial: *</label>
        <input type="url" id="site_oficial" name="site_oficial" class="form-control"
            placeholder="Digite o site oficial." value="<?php echo $_POST['site_oficial'] ?? ''; ?>">

        <label for="genero" class="form-label pt-2">Gênero músical: *</label>
        <input type="text" placeholder="Digite o gênero músical." id="genero" name="genero" class="mb-1 form-control" value="<?php echo $_POST['genero'] ?? '';?>">

        <label for="senha" class="form-label pt-2">Senha: *</label>
        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha."
            autocomplete="off">

        <div class="text-center">
            <button type="submit" name="create_artista" class="btn btn-success mt-4 custom-btn-size">Cadastrar</button>
        </div>
        <div class="mt-2 text-center">
            <a class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                href="login.php">Já possuo uma conta.</a>
        </div>
    </form>
</div>

<script>
    const radios = document.querySelectorAll('input[name="btnradio"]');
    const forms = {
        'usuario': document.querySelector('.form-usuario'),
        'critico': document.querySelector('.form-critico'),
        'artista': document.querySelector('.form-artista'),
    };

    function toggleForms(selected) {
        for (const tipo in forms) {
            forms[tipo].hidden = (tipo !== selected);
        }

        document.getElementById('btnradio1').checked = selected === 'usuario';
        document.getElementById('btnradio2').checked = selected === 'critico';
        document.getElementById('btnradio3').checked = selected === 'artista';
    }

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            const tipo = radio.id === 'btnradio1' ? 'usuario' :
                radio.id === 'btnradio2' ? 'critico' : 'artista';
            toggleForms(tipo);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        <?php if (isset($cadastroSelecionado)): ?>
            toggleForms("<?= $cadastroSelecionado ?>");
        <?php endif; ?>
    });
</script>

</script>
<script src="validarCampos.js"></script>

<body>
</body>

</html>