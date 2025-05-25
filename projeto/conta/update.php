<?php
session_start();
// ADICIONAR ESTAS LINHAS PARA TRATAMENTO DE ERROS
ini_set('display_errors', 0); // Desabilita a exibição de erros no navegador
ini_set('log_errors', 1);    // Habilita o log de erros para um arquivo
// Define o arquivo de log. Certifique-se de que este caminho está correto
// e que o servidor web tem permissão de escrita nesta pasta/arquivo.
// O __DIR__ é a pasta atual (projeto/), então ../php-error.log vai para a raiz do seu projeto.
ini_set('error_log', __DIR__ . '/../php-error.log'); 

include_once("../connect.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Dados inválidos!"]);
    exit;
}

$id = intval($data['id']);
$nome = $data['nome'];
$email = $data['email'];
$data_nasc = $data['data_nasc'];
$genero = $data['genero'];
$cpf = $data['cpf']; // CPF não deve ser alterável, mas se está no formulário, receba.

// Validações adicionais (mantenha ou adicione conforme sua necessidade)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de e-mail inválido."]);
    exit;
}

// Conecta ao banco de dados
$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

// Verifica se o email já existe para outro usuário (excluindo o próprio)
$stmt = $conexao->prepare("SELECT id FROM usuario WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Este e-mail já está sendo usado por outro usuário."]);
    exit;
}
$stmt->close();

// Prepara a consulta SQL para atualizar o usuário
$query = "UPDATE usuario SET nome = ?, email = ?, data_nasc = ?, genero = ? WHERE id = ?";
$stmt = $conexao->prepare($query);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao preparar a consulta: " . $conexao->error]);
    exit;
}

$stmt->bind_param("ssssi", $nome, $email, $data_nasc, $genero, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Perfil atualizado com sucesso!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao atualizar o perfil: " . $stmt->error]);
}

$stmt->close();
$conexao->close();
?>