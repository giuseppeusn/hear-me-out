<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_SESSION['id']);
    $name = $_SESSION['nome'];
    $mensagem = $_POST['comentario_mensagem'];
    $id_album = $_POST['album_id'];
    $conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

    if ($conn->connect_error) {
        echo "Erro na conexão: " . $conn->connect_error;
        exit;
    }

    if (!empty($mensagem) && $id_album && $id && $name) {
        $comentario = "INSERT INTO comentario (mensagem, id_autor, nome_autor) 
                       VALUES ('$mensagem', '$id', '$name')";

        if ($conn->query($comentario)) {
            $id_comentario = $conn->insert_id;
            $comentario_album = "INSERT INTO comentario_album (id_album, id_comentario)
                                 VALUES ('$id_album', '$id_comentario')";

            if ($conn->query($comentario_album)) {
                echo "Comentário registrado com sucesso";
            } else {
                echo "Erro ao relacionar comentário com álbum: " . $conn->error;
            }
        } else {
            echo "Erro ao inserir comentário: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Dados incompletos. Preencha todos os campos.";
    }
}
?>
