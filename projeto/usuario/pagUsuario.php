<?php
session_start();
// O connect.php está na pasta PAI (superior) do diretório onde este arquivo está.
include_once("../connect.php"); // Corrigido o caminho para o connect.php

if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona para login.php se não houver sessão
    exit;
}

$id = $_SESSION['id'];
$userData = null;
$userType = ''; // Variável para armazenar o tipo de usuário

$conexao = connect_db();

if (!$conexao) {
    die("Erro ao conectar ao banco de dados!");
}

// Tenta buscar como usuário
$query = "SELECT * FROM usuario WHERE id = $id";
$resultado = $conexao->query($query);

if ($resultado && $resultado->num_rows > 0) {
    $userData = $resultado->fetch_assoc();
    $userType = 'usuario';
} else {
    // Tenta buscar como crítico
    $query = "SELECT * FROM critico WHERE id = $id";
    $resultado = $conexao->query($query);
    
    if ($resultado && $resultado->num_rows > 0) {
        $userData = $resultado->fetch_assoc();
        $userType = 'critico';
    } else {
        // Tenta buscar como artista
        $query = "SELECT * FROM artista WHERE id = $id";
        $resultado = $conexao->query($query);

        if ($resultado && $resultado->num_rows > 0) {
            $userData = $resultado->fetch_assoc();
            $userType = 'artista';
        }
    }
}

