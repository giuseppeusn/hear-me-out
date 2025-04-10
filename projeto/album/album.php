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
                <th>ID</th>
                <th>Nome</th>
                <th>Capa</th>
                <th>Data de lançamento</th>
                <th>Número de músicas</th>
                <th>Duração</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $conexao = connect_db(); 
        $query = "SELECT * FROM album";
        $resultado = $conexao->query($query);
        
        if ($resultado) {
            while ($linha = $resultado->fetch_object()) {
                $btn = "<a href='index.php?page=2&id=" . $linha->id . "' class='btn btn-warning'>Alterar</a>";
                $btn .= "<a href='index.php?page=3&id=" . $linha->id . "' class='btn btn-danger'>Excluir</a>";

                $duracao = $linha->duracao;  
                $minutos = floor($duracao / 60);  
                $segundos = $duracao % 60; 

                echo "<tr>";
                echo "<td>" . $btn . "</td>";
                echo "<td>{$linha->id}</td>";
                echo "<td>{$linha->nome}</td>";
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
