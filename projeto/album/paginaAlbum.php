<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Álbum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-3">
    <?php
    include_once("../connect.php");
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
            musica.duracao AS musica_duracao,
            musica.data_lancamento AS musica_data
        FROM musica
        WHERE musica.id_album = $id_album";

        $resultadoMusicas = $conexao->query($queryMusicas);

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
                            <img src='{$dadosAlbum->album_capa}' alt='Capa do álbum' 
                                style='width: 200px; height: 200px; object-fit: cover; border-radius: 8px;'>
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
    ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Músicas</th>
                <th>Nota</th>
                <th>Duração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($resultadoMusicas->num_rows > 0) {
            while ($musica = $resultadoMusicas->fetch_object()) {
                $btnAlterarMusica = "<a href='/hear-me-out/projeto/musica/update.php?page=2&id={$musica->musica_id}' class='btn btn-warning me-2'>Alterar</a>";
                $btnExcluirMusica = "<a href='/hear-me-out/projeto/musica/delete.php?page=3&id={$musica->musica_id}' class='btn btn-danger'>Excluir</a>";

                $minutosMusica = floor($musica->musica_duracao / 60);
                $segundosMusica = $musica->musica_duracao % 60;

                echo "<tr>";
                echo "<td>{$musica->musica_id}</td>";
                echo "<td>{$musica->musica_nome}</td>";
                echo "<td>AINDA NAO FEITO</td>";
                echo "<td>" . ($musica->musica_duracao >= 60 
                                ? "{$minutosMusica} min {$segundosMusica} sec" 
                                : "{$segundosMusica} sec") . "</td>";
                echo "<td>{$btnAlterarMusica}{$btnExcluirMusica}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>
                <a href='/hear-me-out/projeto/musica/insert.php?id={$dadosAlbum->album_id}' class='btn btn-success'>
                    Adicionar música ao álbum
                </a></td></tr>";
        }
        ?>
        </tbody>
    </table>
    <?php } ?>
</div>
</body>
</html>
