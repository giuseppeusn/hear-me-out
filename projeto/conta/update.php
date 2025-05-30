<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

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


// meus amigos, esse campo faz a validação do nome do usuario, nao pode ser vazio;
if (empty(trim($nome)) || empty(trim($email)) || empty(trim($data_nasc)) || empty(trim($genero))) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: nome, email, data de nascimento e genero."]);
    exit;
}


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