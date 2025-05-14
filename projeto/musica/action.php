<?php
session_start();
include_once  "../connect.php";

$conexao = connect_db();

function msg(){
    $_SESSION['mensagem'] = "Operação realizada com sucesso!";
    header("Location: index.php");
    exit;
}

function searchArtistaAlbum($conexao, $artista_musica, $album_musica){
    $query_artista = "SELECT id FROM artista WHERE nome = '$artista_musica'";
    $query_album = "SELECT id FROM album WHERE nome = '$album_musica'";

    $resultado_artista = mysqli_query($conexao, $query_artista);
    $resultado_album = mysqli_query($conexao, $query_album);
    
    if (mysqli_num_rows($resultado_artista) > 0) {
        foreach ($resultado_artista as $i) {
            $artista_musica = $i['id'];
        }
    } else {
        $_SESSION['mensagem'] = "Nenhum artista encontrado com o nome: " . $artista_musica;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die;
    }

    if (mysqli_num_rows($resultado_album) > 0) {
        foreach ($resultado_album as $i) { // mesma coisa do fetch_assoc, nesse caso o foreach itera o objeto e ao mesmo tempo da fetch assoc em todas as linhas.
            $album_musica = $i['id'];
        }
    } else {
        $_SESSION['mensagem'] = "Nenhum álbum encontrado com o nome: " . $album_musica;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die;
    }

    return [$artista_musica, $album_musica];
}

if (isset($_POST['create'])) {
    // pega os valores do formulário
    $nome_musica = mysqli_real_escape_string($conexao, $_POST['nome_musica']);
    $duracao_musica = (int) $_POST['duracao_musica']; // Garante que seja um número
    $data_lancamento_musica = mysqli_real_escape_string($conexao, $_POST['data_lancamento_musica']);
    $capa_musica_arquivo = mysqli_real_escape_string($conexao, $_POST['capa_musica_arquivo']);
    $artista_musica = mysqli_real_escape_string($conexao, $_POST['artista_musica']);
    $album_musica = mysqli_real_escape_string($conexao, $_POST['album_musica']);

    // buscar artista e album 
    list($artista_musica, $album_musica) = searchArtistaAlbum($conexao, $artista_musica, $album_musica);

    $query = "INSERT INTO musica (nome, duracao, data_lancamento, capa, id_artista, id_album) 
                  VALUES ('$nome_musica', $duracao_musica, '$data_lancamento_musica', '$capa_musica_arquivo', '$artista_musica', '$album_musica')";
    // executa a query
    if(mysqli_query($conexao, $query)){
        msg();
    }
}

if (isset($_POST['edit'])) {

    // pega os valores do formulário
    $id = mysqli_real_escape_string($conexao, $_POST['id_hidden']);
    $nome_musica = mysqli_real_escape_string($conexao, $_POST['nome_musica']);
    $duracao_musica = (int) $_POST['duracao_musica']; // Garante que seja um número
    $data_lancamento_musica = mysqli_real_escape_string($conexao, $_POST['data_lancamento_musica']);
    $capa_musica_arquivo = mysqli_real_escape_string($conexao, $_POST['capa_musica_arquivo']);
    $artista_musica = mysqli_real_escape_string($conexao, $_POST['artista_musica']);
    $album_musica = mysqli_real_escape_string($conexao, $_POST['album_musica']);

    // buscar artista e album 
    list($artista_musica, $album_musica) = searchArtistaAlbum($conexao, $artista_musica, $album_musica);
    
    $query_update = "UPDATE musica SET nome = '$nome_musica', duracao = '$duracao_musica', data_lancamento = '$data_lancamento_musica', capa = '$capa_musica_arquivo', id_artista = '$artista_musica', id_album = '$album_musica' where id = '$id'";
    if(mysqli_query($conexao, $query_update)){
        msg();
    }
}

?>
