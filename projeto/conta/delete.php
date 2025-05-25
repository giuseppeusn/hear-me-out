<?php
session_start();
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
        throw new Exception("Usuário não encontrado.");
    }

    if ($userType === 'usuario' || $userType === 'critico') {
        
        $avaliacao_ids = [];
        $stmt_select_avaliacoes = $conexao->prepare("SELECT id FROM avaliacao WHERE id_usuario = ? OR id_critico = ?");
        $stmt_select_avaliacoes->bind_param("ii", $id, $id);
        $stmt_select_avaliacoes->execute();
        $result_avaliacoes = $stmt_select_avaliacoes->get_result();
        while ($row = $result_avaliacoes->fetch_assoc()) {
            $avaliacao_ids[] = $row['id'];
        }
        $stmt_select_avaliacoes->close();

        if (!empty($avaliacao_ids)) {
            $ids_placeholder = implode(',', array_fill(0, count($avaliacao_ids), '?'));
            
            
            $stmt_del_avaliacao_album = $conexao->prepare("DELETE FROM avaliacao_album WHERE id_avaliacao IN ($ids_placeholder)");
            $stmt_del_avaliacao_album->bind_param(str_repeat('i', count($avaliacao_ids)), ...$avaliacao_ids);
            $stmt_del_avaliacao_album->execute();
            $stmt_del_avaliacao_album->close();

            
            $stmt_del_avaliacao_musica = $conexao->prepare("DELETE FROM avaliacao_musica WHERE id_avaliacao IN ($ids_placeholder)");
            $stmt_del_avaliacao_musica->bind_param(str_repeat('i', count($avaliacao_ids)), ...$avaliacao_ids);
            $stmt_del_avaliacao_musica->execute();
            $stmt_del_avaliacao_musica->close();
            
            
            $stmt_del_avaliacao = $conexao->prepare("DELETE FROM avaliacao WHERE id IN ($ids_placeholder)");
            $stmt_del_avaliacao->bind_param(str_repeat('i', count($avaliacao_ids)), ...$avaliacao_ids);
            $stmt_del_avaliacao->execute();
            $stmt_del_avaliacao->close();
        }

        
        $comentario_ids = [];
        $stmt_select_comentarios = $conexao->prepare("SELECT id FROM comentario WHERE id_autor = ?");
        $stmt_select_comentarios->bind_param("i", $id);
        $stmt_select_comentarios->execute();
        $result_comentarios = $stmt_select_comentarios->get_result();
        while ($row = $result_comentarios->fetch_assoc()) {
            $comentario_ids[] = $row['id'];
        }
        $stmt_select_comentarios->close();

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

    } elseif ($userType === 'artista') {

        $album_ids = [];
        $stmt_select_albums = $conexao->prepare("SELECT id FROM album WHERE id_artista = ?");
        $stmt_select_albums->bind_param("i", $id);
        $stmt_select_albums->execute();
        $result_albums = $stmt_select_albums->get_result();
        while ($row = $result_albums->fetch_assoc()) {
            $album_ids[] = $row['id'];
        }
        $stmt_select_albums->close();

        
        if (!empty($album_ids)) {
            $ids_placeholder_albums = implode(',', array_fill(0, count($album_ids), '?'));

           
            $musica_ids = [];
            $stmt_select_musicas = $conexao->prepare("SELECT id FROM musica WHERE id_artista = ?");
            $stmt_select_musicas->bind_param("i", $id);
            $stmt_select_musicas->execute();
            $result_musicas = $stmt_select_musicas->get_result();
            while ($row = $result_musicas->fetch_assoc()) {
                $musica_ids[] = $row['id'];
            }
            $stmt_select_musicas->close();

          
            $stmt_del_avaliacao_album = $conexao->prepare("DELETE FROM avaliacao_album WHERE id_album IN ($ids_placeholder_albums)");
            $stmt_del_avaliacao_album->bind_param(str_repeat('i', count($album_ids)), ...$album_ids);
            $stmt_del_avaliacao_album->execute();
            $stmt_del_avaliacao_album->close();

            if (!empty($musica_ids)) {
                $ids_placeholder_musicas = implode(',', array_fill(0, count($musica_ids), '?'));
              
                $stmt_del_avaliacao_musica = $conexao->prepare("DELETE FROM avaliacao_musica WHERE id_musica IN ($ids_placeholder_musicas)");
                $stmt_del_avaliacao_musica->bind_param(str_repeat('i', count($musica_ids)), ...$musica_ids);
                $stmt_del_avaliacao_musica->execute();
                $stmt_del_avaliacao_musica->close();
            }

           
            $stmt_del_comentario_album = $conexao->prepare("DELETE FROM comentario_album WHERE id_album IN ($ids_placeholder_albums)");
            $stmt_del_comentario_album->bind_param(str_repeat('i', count($album_ids)), ...$album_ids);
            $stmt_del_comentario_album->execute();
            $stmt_del_comentario_album->close();

            if (!empty($musica_ids)) {
                $ids_placeholder_musicas = implode(',', array_fill(0, count($musica_ids), '?'));
                
                $stmt_del_comentario_musica = $conexao->prepare("DELETE FROM comentario_musica WHERE id_musica IN ($ids_placeholder_musicas)");
                $stmt_del_comentario_musica->bind_param(str_repeat('i', count($musica_ids)), ...$musica_ids);
                $stmt_del_comentario_musica->execute();
                $stmt_del_comentario_musica->close();
            }
        }
        
        
        $stmt = $conexao->prepare("DELETE FROM musica WHERE id_artista = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

       
        $stmt = $conexao->prepare("DELETE FROM album WHERE id_artista = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

  
    $stmt = $conexao->prepare("DELETE FROM " . $userType . " WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conexao->commit();
    session_destroy();
    echo json_encode(["success" => true, "message" => "Conta excluída com sucesso!"]);
} catch (Exception $e) {
    $conexao->rollback();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao excluir a conta: " . $e->getMessage()]);
}

$conexao->close();
?>