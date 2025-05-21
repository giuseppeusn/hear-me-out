<?php
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);
$sql = "DELETE FROM musica WHERE id = $id";

if ($conn->query($sql)) {
    echo "Música excluída com sucesso!";
} else {
    echo "Erro ao excluir música: " . $conn->error;
}
$conn->close();
?>