<?php
$oMysql = new mysqli("localhost:3307", "root", "", "hear_me_out");
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id_album = intval($data['id']);
    $oMysql->begin_transaction();

    try {
        $oMysql->query("DELETE FROM comentario_album WHERE id_album = $id_album");
        $comentarioAlbumAfetado = $oMysql->affected_rows;

        $oMysql->query("DELETE FROM avaliacao_album WHERE id_album = $id_album");
        $avaliacaoAlbumAfetado = $oMysql->affected_rows;

        if ($comentarioAlbumAfetado > 0 || $avaliacaoAlbumAfetado > 0) {
            $oMysql->query("
                DELETE FROM comentario
                WHERE id NOT IN (SELECT id_comentario FROM comentario_musica)
                  AND id NOT IN (SELECT id_comentario FROM comentario_album)
            ");

            $oMysql->query("
                DELETE FROM avaliacao
                WHERE id NOT IN (SELECT id_avaliacao FROM avaliacao_musica)
                  AND id NOT IN (SELECT id_avaliacao FROM avaliacao_album)
            ");
        }

        $oMysql->query("DELETE FROM musica WHERE id_album = $id_album");
        $musicasAfetadas = $oMysql->affected_rows;

        $oMysql->query("DELETE FROM album WHERE id = $id_album");
        $albumAfetado = $oMysql->affected_rows;

        if ($albumAfetado > 0) {
            $oMysql->commit();
            echo "Álbum excluído com sucesso.";
        } else {
            $oMysql->rollback();
            echo "Erro ao excluir o álbum.";
        }
    } catch (Exception $e) {
        $oMysql->rollback();
        echo "Erro ao excluir o álbum.";
    }
} else {
    echo "ID do álbum não foi enviado.";
}
?>
