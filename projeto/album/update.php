<?php
include_once "../connect.php";

$oMysql = connect_db();
$queryReadAlbum = mysqli_query($oMysql, "SELECT * FROM album WHERE id = " . $_GET['id']);
if ($listaReadAlbum = mysqli_fetch_assoc($queryReadAlbum)) {
    $nome = $listaReadAlbum['nome'];
    $capa = $listaReadAlbum['capa'];
    $duracao = $listaReadAlbum['duracao'];
    $data_lancamento = $listaReadAlbum['data_lancamento'];
    $qtd_musicas = $listaReadAlbum['qtd_musicas'];
}

if (isset($_POST['edit'])) {
    function camposPreenchidos($campos)
    {
        $campos_vazios = [];
        foreach ($campos as $campo) {
            if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                $campos_vazios[] = $campo;
            }
        }
        if (count($campos_vazios) > 0) {
            $_SESSION['campos_vazios'] = $campos_vazios;
            return false;
        }
        return true;
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
                text: 'O número de músicas deve ser um número positivo!',
                draggable: true
            });
        </script>";
    } else {
        $nome = trim($_POST['nome']);
        $capa = trim($_POST['capa']);
        $data_lancamento = $_POST['data_lancamento'];
        $duracao = $_POST['duracao'];
        $qtd_musicas = $_POST['qtd_musicas'];

        $query = "UPDATE album
                  SET nome = '$nome', 
                      capa = '$capa', 
                      data_lancamento = '$data_lancamento', 
                      duracao = '$duracao',
                      qtd_musicas = '$qtd_musicas'
                  WHERE id = " . $_GET['id'];
        $resultado = $oMysql->query($query);

        if ($resultado) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Álbum editado com sucesso!',
                    draggable: true
                }).then(() => {
                    window.location.href = 'index.php';
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
        <h2>CRUD - Atualizar Álbum - ID: <?php echo $_GET['id']; ?></h2>
        <p>Preencha os campos abaixo para atualizar o registro:</p>

        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" placeholder="Digite o nome" value='<?php echo ($nome) ?>'>
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Capa</label>
                <input type="text" name="capa" class="form-control" placeholder="URL da imagem" value='<?php echo ($capa) ?>'>
            </div>

            <div class="mb-3">
                <label for="data_lancamento" class="form-label">Data de lançamento</label>
                <input type="date" name="data_lancamento" class="form-control" value='<?php echo ($data_lancamento) ?>'>
            </div>

            <div class="mb-3">
                <label for="duracao" class="form-label">Duração (em segundos)</label>
                <input type="number" name="duracao" class="form-control" placeholder="Digite a duração" value='<?php echo ($duracao) ?>'>
            </div>

            <div class="mb-3">
                <label for="qtd_musicas" class="form-label">Número de músicas</label>
                <input type="number" name="qtd_musicas" class="form-control" placeholder="Digite a quantidade de músicas" value='<?php echo ($qtd_musicas) ?>'>
            </div>

            <button type="submit" name="edit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
</body>

</html>