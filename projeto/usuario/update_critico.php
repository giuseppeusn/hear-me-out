<?php
session_start();
include_once("../connect.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo "Dados inválidos!";
    exit;
}

function validarData($data) {
    try {
        $d = new DateTime($data);
        $agora = new DateTime();
        return ($d->format('Y') >= 1900 && $d <= $agora);
    } catch (Exception $e) {
        return false;
    }
}


$campos = ['id_usuario', 'nome', 'email', 'data_nasc', 'genero', 'biografia', 'site'];
foreach ($campos as $campo) {
    if (empty(trim($data[$campo]))) {
        http_response_code(400);
        echo "O campo '$campo' é obrigatório.";
        exit;
    }
}

if (!validarData($data['data_nasc'])) {
    http_response_code(400);
    echo "Data de nascimento inválida.";
    exit;
}

$oMysql = connect_db();
$id = intval($data['id_usuario']);
$nome = mysqli_real_escape_string($oMysql, $data['nome']);
$email = mysqli_real_escape_string($oMysql, $data['email']);
$cpf = preg_replace('/[^0-9]/', '', $data['cpf']);
$data_nasc = mysqli_real_escape_string($oMysql, $data['data_nasc']);
$genero = mysqli_real_escape_string($oMysql, $data['genero']);
$biografia = mysqli_real_escape_string($oMysql, $data['biografia']);
$site = mysqli_real_escape_string($oMysql, $data['site']);


$update = "UPDATE critico SET 
    nome = '$nome', 
    email = '$email', 
    cpf = '$cpf', 
    data_nasc = '$data_nasc', 
    genero = '$genero',
    biografia = '$biografia',
    site = '$site'";


if (!empty($data['senha'])) {
    $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
    $update .= ", senha = '$senhaHash'";
}

$update .= " WHERE id = $id";


if ($oMysql->query($update)) {
    echo "Perfil atualizado com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao atualizar: " . $oMysql->error;
}
?>