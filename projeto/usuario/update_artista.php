<?php
session_start();
// **CORREÇÃO:** O connect.php está na pasta PAI (superior) do diretório 'projeto'.
// Portanto, o caminho correto é "../connect.php".
include_once("../connect.php"); 

header('Content-Type: application/json');

// Desabilitar a exibição de erros no output para garantir que apenas JSON seja retornado.
// É recomendado configurar isso no php.ini em ambiente de produção.
ini_set('display_errors', 0);
ini_set('log_errors', 1);
// Opcional: define um arquivo de log para ver os erros de PHP
// ini_set('error_log', __DIR__ . '/../php-error.log'); 

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Dados inválidos!"]);
    exit;
}

// Função para validar campos obrigatórios
function validarCampos($data, $camposObrigatorios) {
    foreach ($camposObrigatorios as $campo) {
        // Verifica se o campo existe e não está vazio após remover espaços em branco
        if (!isset($data[$campo]) || (is_string($data[$campo]) && empty(trim($data[$campo])))) {
            return false;
        }
    }
    return true;
}

// Função para validar URL
function validarUrl($url) {
    // Permite que o campo seja vazio, mas se não for, deve ser uma URL válida
    if (empty(trim($url))) {
        return true; 
    }
    return filter_var($url, FILTER_VALIDATE_URL);
}

// Campos obrigatórios para artista (ajuste conforme seu esquema de BD)
// Baseado no hmo_dump.sql e hmo_modelo_fisico.sql, 'biografia', 'imagem', 'site_oficial' são NULLABLE.
$camposObrigatorios = ['id', 'nome', 'email', 'data_formacao', 'pais', 'genero'];


if (!validarCampos($data, $camposObrigatorios)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha os campos obrigatórios: Nome, E-mail, Data de Formação, País e Gênero Musical."]);
    exit;
}

$id = intval($data['id']);
$nome = $data['nome'];
$email = $data['email'];
$data_formacao = $data['data_formacao'];
$pais = $data['pais'];
$genero = $data['genero'];

// Campos opcionais (verifique se eles foram enviados e sanitize se necessário)
$biografia = isset($data['biografia']) ? trim($data['biografia']) : null;
$imagem = isset($data['imagem']) ? trim($data['imagem']) : null;
$site_oficial = isset($data['site_oficial']) ? trim($data['site_oficial']) : null;

// Se os campos opcionais forem enviados vazios, converta para NULL para o banco de dados
if (empty($biografia)) $biografia = null;
if (empty($imagem)) $imagem = null;
if (empty($site_oficial)) $site_oficial = null;


// Validações adicionais
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de e-mail inválido."]);
    exit;
}

// Validar URLs, permitindo que sejam nulas ou vazias
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

// Validação da data de formação (opcional, mas pode ser útil)
try {
    $dFormacao = new DateTime($data_formacao);
    $agora = new DateTime();
    // A data de formação não pode ser no futuro e deve ser uma data razoável (ex: ano > 1900)
    if ($dFormacao > $agora || $dFormacao->format('Y') < 1900) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Data de formação inválida."]);
        exit;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Formato de data de formação inválido."]);
    exit;
}


$conexao = connect_db();

if (!$conexao) {
    // Se a conexão falhar, garantimos que a mensagem seja JSON
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao conectar ao banco de dados."]);
    exit;
}

// Verificar se o email já existe para outro artista (excluindo o próprio)
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

// Atualizar dados do artista
$query = "UPDATE artista SET nome = ?, biografia = ?, email = ?, imagem = ?, data_formacao = ?, pais = ?, site_oficial = ?, genero = ? WHERE id = ?";
$stmt = $conexao->prepare($query);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao preparar a consulta: " . $conexao->error]);
    exit;
}

// Para campos que podem ser NULL no banco de dados, use 's' para string e passe null para bind_param.
// Se os campos opcionais forem NULL, bind_param aceita NULL para strings.
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