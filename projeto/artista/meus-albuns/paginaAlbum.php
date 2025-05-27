<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Álbum</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/hear-me-out/projeto/artista/meus-albuns/musicas/insert.js"></script>
    <script src="/hear-me-out/projeto/artista/meus-albuns/musicas/update.js"></script>
    <script src="/hear-me-out/projeto/artista/meus-albuns/musicas/delete.js"></script>
</head>
<body>

<div class="container mt-3" style="color: white">
<?php
    $conexao = connect_db();

    if (isset($_GET['id'])) {
        $id_album = intval($_GET['id']);

        $queryAlbum = "SELECT 
            album.id AS album_id,
            album.nome AS album_nome,
            album.capa AS album_capa,
            album.data_lancamento AS album_data,
            artista.nome AS artista_nome
        FROM album
        INNER JOIN artista ON album.id_artista = artista.id
        WHERE album.id = $id_album";

        $resultadoAlbum = $conexao->query($queryAlbum);
        $dadosAlbum = $resultadoAlbum->fetch_object();

        $queryResumo = "SELECT 
            COUNT(id) AS musicas_total,
            IFNULL(SUM(duracao), 0) AS duracao_total
        FROM musica
        WHERE id_album = $id_album";
        $resultadoResumo = $conexao->query($queryResumo);
        $resumo = $resultadoResumo->fetch_object();

        $queryMusicas = "SELECT 
            musica.id AS musica_id,
            musica.nome AS musica_nome,
            musica.capa AS musica_capa,
            musica.duracao AS musica_duracao,
            musica.data_lancamento AS musica_data
        FROM musica
        WHERE musica.id_album = $id_album";
        $resultadoMusicas = $conexao->query($queryMusicas);
        $musica = $resultadoMusicas->fetch_object();

        function obterMusicas($conexao, $id_album) {
            $sql = "SELECT id AS musica_id, nome AS musica_nome, capa AS musica_capa, duracao AS musica_duracao, data_lancamento AS musica_data
            FROM musica WHERE id_album = $id_album";
            return $conexao->query($sql);
        }
        $musicas = obterMusicas($conexao, $id_album);

        $musicas_total = $resumo->musicas_total;
        $duracao_total = $resumo->duracao_total;
        $minutosAlbum = floor($duracao_total / 60);
        $segundosAlbum = $duracao_total % 60;
        $btnVoltar = "<a href='index.php?page=0' class='btn btn-light'>< Voltar</a>";

        echo "
        <div class='row'>
            <div class='col-1'>$btnVoltar</div>
            <div class='col-7'>
                <div class='p-4'>
                    <div class='d-flex align-items-center'>
                        <div class='me-4'>
                            <img src='{$dadosAlbum->album_capa}' alt='Capa do álbum' style='width: 200px; height: 200px; object-fit: cover; border-radius: 8px;'>
                        </div>
                        <div>
                            <p class='text-uppercase mb-1' style='font-size: 12px;'>Álbum</p>
                            <h1 style='font-size: 36px; font-weight: bold; margin: 0;'>{$dadosAlbum->album_nome}</h1>
                            <p class='mt-3' style='font-size: 16px;'>
                                <b>{$dadosAlbum->artista_nome}</b> • {$musicas_total} músicas, " . 
                                ($duracao_total >= 60 
                                    ? "{$minutosAlbum} min {$segundosAlbum} sec" 
                                    : "{$segundosAlbum} sec") . 
                            "</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>";
        echo "<button type='button' class='btn btn-success me-2' onclick='abrirInserirMusica({$dadosAlbum->album_id})'>Inserir nova música</button>";
        include "musicas/coverCard.php";
        include "musicas/renderList.php";
        if (empty($musica->musica_id)) {
            echo '<h3 style="text-align: center;"> Esse album ainda não tem música!</h3>';
        } else {
            echo renderList(true, $musicas);
        }
    
?>

    <?php } ?>
</div>

</body>
</html>