<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Acesso negado. Por favor, faça login novamente."]);
    exit;
}

if (!isset($data['id']) || $_SESSION['id'] != $data['id']) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Acesso negado. ID da sessão não corresponde."]);
    exit;
}

$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$id = intval($data['id']);

$conexao->begin_transaction();

try {
    $userType = '';

    $stmt = $conexao->prepare("SELECT id FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userType = 'usuario';
    }
    $stmt->close();

    if (empty($userType)) {
        $stmt = $conexao->prepare("SELECT id FROM critico WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userType = 'critico';
        }
        $stmt->close();
    }

    if (empty($userType)) {
        $stmt = $conexao->prepare("SELECT id FROM artista WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userType = 'artista';
        }
        $stmt->close();
    }

    if (empty($userType)) {
        throw new Exception("Tipo de usuário não identificado ou usuário não encontrado para o ID fornecido.");
    }



    $comentario_ids = [];
    $stmt_comentarios = $conexao->prepare("SELECT id FROM comentario WHERE id_autor = ?");
    $stmt_comentarios->bind_param("i", $id);
    $stmt_comentarios->execute();
    $result_comentarios = $stmt_comentarios->get_result();
    while ($row = $result_comentarios->fetch_assoc()) {
        $comentario_ids[] = $row['id'];
    }
    $stmt_comentarios->close();

    if (!empty($comentario_ids)) {
        $ids_placeholder = implode(',', array_fill(0, count($comentario_ids), '?'));


        $stmt_del_comentario_album = $conexao->prepare("DELETE FROM comentario_album WHERE id_comentario IN ($ids_placeholder)");
        $stmt_del_comentario_album->bind_param(str_repeat('i', count($comentario_ids)), ...$comentario_ids);
        $stmt_del_comentario_album->execute();
        $stmt_del_comentario_album->close();


        $stmt_del_comentario_musica = $conexao->prepare("DELETE FROM comentario_musica WHERE id_comentario IN ($ids_placeholder)");
        $stmt_del_comentario_musica->bind_param(str_repeat('i', count($comentario_ids)), ...$comentario_ids);
        $stmt_del_comentario_musica->execute();
        $stmt_del_comentario_musica->close();

        $stmt_del_comentario = $conexao->prepare("DELETE FROM comentario WHERE id IN ($ids_placeholder)");
        $stmt_del_comentario->bind_param(str_repeat('i', count($comentario_ids)), ...$comentario_ids);
        $stmt_del_comentario->execute();
        $stmt_del_comentario->close();
    }
    

    $stmt_delete_main = $conexao->prepare("DELETE FROM $userType WHERE id = ?");
    $stmt_delete_main->bind_param("i", $id);
    $stmt_delete_main->execute();

    if ($stmt_delete_main->affected_rows === 0) {
        throw new Exception("Falha ao excluir o registro principal ou registro já inexistente.");
    }

    $stmt_delete_main->close();
    $conexao->commit();
    session_destroy();
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Conta excluída com sucesso!"]);
} catch (Exception $e) {
    $conexao->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao excluir a conta: " . $e->getMessage()]);
} finally {
    if ($conexao) {
        $conexao->close();
    }
}
?>