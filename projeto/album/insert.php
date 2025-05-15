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

<body>
	<div class="container mt-3">
		<h2>CADASTRO - ÁLBUM</h2>
		<p style="color:gray" class="mb-0">Campo obrigatório*</p>

		<form method="POST" action="">
			<label class="form-label">Nome: *</label>
			<input type="text" name="nome" class="form-control" placeholder="Digite aqui o nome do álbum">

			<label class="form-label">Capa: *</label>
			<input type="text" name="capa" class="form-control" placeholder="Digite aqui o link da capa">

			<label class="form-label">Data de lançamento: *</label>
			<input type="date" name="data_lancamento" class="form-control">

			<label class="form-label">Duração do álbum (em segundos): *</label>
			<input type="number" name="duracao" class="form-control" placeholder="Digite aqui a duração do álbum">

			<label class="form-label">Número de músicas: *</label>
			<input type="number" name="qtd_musicas" class="form-control" placeholder="Digite aqui o número de músicas">

			<button type="submit" name="create" class="btn btn-primary mt-3">Enviar</button>
		</form>
	</div>
</body>

</html>