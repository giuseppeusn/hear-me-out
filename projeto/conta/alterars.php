<?php
session_start();
include_once("../connect.php"); 

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    $response['message'] = 'Usuário não autenticado.';
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'alterar_senha') {
    $mysql = connect_db();

    $id_usuario = $_SESSION['id'];
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $permissao_usuario = $_SESSION['permissao']; // 'normal', 'critico', 'artista'

    // Função de validação de senha (copiada do cadastro.php para ser autocontida)
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
        return [!empty($erros), $erros]; // true se houver erros, false se não
    }

    if (empty($nova_senha) || empty($confirmar_senha)) {
        $response['message'] = 'Por favor, preencha todos os campos da senha.';
    } elseif ($nova_senha !== $confirmar_senha) {
        $response['message'] = 'As senhas não coincidem.';
    } else {
        $validaSenha = validarSenha($nova_senha);
        if ($validaSenha[0] === true) {
            $response['message'] = 'A nova senha não atende aos requisitos:<br><ul><li>' . implode('</li><li>', $validaSenha[1]) . '</li></ul>';
        } else {
            $senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);

            $tabela = '';
            if ($permissao_usuario === 'normal') {
                $tabela = 'usuario';
            } elseif ($permissao_usuario === 'critico') {
                $tabela = 'critico';
            } elseif ($permissao_usuario === 'artista') {
                $tabela = 'artista';
            } else {
                $response['message'] = 'Permissão de usuário desconhecida.';
                echo json_encode($response);
                exit();
            }

            // Preparar a query para evitar injeção de SQL
            $query = "UPDATE $tabela SET senha = ? WHERE id = ?";
            $stmt = $mysql->prepare($query);
            // 'si' significa que o primeiro parâmetro é string e o segundo é int
            $stmt->bind_param("si", $senha_hashed, $id_usuario);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Senha alterada com sucesso!';
            } else {
                $response['message'] = 'Erro ao alterar a senha: ' . $mysql->error;
            }
            $stmt->close();
        }
    }
    $mysql->close();
} else {
    $response['message'] = 'Requisição inválida.';
}

echo json_encode($response);
exit();
?>