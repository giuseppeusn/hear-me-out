<?php
include_once("../../connect.php");

$oMysql = connect_db();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id_comentario = intval($data['id']);
    $queryComentarioMusica = "DELETE FROM comentario_musica WHERE id_comentario = $id_comentario";
    $oMysql->query($queryComentarioMusica);

    $queryComentario = "DELETE FROM comentario WHERE id = $id_comentario";
    $oMysql->query($queryComentario);



    echo "Comentário excluída com sucesso.";
} else {
    echo "ID do comentário não foi enviado.";
}
?>