if (!$userData) {
    die("Usuário não encontrado!");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário - Hear Me Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/pagUsuario.css"> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hear Me Out</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Explorar</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a class="btn btn-outline-light me-2" href="#">Minha Biblioteca</a>
                    <a class="btn btn-outline-light" href="logout.php">Sair</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" width="150px" src="<?= htmlspecialchars($userType === 'artista' && !empty($userData['imagem']) ? $userData['imagem'] : 'https://st3.depositphotos.com/1531338/15729/v/450/depositphotos_157295552-stock-illustration-profile-placeholder-male-silhouette.jpg') ?>" alt="Foto de Perfil">
                    <span class="font-weight-bold"><?= htmlspecialchars($userData['nome']) ?></span>
                    <span class="text-black-50"><?= htmlspecialchars($userData['email']) ?></span>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Configurações de Perfil</h4>
                    </div>
                    <form id="formPerfil">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>">
                        <div class="row mt-2">
                            <div class="col-md-12"><label class="labels">Nome</label><input type="text" class="form-control" placeholder="Nome Completo" value="<?= htmlspecialchars($userData['nome']) ?>" name="nome" disabled></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Email:</label><input type="email" class="form-control" placeholder="email" value="<?= htmlspecialchars($userData['email']) ?>" name="email" disabled></div>
                            <?php if ($userType === 'usuario'): ?>
                                <div class="col-md-12"><label class="labels">Data de Nascimento: </label><input type="date" class="form-control" placeholder="data de nascimento" value="<?= $userData['data_nasc'] ?>" name="data_nasc" disabled></div>
                                <div class="col-md-12"><label class="labels">Gênero</label>
                                    <select class="form-control" name="genero" disabled>
                                        <option value="M" <?= ($userData['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="F" <?= ($userData['genero'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                                        <option value="I" <?= ($userData['genero'] == 'I') ? 'selected' : '' ?>>Prefiro não informar</option>
                                    </select>
                                </div>
                                <div class="col-md-12"><label class="labels">CPF</label><input type="text" class="form-control" placeholder="CPF" value="<?= htmlspecialchars($userData['cpf']) ?>" name="cpf" disabled></div>
                            <?php elseif ($userType === 'critico'): ?>
                                <div class="col-md-12"><label class="labels">Data de Nascimento: </label><input type="date" class="form-control" placeholder="data de nascimento" value="<?= $userData['data_nasc'] ?>" name="data_nasc" disabled></div>
                                <div class="col-md-12"><label class="labels">Gênero</label>
                                    <select class="form-control" name="genero" disabled>
                                        <option value="M" <?= ($userData['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="F" <?= ($userData['genero'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                                        <option value="I" <?= ($userData['genero'] == 'I') ? 'selected' : '' ?>>Prefiro não informar</option>
                                    </select>
                                </div>
                                <div class="col-md-12"><label class="labels">CPF</label><input type="text" class="form-control" placeholder="CPF" value="<?= htmlspecialchars($userData['cpf']) ?>" name="cpf" disabled></div>
                                <div class="col-md-12"><label class="labels">Biografia</label><textarea class="form-control" placeholder="Sua biografia" name="biografia" disabled><?= htmlspecialchars($userData['biografia']) ?></textarea></div>
                                <div class="col-md-12"><label class="labels">Site</label><input type="url" class="form-control" placeholder="Seu site oficial" value="<?= htmlspecialchars($userData['site']) ?>" name="site" disabled></div>
                            <?php elseif ($userType === 'artista'): ?>
                                <div class="col-md-12"><label class="labels">Biografia</label><textarea class="form-control" placeholder="Sua biografia" name="biografia" disabled><?= htmlspecialchars($userData['biografia']) ?></textarea></div>
                                <div class="col-md-12"><label class="labels">Data de Formação</label><input type="date" class="form-control" placeholder="data de formação" value="<?= $userData['data_formacao'] ?>" name="data_formacao" disabled></div>
                                <div class="col-md-12"><label class="labels">País</label><input type="text" class="form-control" placeholder="país" value="<?= htmlspecialchars($userData['pais']) ?>" name="pais" disabled></div>
                                <div class="col-md-12"><label class="labels">Site Oficial</label><input type="url" class="form-control" placeholder="site oficial" value="<?= htmlspecialchars($userData['site_oficial']) ?>" name="site_oficial" disabled></div>
                                <div class="col-md-12"><label class="labels">Gênero Musical</label><input type="text" class="form-control" placeholder="Gênero musical" value="<?= htmlspecialchars($userData['genero']) ?>" name="genero" disabled></div>
                                <div class="col-md-12"><label class="labels">URL da Imagem</label><input type="url" class="form-control" placeholder="URL da imagem" value="<?= htmlspecialchars($userData['imagem']) ?>" name="imagem" disabled></div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-5 text-center">
                            <button class="btn btn-primary profile-button" type="button" id="btnAlterarPerfil">Alterar Perfil</button>
                            <button class="btn btn-success profile-button" type="submit" id="btnSalvarPerfil" style="display:none;">Salvar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
    </div>

    <script>
        document.getElementById('btnAlterarPerfil').addEventListener('click', function() {
            var form = document.getElementById('formPerfil');
            var inputs = form.querySelectorAll('.form-control');
            var userType = "<?= $userType ?>"; // Pega o tipo de usuário do PHP

            inputs.forEach(function(input) {
                input.disabled = false;
            });

            // Habilita/desabilita campos específicos para cada tipo de usuário
            if (userType === 'usuario' || userType === 'critico') {
                // CPF não pode ser alterado para usuários e críticos
                var cpfInput = form.querySelector('input[name="cpf"]');
                if (cpfInput) {
                    cpfInput.disabled = true;
                }
            } else if (userType === 'artista') {
                // Nenhum campo específico para artista desabilitado, todos podem ser alterados
            }

            document.getElementById('btnAlterarPerfil').style.display = 'none';
            document.getElementById('btnSalvarPerfil').style.display = 'block';
        });

        document.getElementById('formPerfil').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = e.target;
            var formData = new FormData(form);
            var jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            var userType = "<?= $userType ?>";
            var url = '';
            if (userType === 'usuario') {
                url = 'update.php'; // Caminho corrigido (mesma pasta)
            } else if (userType === 'critico') {
                url = 'update_critico.php'; // Caminho corrigido (mesma pasta)
            } else if (userType === 'artista') {
                url = 'update_artista.php'; // Caminho corrigido (mesma pasta)
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                // Verifica se a resposta não é OK (status 200) ou se o Content-Type não é JSON
                const contentType = response.headers.get("content-type");
                if (!response.ok || !contentType || !contentType.includes("application/json")) {
                    // Tenta ler o texto da resposta para depuração
                    return response.text().then(text => {
                        console.error('Resposta não JSON ou erro HTTP:', text);
                        throw new Error('Resposta do servidor inválida. Verifique o console para mais detalhes.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erro!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro na requisição ou parsing:', error);
                Swal.fire('Erro!', 'Ocorreu um erro ao tentar atualizar o perfil. ' + error.message, 'error');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>