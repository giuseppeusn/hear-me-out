<?php
$conn = new mysqli("localhost:3306", "root", "", "hear_me_out");

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

if (!$id) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$conn->begin_transaction();

try {
    $conn->query("DELETE FROM comentario_musica WHERE id_musica = $id");
    $comentarioMusicaAfetado = $conn->affected_rows;

    $conn->query("DELETE FROM avaliacao_musica WHERE id_musica = $id");
    $avaliacaoMusicaAfetado = $conn->affected_rows;

    if ($comentarioMusicaAfetado > 0 || $avaliacaoMusicaAfetado > 0) {

        $conn->query("
            DELETE FROM comentario
            WHERE id NOT IN (SELECT id_comentario FROM comentario_musica)
              AND id NOT IN (SELECT id_comentario FROM comentario_album)
        ");

        $conn->query("
            DELETE FROM avaliacao
            WHERE id NOT IN (SELECT id_avaliacao FROM avaliacao_musica)
              AND id NOT IN (SELECT id_avaliacao FROM avaliacao_album)
        ");

        $result = $conn->query("DELETE FROM musica WHERE id = $id");
        $musicaAfetado = $conn->affected_rows;

        if ($musicaAfetado > 0) {
            $conn->commit();
            echo "Música excluída com sucesso!";
        } else {
            $conn->rollback();
            http_response_code(500);
            echo "Erro ao excluir música!";
        }
    } else {
        $conn->rollback();
        http_response_code(404);
        echo "Nenhum vínculo de comentário ou avaliação para essa música foi encontrado.";
    }
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Erro ao excluir música!";
}

$conn->close();
?>
