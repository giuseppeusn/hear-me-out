<?php
session_start();
header('Content-Type: application/json');

include_once("../connect.php"); 

$conexao = connect_db();


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

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    sendErrorResponse("ID do crítico não fornecido.", 400);
}

$id = intval($data['id']);


if (!isset($_SESSION['id']) || $_SESSION['id'] != $id) {
    sendErrorResponse("Não autorizado a atualizar este perfil de crítico.", 403);
}


$nome = isset($data['nome']) ? trim($data['nome']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$data_nasc = isset($data['data_nasc']) ? $data['data_nasc'] : '';
$genero = isset($data['genero']) ? $data['genero'] : '';
$biografia = isset($data['biografia']) ? trim($data['biografia']) : '';
$site = isset($data['site']) ? trim($data['site']) : '';
$senha = isset($data['senha']) ? trim($data['senha']) : '';
$cpf = isset($data['cpf']) ? preg_replace('/[^0-9]/', '', $data['cpf']) : ''; 


$updateFields = [];
$bindParams = '';
$bindValues = [];

if (!empty($nome)) {
    $updateFields[] = "nome = ?";
    $bindParams .= "s";
    $bindValues[] = $nome;
}
if (!empty($email)) {
    $updateFields[] = "email = ?";
    $bindParams .= "s";
    $bindValues[] = $email;
}
if (!empty($data_nasc)) {
    $updateFields[] = "data_nasc = ?";
    $bindParams .= "s";
    $bindValues[] = $data_nasc;
}
if (!empty($genero)) {
    $updateFields[] = "genero = ?";
    $bindParams .= "s";
    $bindValues[] = $genero;
}
if (isset($data['biografia'])) { 
    $updateFields[] = "biografia = ?";
    $bindParams .= "s";
    $bindValues[] = $biografia;
}
if (isset($data['site'])) { 
    $updateFields[] = "site = ?";
    $bindParams .= "s";
    $bindValues[] = $site;
}

if (!empty($senha)) {
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
    $updateFields[] = "senha = ?";
    $bindParams .= "s";
    $bindValues[] = $hashed_password;
}

if (empty($updateFields)) {
    sendJsonResponse(true, "Nenhuma alteração foi fornecida.");
}

$query = "UPDATE critico SET " . implode(", ", $updateFields) . " WHERE id = ?";
$bindParams .= "i";
$bindValues[] = $id;

$stmt = $conexao->prepare($query);

if (!$stmt) {
    sendErrorResponse("Erro na preparação da consulta: " . $conexao->error);
}


$types = $bindParams;
$params = array_merge([$types], $bindValues);
call_user_func_array([$stmt, 'bind_param'], refValues($params));


if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        sendJsonResponse(true, "Perfil de crítico atualizado com sucesso!");
    } else {
        sendJsonResponse(true, "Nenhuma alteração foi feita no perfil (ou já estava atualizado).");
    }
} else {
    sendErrorResponse("Erro ao executar a atualização: " . $stmt->error);
}

$stmt->close();
$conexao->close();

function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0)
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}
?>