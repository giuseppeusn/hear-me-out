<?php
include_once "../connect.php";

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
		return false;
	}

	if (!camposPreenchidos(['nome', 'capa', 'data_lancamento', 'duracao', 'qtd_musicas'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Os campos " . implode(', ', $_SESSION['campos_vazios']) . " não foram preenchidos!',
                draggable: true
            });
        </script>";
		unset($_SESSION['campos_vazios']);
	} elseif (!filter_var($_POST['capa'], FILTER_VALIDATE_URL)) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'O link da capa não é válido!',
                draggable: true
            });
        </script>";
	} elseif (!is_numeric($_POST['duracao']) || $_POST['duracao'] <= 0) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'A duração deve ser um número positivo!',
                draggable: true
            });
        </script>";
	} elseif (!is_numeric($_POST['qtd_musicas']) || $_POST['qtd_musicas'] <= 0) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'A quantidade de músicas deve ser um número positivo!',
                draggable: true
            });
        </script>";
	} elseif (validarData($_POST['data_lancamento'])) {
		echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
		echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'A data de lançamento é inválida!',
                draggable: true
            });
        </script>";
	} else {
		$nome = trim($_POST['nome']);
		$capa = trim($_POST['capa']);
		$data = $_POST['data_lancamento'];
		$duracao = $_POST['duracao'];
		$qtd_musicas = $_POST['qtd_musicas'];
		$id_artista = $_SESSION['id_artista'];

		$oMysql = connect_db();
		$query = "INSERT INTO album (nome, capa, data_lancamento, duracao, qtd_musicas, id_artista) 
                  VALUES ('$nome', '$capa', '$data', '$duracao', '$qtd_musicas', '$id_artista')";
		$resultado = $oMysql->query($query);

		if ($resultado) {
			echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
			echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Álbum cadastrado com sucesso!',
                    draggable: true
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
		} else {
			echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
			echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao cadastrar o álbum!',
                    draggable: true
                });
            </script>";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro de Álbum</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
	<div class="container mt-3">
		<h2>Cadastro - Álbum</h2>
		<p style="color:gray" class="mb-0">Campo obrigatório *</p>

		<form method="POST">
			<div class="mb-2">
				<label for="nome" class="form-label">Nome: *</label>
				<input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do álbum" value="<?php echo $_POST['nome'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="capa" class="form-label">Capa (URL): *</label>
				<input type="url" id="capa" name="capa" class="form-control" placeholder="Digite o link da imagem da capa" value="<?php echo $_POST['capa'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="data_lancamento" class="form-label">Data de lançamento: *</label>
				<input type="date" id="data_lancamento" name="data_lancamento" class="form-control" value="<?php echo $_POST['data_lancamento'] ?? ''; ?>">
			</div>

			<div class="mb-2">
				<label for="duracao" class="form-label">Duração do álbum (em segundos): *</label>
				<input type="number" id="duracao" name="duracao" class="form-control" placeholder="Ex: 3600 para 1 hora" min="1" value="<?php echo $_POST['duracao'] ?? ''; ?>">
			</div>

			<div class="mb-3">
				<label for="qtd_musicas" class="form-label">Número de músicas: *</label>
				<input type="number" id="qtd_musicas" name="qtd_musicas" class="form-control" placeholder="Ex: 10" min="1" value="<?php echo $_POST['qtd_musicas'] ?? ''; ?>">
			</div>

			<button type="submit" name="create" class="btn btn-primary">Enviar</button>
		</form>
	</div>

	<script src="../validarCampos.js"></script>

</body>
