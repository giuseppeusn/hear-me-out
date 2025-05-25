<?php
session_start();
header('Content-Type: application/json'); 


function sendJsonResponse($success, $message, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit(); 
}

function sendErrorResponse($message, $statusCode = 400) {
    http_response_code($statusCode);
    echo json_encode(['success' => false, 'error' => $message]);
    exit();
}

include_once("../connect.php"); 

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['id'])) {
    sendErrorResponse("Acesso negado. Usuário não autenticado.", 403);
}

if (!isset($data['id']) || $_SESSION['id'] != $data['id']) {
    sendErrorResponse("Acesso negado. ID inválido ou não autorizado.", 403);
}

$conexao = connect_db();
$id = intval($data['id']);
$tipoUsuario = $data['tipo'] ?? ''; 

if (empty($tipoUsuario)) {
    sendErrorResponse("Tipo de usuário não fornecido.", 400);
}

$tabela = '';
switch ($tipoUsuario) {
    case 'usuario':
        $tabela = 'usuario';
        break;
    case 'critico':
        $tabela = 'critico';
        break;
    case 'artista':
        $tabela = 'artista';
        break;
    default:
        sendErrorResponse("Tipo de usuário inválido.", 400);
}


$conexao->begin_transaction();

try {

    $query = "DELETE FROM $tabela WHERE id = ?";
    $stmt = $conexao->prepare($query);
    if (!$stmt) {
        throw new Exception("Erro ao preparar a query de exclusão: " . $conexao->error);
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $conexao->commit(); 
            session_destroy(); 
            sendJsonResponse(true, "Sua conta foi excluída com sucesso.");
        } else {
            $conexao->rollback(); 
            sendErrorResponse("Nenhuma conta encontrada com o ID fornecido ou tipo de usuário.", 404);
        }
    } else {
        throw new Exception("Erro ao executar a exclusão: " . $stmt->error);
    }
} catch (Exception $e) {
    $conexao->rollback(); 
    sendErrorResponse("Erro ao excluir a conta: " . $e->getMessage(), 500);
}

$conexao->close();
?>