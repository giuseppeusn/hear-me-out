<?php
header('Content-Type: application/json');
$conexao = new mysqli("localhost:3306", "root", "", "hear_me_out");


session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Faça login para avaliar']);
    exit;
}


$item_id = intval($_POST['item_id'] ?? 0);
$tipo_item = ($_POST['tipo_item'] ?? '') === 'album' ? 'album' : 'musica';
$nota = floatval($_POST['nota'] ?? 0);

if ($item_id <= 0 || $nota < 1 || $nota > 10) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}


$query = "INSERT INTO avaliacao (mensagem, nota, id_usuario, id_critico) 
          VALUES ('', ?, ?, NULL)";
$stmt = $conexao->prepare($query);
$stmt->bind_param("di", $nota, $_SESSION['usuario_id']);
$stmt->execute();
$avaliacao_id = $conexao->insert_id;


$tabela = $tipo_item . '_avaliacao';
$campo = 'id_' . $tipo_item;
$query = "INSERT INTO $tabela (id_avaliacao, $campo) VALUES (?, ?)";
$stmt = $conexao->prepare($query);
$stmt->bind_param("ii", $avaliacao_id, $item_id);
$stmt->execute();

echo json_encode(['success' => true]);
?>