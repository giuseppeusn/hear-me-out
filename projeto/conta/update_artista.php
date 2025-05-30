<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../connect.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);





if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Dados inválidos!"]);
    exit;
}

function validarCampos($data, $camposObrigatorios) {
    foreach ($camposObrigatorios as $campo) {
        if (!isset($data[$campo]) || (is_string($data[$campo]) && empty(trim($data[$campo])))) {
            return false;
        }
    }
    return true;
}

function validarUrl($url) {
    if (empty(trim($url))) {
        return true;
    }
    return filter_var($url, FILTER_VALIDATE_URL);
}

$camposObrigatorios = ['id', 'nome', 'email', 'data_formacao', 'pais', 'genero','biografia', 'imagem','site_oficial']; // 'genero' aqui é o musical

if (!validarCampos($data, $camposObrigatorios)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: Nome, E-mail, Data de Formação, País, Gênero Musical, biografia da banda, foto da banda e site."]);
    exit;
}

$id = intval($data['id']);
$nome = $data['nome'];
$email = $data['email'];
$data_formacao = $data['data_formacao'];
$pais = $data['pais'];
$genero = $data['genero']; 

$biografia = isset($data['biografia']) ? trim($data['biografia']) : null;
$imagem = isset($data['imagem']) ? trim($data['imagem']) : null;
$site_oficial = isset($data['site_oficial']) ? trim($data['site_oficial']) : null;



if (empty($biografia) && $biografia !== null) $biografia = null;
if (empty($imagem) && $imagem !== null) $imagem = null;
if (empty($site_oficial) && $site_oficial !== null) $site_oficial = null;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de e-mail inválido."]);
    exit;
}

if ($imagem !== null && !validarUrl($imagem)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "URL da imagem inválida."]);
    exit;
}

if ($site_oficial !== null && !validarUrl($site_oficial)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "URL do site oficial inválida."]);
    exit;
}

try {
    $dFormacao = new DateTime($data_formacao);
    $agora = new DateTime();
    if ($dFormacao > $agora || $dFormacao->format('Y') < 1900) {
        throw new Exception("Data de formação inválida.");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de data de formação inválido."]);
    exit;
}

$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$stmt = $conexao->prepare("SELECT id FROM artista WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Este e-mail já está sendo usado por outro artista."]);
    exit;
}
$stmt->close();

$query = "UPDATE artista SET nome = ?, biografia = ?, email = ?, imagem = ?, data_formacao = ?, pais = ?, site_oficial = ?, genero = ? WHERE id = ?";
$stmt = $conexao->prepare($query);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao preparar a consulta: " . $conexao->error]);
    exit;
}

$stmt->bind_param("ssssssssi", $nome, $biografia, $email, $imagem, $data_formacao, $pais, $site_oficial, $genero, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Perfil atualizado com sucesso!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao atualizar o perfil: " . $stmt->error]);
}

$stmt->close();
$conexao->close();
?>