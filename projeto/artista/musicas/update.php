<?php
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['id']) ||
    !isset($data['nome']) ||
    !isset($data['duracao']) ||
    !isset($data['capa']) ||
    !isset($data['data'])
) {
    http_response_code(400);
    echo "Erro: Dados incompletos.";
    exit;
}

$id = intval($data['id']);
$nome = $conn->real_escape_string($data['nome']);
$duracao = intval($data['duracao']);
$capa = $conn->real_escape_string($data['capa']);
$data_lancamento = $conn->real_escape_string($data['data']);

$stmt = $conn->prepare("UPDATE musica SET nome = ?, duracao = ?, capa = ?, data_lancamento = ? WHERE id = ?");
$stmt->bind_param("sissi", $nome, $duracao, $capa, $data_lancamento, $id);

if ($stmt->execute()) {
    echo "Música atualizada com sucesso!";
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
