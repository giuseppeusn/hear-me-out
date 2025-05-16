<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <div class="container mt-3">
        <h2>Lista de álbuns</h2>
        <a type="button" class="btn btn-success" href="index.php?page=1">Inserir novo álbum</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Artista</th>
                    <th>Capa</th>
                    <th>Data de lançamento</th>
                    <th>Número de músicas</th>
                    <th>Duração</th>
                </tr>
            </thead>
            <tbody>
                <?php

                include_once '../connect.php';

                $conexao = connect_db();
                $query = "
        SELECT 
          album.id AS album_id,
          album.nome AS album_nome,
          album.duracao,
          album.capa,
          album.data_lancamento,
          album.qtd_musicas,
          artista.nome AS artista_nome
        FROM album
        JOIN artista ON album.id_artista = artista.id
      ";
                $resultado = $conexao->query($query);

                if ($resultado) {
                    while ($linha = $resultado->fetch_object()) {
                        $btn = "<a href='index.php?page=2&id=" . $linha->album_id . "' class='btn btn-warning btn-sm'>Alterar</a>";
                        $btn .= "<a href='index.php?page=3&id=" . $linha->album_id . "' class='btn btn-danger btn-sm m-1'>Excluir</a>";

                        $duracao = $linha->duracao;
                        $minutos = floor($duracao / 60);
                        $segundos = $duracao % 60;
                        echo "<tr>";
                        echo "<td>" . $btn . "</td>";
                        echo "<td>{$linha->album_nome}</td>";
                        echo "<td>{$linha->artista_nome}</td>";
                        echo "<td><img src='{$linha->capa}' width='100'></td>";
                        echo "<td>{$linha->data_lancamento}</td>";
                        echo "<td>{$linha->qtd_musicas}</td>";
                        echo "<td>{$minutos} min {$segundos} sec</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>