<?php
session_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
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
$cpf = $data['cpf'];
$biografia = $data['biografia'];
$site = $data['site'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de e-mail inválido."]);
    exit;
}

$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$stmt = $conexao->prepare("SELECT id FROM critico WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Este e-mail já está sendo usado por outro crítico."]);
    exit;
}
$stmt->close();

$query = "UPDATE critico SET nome = ?, email = ?, data_nasc = ?, genero = ?, biografia = ?, site = ? WHERE id = ?";
$stmt = $conexao->prepare($query);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao preparar a consulta: " . $conexao->error]);
    exit;
}

$stmt->bind_param("ssssssi", $nome, $email, $data_nasc, $genero, $biografia, $site, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Perfil atualizado com sucesso!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao atualizar o perfil: " . $stmt->error]);
}

$stmt->close();
$conexao->close();
?>