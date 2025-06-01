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

$camposObrigatorios = ['id', 'nome', 'email', 'data_nasc', 'genero', 'biografia', 'site'];

if (!validarCampos($data, $camposObrigatorios)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: Nome, E-mail, Data de Nascimento, Gênero, Biografia e Site."]);
    exit;
}

$id = intval($data['id']);
$nome = $data['nome'];
$email = $data['email'];
$data_nasc = $data['data_nasc'];
$genero = $data['genero'];
$biografia = $data['biografia'];
$site = $data['site'];

$resposta = [
    "success" => false,
    "profile" => [
        "success" => false,
        "message" => ""
    ],
    "comentario" => [
        "success" => false,
        "message" => ""
    ]
];

if (empty(trim($nome)) || empty(trim($email)) || empty(trim($data_nasc)) || empty(trim($genero)) || empty(trim($biografia)) || empty(trim($site))) {
    http_response_code(400);
    $resposta["profile"]["message"] = "Preencha os campos obrigatórios: nome, email, data de nascimento, genero, biografia, site.";
    echo json_encode($resposta);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    $resposta["profile"]["message"] = "Formato de e-mail inválido.";
    echo json_encode($resposta);
    exit;
}

if (!validarUrl($site)) {
    http_response_code(400);
    $resposta["profile"]["message"] = "URL do site inválida.";
    echo json_encode($resposta);
    exit;
}

$conexao = connect_db();

if (!$conexao) {
    http_response_code(500);
    $resposta["profile"]["message"] = "Erro ao conectar ao banco de dados.";
    echo json_encode($resposta);
    exit;
}

$stmt = $conexao->prepare("SELECT id FROM critico WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $stmt->close();
    http_response_code(400);
    $resposta["profile"]["message"] = "Este e-mail já está sendo usado por outro crítico.";
    echo json_encode($resposta);
    exit;
}
$stmt->close();

$stmt = $conexao->prepare("UPDATE critico SET nome = ?, email = ?, data_nasc = ?, genero = ?, biografia = ?, site = ? WHERE id = ?");
if ($stmt === false) {
    http_response_code(500);
    $resposta["profile"]["message"] = "Erro ao preparar a consulta: " . $conexao->error;
    echo json_encode($resposta);
    exit;
}
$stmt->bind_param("ssssssi", $nome, $email, $data_nasc, $genero, $biografia, $site, $id);

if ($stmt->execute()) {
    $resposta["profile"]["success"] = true;
    $resposta["profile"]["message"] = "Perfil atualizado com sucesso!";
} else {
    http_response_code(500);
    $resposta["profile"]["message"] = "Erro ao atualizar o perfil: " . $stmt->error;
}
$stmt->close();


$stmt = $conexao->prepare("UPDATE comentario SET nome_autor = ? WHERE id_autor = ?");
if ($stmt === false) {
    $resposta["comentario"]["message"] = "Erro ao preparar a atualização dos comentários: " . $conexao->error;
} else {
    $stmt->bind_param("si", $nome, $id);
    if ($stmt->execute()) {
        $resposta["comentario"]["success"] = true;
        $resposta["comentario"]["message"] = "Nome do crítico atualizado nos comentários!";
    } else {
        $resposta["comentario"]["message"] = "Erro ao atualizar comentários: " . $stmt->error;
    }
    $stmt->close();
}


$stmt = $conexao->prepare("SELECT nome FROM critico WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $_SESSION['nome'] = $row['nome'];
}
$stmt->close();
$conexao->close();

$resposta["success"] = $resposta["profile"]["success"] && $resposta["comentario"]["success"];
echo json_encode($resposta);
exit;
?>
