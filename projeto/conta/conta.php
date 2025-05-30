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
  $nome = $_SESSION['nome'];
  $userData = null;
  $userType = '';

  $conexao = connect_db();

  if (!$conexao) {
      die("Erro ao conectar ao banco de dados!");
  }

 
    $queries = [
        'usuario' => "SELECT id, nome, email, data_nasc, genero, cpf FROM usuario WHERE id = ? AND nome = ?",
        'critico' => "SELECT id, nome, email, data_nasc, genero, biografia, cpf, site FROM critico WHERE id = ? AND nome = ?",
        'artista' => "SELECT id, nome, email, biografia, imagem, data_formacao, pais, site_oficial, genero FROM artista WHERE id = ? AND nome = ?"
    ];

    foreach ($queries as $type => $sql) {
        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $id, $nome);
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
    <div class="cs-container">
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
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" type="button" id="btnEditarPerfil">Editar</button>
                    <button class="btn btn-danger profile-button" type="button" id="btnExcluirConta">Excluir</button>
                    <button class="btn btn-success profile-button" type="submit" id="btnSalvarPerfil" style="display:none;">Salvar</button>
                    <button class="btn btn-secondary profile-button" type="reset" id="btnCancelarPerfil" style="display:none;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "script.php"; ?>
</body>
</html>