<?php
try {
    $conexao = new mysqli("localhost", "root", "", "hear_me_out", 3307);
} catch (mysqli_sql_exception $e) {
    echo "Erro na conexão com o banco de dados. Erro: <br>" . $e;
}