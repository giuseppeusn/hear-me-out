<?php
if (isset($_GET['id'])) {
    $id_album = intval($_GET['id']);
    $oMysql = connect_db();
    $queryMusicas = "DELETE FROM musica WHERE id_album = $id_album";
    $oMysql->query($queryMusicas);

    $queryAlbum = "DELETE FROM album WHERE id = $id_album";
    $oMysql->query($queryAlbum);

    header('Location: index.php');
    exit;
}
?>
