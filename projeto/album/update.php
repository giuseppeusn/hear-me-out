<?php
session_start();
$id_artista = $_SESSION['id'];
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$id_album = intval($data['id']);
$nome = $conn->real_escape_string($data['nome']);
$capa = $conn->real_escape_string($data['capa']);
$data_lancamento = $conn->real_escape_string($data['data']);

$sql = "UPDATE album 
        SET nome = '$nome', capa = '$capa', data_lancamento = '$data_lancamento' 
        WHERE id = $id_album AND id_artista = $id_artista";

if ($conn->query($sql)) {
    echo "Álbum atualizado com sucesso!";
} else {
    echo "Erro ao atualizar: " . $conn->error;
}

$conn->close();
?>
