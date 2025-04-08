<?php
include "conexao.php";

$id = $_GET['id'];
if(isset($id)){
    $query = "DELETE from musica where id = '$id'";
    mysqli_query($conexao, $query);
    header("Location: index.php");
}

?>