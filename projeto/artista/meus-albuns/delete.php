<?php
$oMysql = new mysqli("localhost:3306", "root", "", "hear_me_out");
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id_album = intval($data['id']);
    $queryMusicas = "DELETE FROM musica WHERE id_album = $id_album";
    $oMysql->query($queryMusicas);

    $queryAlbum = "DELETE FROM album WHERE id = $id_album";
    $oMysql->query($queryAlbum);

    echo "Álbum excluído com sucesso.";
} else {
    echo "ID do álbum não foi enviado.";
}
?>
