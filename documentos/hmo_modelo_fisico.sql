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
    senha VARCHAR(255) NOT NULL
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
    duracao INT NOT NULL,
    data_lancamento DATE NOT NULL,
    capa VARCHAR(500) NOT NULL,
    qtd_musicas INT NOT NULL,
    id_artista INT NOT NULL,
    
    FOREIGN KEY (id_artista) REFERENCES artista(id)
);

CREATE TABLE musica (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL,
    duracao INT NOT NULL,
    data_lancamento DATE NOT NULL,
    capa VARCHAR(500) NOT NULL,
    id_artista INT NOT NULL,
    id_album INT NOT NULL,
    
    FOREIGN KEY (id_artista) REFERENCES artista(id),
    FOREIGN KEY (id_album) REFERENCES album(id)
);

CREATE TABLE comentario (
	id INT PRIMARY KEY AUTO_INCREMENT,
    mensagem VARCHAR(250) NOT NULL,
    id_usuario INT NOT NULL,
    
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

CREATE TABLE comentario_musica (
	id_musica INT NOT NULL,
    id_comentario INT NOT NULL,
    
    FOREIGN KEY (id_musica) REFERENCES musica(id),
    FOREIGN KEY (id_comentario) REFERENCES comentario(id)
);

CREATE TABLE comentario_album (
	id_album INT NOT NULL,
    id_comentario INT NOT NULL,
    
    FOREIGN KEY (id_album) REFERENCES album(id),
    FOREIGN KEY (id_comentario) REFERENCES comentario(id)
);
    
CREATE TABLE avaliacao (
	id INT PRIMARY KEY AUTO_INCREMENT,
    mensagem VARCHAR(500) NOT NULL,
    nota FLOAT NOT NULL,
    id_usuario INT,
	id_critico INT,
    
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_critico) REFERENCES critico(id),
    
    CHECK (
		(id_usuario IS NOT NULL AND id_critico IS NULL) OR 
        (id_usuario IS NULL AND id_critico IS NOT NULL)
    )
);

CREATE TABLE avaliacao_musica(
	id_avaliacao INT,
    id_musica INT,
    
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id),
    FOREIGN KEY (id_musica) REFERENCES musica(id)
);

CREATE TABLE avaliacao_album(
	id_avaliacao INT,
    id_album INT,
    
    FOREIGN KEY (id_avaliacao) REFERENCES avaliacao(id),
    FOREIGN KEY (id_album) REFERENCES album(id)
);
    
    
    