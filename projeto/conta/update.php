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

$camposObrigatorios = ['id', 'nome', 'email', 'data_nasc', 'genero'];

if (!validarCampos($data, $camposObrigatorios)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: Nome, E-mail, Data de Nascimento e Gênero."]);
    exit;
}

$id = intval($data['id']);
$nome = $data['nome'];
$email = $data['email'];
$data_nasc = $data['data_nasc'];
$genero = $data['genero'];

if (empty(trim($nome)) || empty(trim($email)) || empty(trim($data_nasc)) || empty(trim($genero))) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: nome, email, data de nascimento e genero."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de e-mail inválido."]);
    exit;
}

$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$stmt = $conexao->prepare("SELECT id FROM usuario WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Este e-mail já está sendo usado por outro usuário."]);
    exit;
}
$stmt->close();

$profileSuccess = false;
$profileMessage = '';
$stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, data_nasc = ?, genero = ? WHERE id = ?");
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao preparar a consulta: " . $conexao->error]);
    exit;
}
$stmt->bind_param("ssssi", $nome, $email, $data_nasc, $genero, $id);

if ($stmt->execute()) {
    $profileSuccess = true;
    $profileMessage = "Perfil atualizado com sucesso!";
} else {
    http_response_code(500);
    $profileMessage = "Erro ao atualizar o perfil: " . $stmt->error;
}
$stmt->close();

$commentSuccess = false;
$commentMessage = '';
$stmt = $conexao->prepare("UPDATE comentario SET nome_autor = ? WHERE id_autor = ?");
if ($stmt === false) {
    $commentMessage = "Erro ao preparar a consulta dos comentários: " . $conexao->error;
} else {
    $stmt->bind_param("si", $nome, $id);
    if ($stmt->execute()) {
        $commentSuccess = true;
        $commentMessage = "Nome dos comentários atualizado!";
    } else {
        $commentMessage = "Erro ao atualizar comentários: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conexao->prepare("SELECT nome FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $_SESSION['nome'] = $row['nome'];
}
$stmt->close();
$conexao->close();

echo json_encode([
    "success" => $profileSuccess && $commentSuccess,
    "profile" => [
        "success" => $profileSuccess,
        "message" => $profileMessage,
    ],
    "comentario" => [
        "success" => $commentSuccess,
        "message" => $commentMessage,
    ]
]);
exit;
?>
