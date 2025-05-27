<?php
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

if (!$id) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$result = $conn->query("DELETE FROM musica WHERE id = $id");

if ($result) {
    echo "Música excluída com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao excluir música!";
}
$conn->close();
?>
