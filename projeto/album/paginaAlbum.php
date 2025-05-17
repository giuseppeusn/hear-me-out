<!DOCTYPE html>
<html lang="pt-BR">
<head>
</head>
<body>


<div class="container mt-3">
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Músicas</th>
                    <th>Nota</th>
                    <th>Duração</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
        <?php
        include_once("../connect.php");
        $conexao = connect_db(); 

    if (isset($_GET['id'])) {
        $id_album = intval($_GET['id']);
        $query = "
            SELECT 
                album.id AS album_id,
                album.nome AS album_nome,
                album.capa AS album_capa,
                album.data_lancamento AS album_data,
                album.duracao AS album_duracao,
                album.qtd_musicas AS album_musicas,
                artista.nome AS artista_nome,
                musica.id AS musica_id,
                musica.nome AS musica_nome,
                musica.duracao AS musica_duracao,
                musica.data_lancamento AS musica_data
            FROM album
            INNER JOIN artista ON album.id_artista = artista.id
            LEFT JOIN musica ON album.id = musica.id_album
            WHERE album.id = $id_album
        ";

        $resultado = $conexao->query($query);
    }


        if ($resultado) {
            while ($linha = $resultado->fetch_object()) {
                $btnAlterarMusica = "<a href='/hear-me-out/projeto/musica/update.php?page=2&id=" . $linha->album_id . "' class='btn btn-warning me-2'>Alterar</a>";
                $btnExcluirMusica = "<a href='/hear-me-out/projeto/musica/delete.php?page=3&id=" . $linha->album_id . "' class='btn btn-danger'>Excluir</a>";
                $btnVoltar =  "<a href='index.php?page=0' class='btn btn-light'>< Voltar</a>";
                $tempoAlbum = $linha->album_duracao;  
                $minutos = floor($tempoAlbum / 60);  
                $segundos = $tempoAlbum % 60; 

                echo "
                <div class='container.fluid mt-3'>
                    <div class='row'>
                        <div class='col-1'>$btnVoltar</div>
                            <div class='col-7'>
                                <div class='p-4' style= border-radius: 10px; color: white;'>
                                    <div class='d-flex align-items-center'>
                                        <div class='me-4'>
                                            <img src='{$linha->album_capa}' alt='Capa do álbum' 
                                                style='width: 200px; height: 200px; object-fit: cover; border-radius: 8px;'>
                                        </div>
                                        <div>
                                            <p class='text-uppercase mb-1' style='font-size: 12px;'>Album</p>
                                            <h1 style='font-size: 48px; font-weight: bold; margin: 0;'>{$linha->album_nome}</h1>
                                            <p class='mt-3' style='font-size: 16px; '>
                                                <b>{$linha->artista_nome}</b> • {$linha->album_musicas} músicas, {$minutos} min {$segundos} sec
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        <div class='col-4'></div>   
                    </div>
                </div>";
            if (!empty($linha->musica_id)) {
                echo "<tr>";
                echo "<td>{$linha->musica_id}</td>";
                echo "<td>{$linha->musica_nome}</td>";
                echo "<td>AINDA NAO FEITO</td>";
                echo "<td>{$minutos} min {$segundos} sec</td>";
                echo "<td>".$btnAlterarMusica.$btnExcluirMusica."</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<td colspan='5' class='text-center'>";
                echo "<a href='/hear-me-out/projeto/musica/create.php?id=" . $linha->album_id . "' class='btn btn-success'>Adicionar música ao álbum</a>";
                echo "</td>";
                echo "</tr>";
            }

                

            }
        }
        ?>
    </div>
</div>

</body>
</html>