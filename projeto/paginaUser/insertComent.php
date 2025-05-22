<?php
include_once("../header.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = intval($_SESSION['id_usuario']);
    $mensagem = $_POST['comentario_mensagem'];
    $id_album = $_POST['album_id'];
    if ($mensagem == null || $id_album == null || $id_user == null) {
        echo "<p> Alguma coisa ta vazio fi </p>";
        exit;
    }
    $conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }
    if (!empty($mensagem) && $id_album && $id_user) {
        $comentario = "INSERT INTO comentario (mensagem,id_usuario) 
        VALUES ('$mensagem', '$id_user')";

        if ($conn->query($comentario)) {
            $id_comentario = $conn->insert_id;
            $comentario_album = "INSERT into comentario_album(id_album,id_comentario)
            VALUES ('$id_album', '$id_comentario')";
            if ($conn->query($comentario_album)){
                echo "Comentário registrado com sucesso";
            } else {
                echo "Erro ao relacionar comentário com álbum: " . $conn->error;
            }
        } else {
            echo "Erro: " . $conn->error;}
$conn->close();
    }
}
?>