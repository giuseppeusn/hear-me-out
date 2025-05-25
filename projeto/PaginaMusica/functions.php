<?php
function obterAlbum($conexao, $album_id) {
    $sql = "SELECT album.id AS album_id, album.nome AS album_nome, album.capa AS album_capa, album.data_lancamento AS album_data, artista.nome AS artista_nome, album.id_artista AS album_idArtista
            FROM album
            INNER JOIN artista ON album.id_artista = artista.id
            WHERE album.id = $album_id";
    return $conexao->query($sql)->fetch_object();
}
function obterMusica($conexao, $musica_id) {
    $sql = "SELECT musica.id AS musica_id, musica.nome AS musica_nome, musica.capa AS musica_capa, musica.data_lancamento AS musica_data, artista.nome AS artista_nome, musica.id_artista AS musica_idArtista
            FROM musica
            INNER JOIN artista ON musica.id_artista = artista.id
            WHERE musica.id = $album_id";
    return $conexao->query($sql)->fetch_object();
}
function obterResumoAlbum($conexao, $album_id) {
    $sql = "SELECT COUNT(id) AS musicas_total, IFNULL(SUM(duracao), 0) AS duracao_total
            FROM musica WHERE id_album = $album_id";
    return $conexao->query($sql)->fetch_object();
}

function obterMusicas($conexao, $album_id) {
    $sql = "SELECT id AS musica_id, nome AS musica_nome, capa AS musica_capa, duracao AS musica_duracao, data_lancamento AS musica_data
            FROM musica WHERE id_album = $album_id";
    return $conexao->query($sql);
}

function obterComentarios($conexao, $musica_id) {
    $sql = "SELECT comentario.id AS comentario_id, comentario.mensagem AS comentario_mensagem, comentario.nome_autor AS comentario_nome, comentario.id_autor AS comentario_idAutor
            FROM comentario
            INNER JOIN comentario_musica ON comentario.id = comentario_musica.id_comentario
            WHERE comentario_musica.id_album = $musica_id";
    return $conexao->query($sql);
}

function obterAvaliacoesMusica($conn, $musica_id, $avaliador_id, $tipoAvaliador) {
    $sql = "SELECT * FROM view_avaliacoes_musica WHERE id_musica = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $musica_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $avaliacoes = [
        'medias' => ['usuario' => 0, 'critico' => 0],
        'quantidades' => ['usuario' => 0, 'critico' => 0],
        'avaliacoes' => ['usuario' => [], 'critico' => []],
        'minhaAvaliacao' => null,
    ];

    $somas = ['usuario' => 0, 'critico' => 0];

    while ($row = $result->fetch_assoc()) {
        $tipo = $row['tipo_avaliador'];
        $nota = floatval($row['nota']);

        if (in_array($tipo, ['usuario', 'critico'])) {
            $avaliacoes['avaliacoes'][$tipo][] = [
                'id_avaliacao' => $row['id_avaliacao'],
                'mensagem' => $row['mensagem'],
                'nota' => $nota,
                'nome_avaliador' => $row['nome_avaliador'],
                'id_avaliador' => intval($row['id_avaliador'])
            ];

            $somas[$tipo] += $nota;
            $avaliacoes['quantidades'][$tipo]++;

            if ($tipo === $tipoAvaliador && intval($row['id_avaliador']) === intval($avaliador_id)) {
                $avaliacoes['minhaAvaliacao'] = [
                    'id_avaliacao' => $row['id_avaliacao'],
                    'mensagem' => $row['mensagem'],
                    'nota' => $nota
                ];
            }
        }
    }

    foreach (['usuario', 'critico'] as $tipo) {
        $quantidade = $avaliacoes['quantidades'][$tipo];
        $avaliacoes['medias'][$tipo] = $quantidade > 0 ? round($somas[$tipo] / $quantidade, 1) : 0;
    }

    return $avaliacoes;
}

function formatarDuracao($duracao) {
    $min = floor($duracao / 60);
    $seg = $duracao % 60;
    return $duracao >= 60 ? "{$min} min {$seg} s" : "{$seg} s";
}
?>
