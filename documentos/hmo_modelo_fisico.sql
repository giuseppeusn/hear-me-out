CREATE DATABASE IF NOT EXISTS hear_me_out;
USE hear_me_out;

CREATE TABLE artista (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
	biografia VARCHAR(1000) NOT NULL,
    email VARCHAR(40) NOT NULL,
    imagem VARCHAR(500) NOT NULL,
    data_formacao DATE NOT NULL,
    pais VARCHAR(100) NOT NULL,
    site_oficial VARCHAR(255) NOT NULL,
    genero VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    aprovado BOOL NOT NULL
);

CREATE TABLE usuario (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    email VARCHAR(40) NOT NULL,
    data_nasc DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    genero ENUM('M','F','I'),
    permissao ENUM('normal','admin')
);

CREATE TABLE critico (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    email VARCHAR(40) NOT NULL,
    biografia VARCHAR(1000) NOT NULL,
    data_nasc DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    genero ENUM('M','F','I'),
    site VARCHAR(255) NOT NULL,
    aprovado BOOL NOT NULL
);

CREATE TABLE album (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
    data_lancamento DATE NOT NULL,
    capa VARCHAR(500) NOT NULL,
    id_artista INT NOT NULL,
    FOREIGN KEY (id_artista) REFERENCES artista(id) ON DELETE CASCADE
);

CREATE TABLE musica (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
    duracao INT NOT NULL,
    data_lancamento DATE NOT NULL,
    capa VARCHAR(500) NOT NULL,
    id_artista INT NOT NULL,
    id_album INT NOT NULL,
    FOREIGN KEY (id_artista) REFERENCES artista(id) ON DELETE CASCADE,
    FOREIGN KEY (id_album) REFERENCES album(id) ON DELETE CASCADE
);

CREATE TABLE comentario (
	id INT PRIMARY KEY AUTO_INCREMENT,
    id_autor INT NOT NULL,
    nome_autor VARCHAR(60) NOT NULL,
    mensagem VARCHAR(250) NOT NULL
);

CREATE TABLE comentario_musica (
	id_musica INT NOT NULL,
    id_comentario INT NOT NULL,
    PRIMARY KEY (id_musica, id_comentario),
    FOREIGN KEY (id_musica) REFERENCES musica(id) ON DELETE CASCADE,
    FOREIGN KEY (id_comentario) REFERENCES comentario(id) ON DELETE CASCADE
);

CREATE TABLE comentario_album (
	id_album INT NOT NULL,
    id_comentario INT NOT NULL,
    PRIMARY KEY (id_album, id_comentario),
    FOREIGN KEY (id_album) REFERENCES album(id) ON DELETE CASCADE,
    FOREIGN KEY (id_comentario) REFERENCES comentario(id) ON DELETE CASCADE
);

CREATE TABLE avaliacao (
	id INT PRIMARY KEY AUTO_INCREMENT,
    mensagem VARCHAR(500) NOT NULL,
    nota FLOAT NOT NULL,
    id_usuario INT,
	id_critico INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (id_critico) REFERENCES critico(id) ON DELETE CASCADE,
    CHECK (
		(id_usuario IS NOT NULL AND id_critico IS NULL) OR 
        (id_usuario IS NULL AND id_critico IS NOT NULL)
    )
);

CREATE TABLE avaliacao_musica (
	id_avaliacao INT NOT NULL,
    id_musica INT NOT NULL,
    PRIMARY KEY (id_avaliacao, id_musica),
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id) ON DELETE CASCADE,
    FOREIGN KEY (id_musica) REFERENCES musica(id) ON DELETE CASCADE
);

CREATE TABLE avaliacao_album (
	id_avaliacao INT NOT NULL,
    id_album INT NOT NULL,
    PRIMARY KEY (id_avaliacao, id_album),
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id) ON DELETE CASCADE,
    FOREIGN KEY (id_album) REFERENCES album(id) ON DELETE CASCADE
);

-- Views

CREATE VIEW view_musicas_com_nomes AS
SELECT
    musica.id AS id_musica,
    musica.nome AS nome_musica,
    album.nome AS nome_album,
    artista.nome AS nome_artista,
    musica.duracao,
    musica.data_lancamento,
    musica.capa
FROM
    musica
JOIN album ON musica.id_album = album.id
JOIN artista ON musica.id_artista = artista.id;

CREATE VIEW view_albuns_com_nomes AS
SELECT
	album.id AS album_id,
    artista.id AS id_artista,
    album.nome AS nome_album,
    artista.nome AS nome_artista,
    album.data_lancamento,
    album.capa
FROM
    album
JOIN artista ON album.id_artista = artista.id;

CREATE OR REPLACE VIEW view_avaliacoes_album AS
SELECT 
    aa.id_album,
    a.id AS id_avaliacao,
    a.mensagem,
    a.nota,
    COALESCE(a.id_usuario, a.id_critico) AS id_avaliador,
    CASE 
        WHEN a.id_usuario IS NOT NULL THEN 'usuario'
        WHEN a.id_critico IS NOT NULL THEN 'critico'
        ELSE 'desconhecido'
    END AS tipo_avaliador,
    COALESCE(u.nome, c.nome) AS nome_avaliador
FROM 
    avaliacao_album aa
JOIN 
    avaliacao a ON aa.id_avaliacao = a.id
LEFT JOIN 
    usuario u ON a.id_usuario = u.id
LEFT JOIN 
    critico c ON a.id_critico = c.id;

CREATE OR REPLACE VIEW view_avaliacoes_musica AS
SELECT 
    am.id_musica,
    a.id AS id_avaliacao,
    a.mensagem,
    a.nota,
    COALESCE(a.id_usuario, a.id_critico) AS id_avaliador,
    CASE 
        WHEN a.id_usuario IS NOT NULL THEN 'usuario'
        WHEN a.id_critico IS NOT NULL THEN 'critico'
        ELSE 'desconhecido'
    END AS tipo_avaliador,
    COALESCE(u.nome, c.nome) AS nome_avaliador
FROM 
    avaliacao_musica am
JOIN 
    avaliacao a ON am.id_avaliacao = a.id
LEFT JOIN 
    usuario u ON a.id_usuario = u.id
LEFT JOIN 
    critico c ON a.id_critico = c.id;
