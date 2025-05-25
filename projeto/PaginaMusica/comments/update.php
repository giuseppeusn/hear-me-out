<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'], $_SESSION['nome'])) {
    echo json_encode(["erro" => "usuario_nao_logado"]);
    exit;
}

$input = file_get_contents('php://input');
$dados = json_decode($input, true);

if (!isset($dados['id']) || !isset($dados['mensagem'])) {
    echo json_encode(["erro" => "campos_invalidos"]);
    exit;
}

$idComentario = intval($dados['id']);
$novaMensagem = trim($dados['mensagem']);

if ($novaMensagem === '') {
    echo json_encode(["erro" => "O comentário não pode estar vazio."]);
    exit;
}
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

if ($conn->connect_error) {
    echo json_encode(["erro" => "Erro na conexão: " . $conn->connect_error]);
    exit;
}

$sqlVerifica = "SELECT * FROM comentario WHERE id = ? AND id_autor = ? AND nome_autor = ?";
$stmt = $conn->prepare($sqlVerifica);
$stmt->bind_param("iis", $idComentario, $_SESSION['id'], $_SESSION['nome']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["erro" => "comentario_nao_encontrado_ou_permissao"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$sqlUpdate = "UPDATE comentario SET mensagem = ? WHERE id = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("si", $novaMensagem, $idComentario);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => "Comentário atualizado com sucesso."]);
} else {
    echo json_encode(["erro" => "Erro ao atualizar o comentário: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>