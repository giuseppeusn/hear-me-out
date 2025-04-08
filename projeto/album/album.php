<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Lista de dados</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-3">
    <h2>Lista de álbuns</h2>
    <button type="button" class="btn" onclick="window.location.href='insert.php'">Basic</button>

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
                $btn = "<a href='alterar.php" . $linha->id . "' class='btn btn-warning'>Alterar</a>";
                $btn .= "<a href='delete.php" . $linha->id . "' class='btn btn-danger'>Excluir</a>";

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
