<!DOCTYPE html>
<html lang="pt-BR">
<body>
<div class="container mt-3">
    <h2>Lista de Criticos</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Nome</th>
                <th>Biografia</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Data nascimento</th>
                <th>Site</th>
                <th>Genero</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $conexao = connect_db(); 
        $query = "SELECT * FROM critico";
        $resultado = $conexao->query($query);
        
        if ($resultado) {
            while ($linha = $resultado->fetch_object()) {
                $btn = "<a href='index.php?page=2&id=" . $linha->id . "' class='btn btn-warning'>Alterar</a>";
                $btn .= "<a href='index.php?page=3&id=" . $linha->id . "' class='btn btn-danger'>Excluir</a>";

                echo "<tr>";
                echo "<td>" . $btn . "</td>";
                echo "<td>{$linha->id}</td>";
                echo "<td>{$linha->nome}</td>";
                echo "<td>{$linha->biografia}</td>";
                echo "<td>{$linha->cpf}</td>";
                echo "<td>{$linha->email}</td>";
                echo "<td>{$linha->data_nasc}</td>";
                echo "<td><a href='{$linha->site}' target='_blank'>{$linha->site}</a></td>";
                echo "<td>{$linha->genero}</td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
