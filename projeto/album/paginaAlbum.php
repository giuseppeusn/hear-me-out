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

        $query = "
            SELECT 
                musica.id AS musica_id,
                musica.nome AS musica_nome,
                musica.duracao AS musica_duracao,
                musica.data_lancamento AS musica_data,
                musica.capa AS musica_capa,
                musica.id_album AS musica_album_id,
                musica.id_artista AS musica_artista_id,

                album.id AS album_id,
                album.nome AS album_nome,
                album.duracao AS album_duracao,
                album.data_lancamento AS album_data,
                album.capa AS album_capa,
                album.qtd_musicas AS album_musicas,
                album.id_artista AS album_artista_id,

                artista.id AS artista_id,
                artista.nome AS artista_nome,
                artista.biografia AS artista_biografia,
                artista.email AS artista_email,
                artista.imagem AS artista_imagem,
                artista.data_formacao AS artista_data_formacao,
                artista.pais AS artista_pais,
                artista.site_oficial AS artista_site,
                artista.genero AS artista_genero
            FROM musica
            JOIN album ON musica.id_album = album.id
            JOIN artista ON musica.id_artista = artista.id";

        $resultado = $conexao->query($query);

        if ($resultado) {
            while ($linha = $resultado->fetch_object()) {
                $btnAlterarMusica = "<a href='/hear-me-out/projeto/musica/update.php?page=2&id=" . $linha->musica_id . "' class='btn btn-warning me-2'>Alterar</a>";
                $btnExcluirMusica = "<a href='/hear-me-out/projeto/musica/delete.php?page=3&id=" . $linha->musica_id . "' class='btn btn-danger'>Excluir</a>";
                $btnVoltar =  "<a href='index.php?page=0&id=" . $linha->album_id . "' class='btn btn-light'>< Voltar</a>";
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
                echo "<tr>";
                echo "<td>{$linha->musica_id}</td>";
                echo "<td>{$linha->musica_nome}<br>{$linha->artista_nome}</td>";
                echo "<td>AINDA NAO FEITO</td>";
                echo "<td>{$linha->musica_duracao}</td>";
                echo "<td>".$btnAlterarMusica.$btnExcluirMusica."</td>";
                echo "</tr>";
                
                

            }
        }
        ?>
    </div>
</div>

</body>
</html>
