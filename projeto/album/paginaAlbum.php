<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Álbum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/hear-me-out/projeto/musica/insert.js"></script>
    <script src="/hear-me-out/projeto/musica/delete.js"></script>
</head>
<body>

<div class="container mt-3" style="color: white">
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
            musica.capa AS musica_capa,
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
    
    <table class="table table-dark table-striped">
        <button type="button" class="btn btn-success me-2" onclick="abrirInserirMusica(<?= $dadosAlbum->album_id ?>)">Inserir nova música</button>
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
                $numero = 1;

                while ($musica = $resultadoMusicas->fetch_object()) {
                    echo "<div id='musica-{$musica->musica_id}' 
                    data-nome='" . htmlspecialchars($musica->musica_nome, ENT_QUOTES) . "' 
                    data-capa='" . htmlspecialchars($musica->musica_capa, ENT_QUOTES) . "' 
                    data-duracao='" . htmlspecialchars($musica->musica_duracao, ENT_QUOTES) . "'
                    data-data='" . $musica->musica_data . "' 
                    style='display: none;'></div>";

                    $btnAlterarMusica = "<button type='button' class='btn btn-warning me-2' onclick='abrirAlterarMusica({$musica->musica_id})'>Alterar</button>";
                    $btnExcluirMusica = "<button type='button' class='btn btn-danger' onclick='deleteMusica({$musica->musica_id})'>Excluir</button>";
                    $minutosMusica = floor($musica->musica_duracao / 60);
                    $segundosMusica = $musica->musica_duracao % 60;
                    echo "<tr>";
                    echo "<td>{$numero}</td>";
                    echo "<td>{$musica->musica_nome}</td>";
                    echo "<td>AINDA NAO FEITO</td>";
                    echo "<td>" . ($musica->musica_duracao >= 60 
                                    ? "{$minutosMusica} min {$segundosMusica} sec" 
                                    : "{$segundosMusica} sec") . "</td>";
                    echo "<td>{$btnAlterarMusica}{$btnExcluirMusica}</td>";
                    echo "</tr>";

                    $numero++;
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>
                <p> Adicione uma música!</p>
                </td></tr>";
            }

        ?>
        </tbody>
    </table>
    <?php } ?>
</div>
<script src="/hear-me-out/projeto/musica/update.js"></script>
</body>
</html>
