<?php
session_start();
$id_artista = $_SESSION['id_artista'];
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$nome = $conn->real_escape_string($data['nome']);
$capa = $conn->real_escape_string($data['capa']);
$data_lancamento = $conn->real_escape_string($data['data']);

$sql = "INSERT INTO album (nome, capa, data_lancamento, id_artista) VALUES ('$nome', '$capa', '$data_lancamento', '$id_artista')";
if ($conn->query($sql)) {
    echo "Álbum cadastrado com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}
$conn->close();
?>
