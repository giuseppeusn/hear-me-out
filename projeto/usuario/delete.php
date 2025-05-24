<?php
session_start();
include_once("../connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

if (!isset($data['id']) || $_SESSION['id'] != $data['id']) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

$conexao = connect_db();
$id = intval($data['id']);


$checkUser = $conexao->query("SELECT id FROM usuario WHERE id = $id");
$table = ($checkUser->num_rows > 0) ? "usuario" : "critico";

$stmt = $conexao->prepare("DELETE FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    session_destroy();
    echo "Sua conta foi excluída com sucesso.";
} else {
    http_response_code(500);
    echo "Erro ao excluir a conta.";
}

$stmt->close();
$conexao->close();
?>