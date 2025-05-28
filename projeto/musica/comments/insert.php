<?php
session_start();

include_once("../../connect.php");

if (!isset($_SESSION['id'])) {
    echo "erro:usuario_nao_logado";
    exit;
}

$id = intval($_SESSION['id']);
$name = $_SESSION['nome'] ?? 'Anônimo';
$mensagem = trim($_POST['comentario_mensagem'] ?? '');
$id_musica = intval($_POST['musica_id'] ?? 0);

$connection = connect_db();

if ($connection->connect_error) {
    echo "Erro na conexão com o banco de dados.";
    exit;
}

if (!empty($mensagem) && $id_musica > 0) {
    $stmt = $connection->prepare("INSERT INTO comentario (mensagem, id_autor, nome_autor) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $mensagem, $id, $name);

    if ($stmt->execute()) {
        $id_comentario = $stmt->insert_id;
        $stmt->close();

        $stmt2 = $connection->prepare("INSERT INTO comentario_musica (id_musica, id_comentario) VALUES (?, ?)");
        $stmt2->bind_param("ii", $id_musica, $id_comentario);

        if ($stmt2->execute()) {
            echo "Comentário registrado com sucesso";
        } else {
            echo "Erro ao relacionar comentário com música.";
        }

        $stmt2->close();
    } else {
        echo "Erro ao inserir comentário.";
    }

    $connection->close();
} else {
    echo "O comentário não pode ser vazio, por favor preencha o campo.";
}
?>
