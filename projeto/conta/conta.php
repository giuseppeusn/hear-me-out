<?php
  include_once("../connect.php"); 

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['id'])) {
      header('Location: login.php'); 
      exit;
  }

  $id = $_SESSION['id'];
  $userData = null;
  $userType = '';

  $conexao = connect_db();

  if (!$conexao) {
      die("Erro ao conectar ao banco de dados!");
  }

 
    if ($_SESSION['permissao']=== 'critico') {
        $queries = ['critico' => "SELECT id, nome, email, data_nasc, genero, biografia, cpf, site FROM critico WHERE id = ?"];
    } else if ($_SESSION['permissao'] === 'artista'){
        $queries = ['artista' => "SELECT id, nome, email, biografia, imagem, data_formacao, pais, site_oficial, genero FROM artista WHERE id = ?"];
    } else {
        $queries = ['usuario' => "SELECT id, nome, email, data_nasc, genero, cpf FROM usuario WHERE id = ?"];
    }

    foreach ($queries as $type => $sql) {
        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $userData = $resultado->fetch_assoc();
                $userType = $type;
                $stmt->close();
                break;
            }
            $stmt->close();
        }
    }

  $conexao->close();


  if (empty($userData)) {
      header('Location: login.php'); 
      exit;
  }

 
  $generoExibicao = '';
  if (isset($userData['genero']) && ($userType === 'usuario' || $userType === 'critico')) {
      switch ($userData['genero']) {
          case 'M':
              $generoExibicao = 'Masculino';
              break;
          case 'F':
              $generoExibicao = 'Feminino';
              break;
          case 'I':
              $generoExibicao = 'Indefinido';
              break;
          default:
              $generoExibicao = 'Não informado';
      }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Hear Me Out</title>
    <link rel="stylesheet" href="../styles/form.css"> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="cs-container mt-3" style="min-height: 100vh;">
        <div class="form-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Configurações de perfil</h4>
            </div>
            <form id="formPerfil" class="form-container">
                <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>">
                <input type="hidden" name="userType" value="<?= htmlspecialchars($userType) ?>">

                <div class="form-area">
                    <label class="input-label">Nome</label>
                    <input type="text" class="input-field" placeholder="Nome Completo" value="<?= htmlspecialchars($userData['nome']) ?>" name="nome" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Email</label>
                    <input type="email" class="input-field" placeholder="email" value="<?= htmlspecialchars($userData['email']) ?>" name="email" disabled>
                </div>

                <?php if ($userType === 'usuario'): ?>
                <div class="form-area">
                    <label class="input-label">Data de Nascimento</label>
                    <input type="date" class="input-field" placeholder="data de nascimento" value="<?= $userData['data_nasc'] ?>" name="data_nasc" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Gênero</label>
                    <select class="select-field" name="genero" disabled>
                        <option value="M" <?= (isset($userData['genero']) && $userData['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= (isset($userData['genero']) && $userData['genero'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                        <option value="I" <?= (isset($userData['genero']) && $userData['genero'] == 'I') ? 'selected' : '' ?>>Indefinido</option>
                    </select>
                </div>
                <div class="form-area">
                    <label class="input-label">CPF</label>
                    <input type="text" class="input-field" placeholder="CPF" value="<?= htmlspecialchars($userData['cpf']) ?>" name="cpf" disabled>
                </div>
                <?php elseif ($userType === 'critico'): ?>
                <div class="form-area">
                    <label class="input-label">Data de Nascimento</label>
                    <input type="date" class="input-field" placeholder="data de nascimento" value="<?= htmlspecialchars($userData['data_nasc']) ?>" name="data_nasc" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Gênero</label>
                    <select class="select-field" name="genero" disabled>
                        <option value="M" <?= (isset($userData['genero']) && $userData['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= (isset($userData['genero']) && $userData['genero'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                        <option value="I" <?= (isset($userData['genero']) && $userData['genero'] == 'I') ? 'selected' : '' ?>>Indefinido</option>
                    </select>
                </div>
                <div class="form-area">
                    <label class="input-label">CPF</label>
                    <input type="text" class="input-field" placeholder="CPF" value="<?= htmlspecialchars($userData['cpf']) ?>" name="cpf" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Biografia</label>
                    <textarea class="textarea-field" name="biografia" disabled><?= htmlspecialchars($userData['biografia']) ?>
                    </textarea>
                </div>
                <div class="form-area">
                    <label class="input-label">Site</label>
                    <input type="text" class="input-field" placeholder="Seu Site" value="<?= htmlspecialchars($userData['site']) ?>" name="site" disabled>
                </div>
                <?php elseif ($userType === 'artista'): ?>
                <div class="form-area">
                    <label class="input-label">Data de Formação</label>
                    <input type="date" class="input-field" value="<?= htmlspecialchars($userData['data_formacao']) ?>" name="data_formacao" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">País</label>
                    <input type="text" class="input-field" value="<?= htmlspecialchars($userData['pais']) ?>" name="pais" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Gênero Musical</label>
                    <input type="text" class="input-field" value="<?= htmlspecialchars($userData['genero']) ?>" name="genero" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Biografia</label>
                    <textarea class="textarea-field" name="biografia" disabled><?= htmlspecialchars($userData['biografia']) ?>
                    </textarea>
                </div>
                <div class="form-area">
                    <label class="input-label">URL da Imagem</label>
                    <input type="text" class="input-field" value="<?= htmlspecialchars($userData['imagem']) ?>" name="imagem" disabled>
                </div>
                <div class="form-area">
                    <label class="input-label">Site Oficial</label>
                    <input type="text" class="input-field" value="<?= htmlspecialchars($userData['site_oficial']) ?>" name="site_oficial" disabled>
                </div>
                <?php endif; ?>
                <div class="d-flex justify-content-center align-conten-center mt-3">
                    <button class="cs-btn action" type="button" id="btnAlterarSenhaa"onclick="mostrarModalAlterarSenha()">Atualizar senha</button>
                </div>
                <div class="d-flex justify-content-around mt-5 text-center">
                    <button class="btn-update" type="button" id="btnEditarPerfil">Editar</button>
                    <button class="btn-delete" type="button" id="btnExcluirConta">Excluir</button>
                    <button class="cs-btn confirm" type="submit" id="btnSalvarPerfil" style="display:none; width:45%">Salvar</button>
                    <button class="cs-btn cancel" type="reset" id="btnCancelarPerfil" style="display:none; width:45%">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
          function mostrarModalAlterarSenha() {
            Swal.fire({
                title: 'Alterar senha',
                html:`<form>
                    <p style="color:gray" class="mb-1">Campo obrigatório *</p>
                    <div class="form-area">
                        <label for="nova-senha" class="input-label">* Nova senha</label>
                        <input id="nova-senha" class="input-field" type="password" placeholder="Digite sua nova senha">
                    <div class="form-area">
                        <label for="confirmar-senha" class="input-label">* Confirme a senha</label>
                        <input id="confirmar-senha" class="input-field" type="password" placeholder="Confirme sua nova senha">
                    </div>
                </form>`,
                focusConfirm: false,
                showCloseButton: true,
                confirmButtonText: 'Salvar',
                preConfirm: () => {
                    const novaSenha = Swal.getPopup().querySelector('#nova-senha').value;
                    const confirmarSenha = Swal.getPopup().querySelector('#confirmar-senha').value;

                    if (!novaSenha || !confirmarSenha) {
                        Swal.showValidationMessage('Por favor, preencha todos os campos da senha');
                        return false;
                    }

                    if (novaSenha !== confirmarSenha) {
                        Swal.showValidationMessage('As senhas não coincidem');
                        return false;
                    }

                    return { nova_senha: novaSenha, confirmar_senha: confirmarSenha };
                }
            }).then((result) => {
                if (result.isConfirmed) {                          
                    fetch('/hear-me-out/projeto/conta/alterarSenha.php', { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=alterar_senha&nova_senha=${encodeURIComponent(result.value.nova_senha)}&confirmar_senha=${encodeURIComponent(result.value.confirmar_senha)}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Sucesso!', data.message, 'success');
                        } else {
                            Swal.fire('Erro!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        Swal.fire('Erro!', 'Ocorreu um erro ao comunicar com o servidor.', 'error');
                    });
                }
            });
        }
      </script>
    <?php include "script.php"; ?>
</body>
</html>