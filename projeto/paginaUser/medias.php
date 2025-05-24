<?php
header('Content-Type: application/json');
$conexao = new mysqli("localhost:3306", "root", "", "hear_me_out");

$item_id = intval($_GET['item_id'] ?? 0);
$tipo_item = ($_GET['tipo_item'] ?? '') === 'album' ? 'album' : 'musica';

if ($item_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}


$query = "SELECT AVG(a.nota) as media 
          FROM avaliacao a
          JOIN {$tipo_item}_avaliacao ia ON ia.id_avaliacao = a.id
          WHERE ia.id_{$tipo_item} = ? AND a.id_usuario IS NOT NULL";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$media_usuarios = $result->fetch_assoc()['media'] ?? 0;


$query = "SELECT AVG(a.nota) as media 
          FROM avaliacao a
          JOIN {$tipo_item}_avaliacao ia ON ia.id_avaliacao = a.id
          WHERE ia.id_{$tipo_item} = ? AND a.id_critico IS NOT NULL";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$media_criticos = $result->fetch_assoc()['media'] ?? 0;

echo json_encode([
    'success' => true,
    'media_usuarios' => floatval($media_usuarios),
    'media_criticos' => floatval($media_criticos)
]);
?>