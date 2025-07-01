<?php
session_start();

$conn = new mysqli("localhost:3307", "root", "", "hear_me_out");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$nome = $data['nome'];
$duracao = intval($data['duracao']);
$capa = $data['capa'];
$data_lancamento = $data['data'];
$id_album = intval($data['albumId']);

$id_artista = $_SESSION['id'];
if (!$id_artista || !$id_album || !$nome || !$duracao || !$data_lancamento || !$capa) {
    echo "Erro: Dados incompletos.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO musica (nome, duracao, data_lancamento, capa, id_artista, id_album) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissii", $nome, $duracao, $data_lancamento, $capa, $id_artista, $id_album);

if ($stmt->execute()) {
    echo "Música cadastrada com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
