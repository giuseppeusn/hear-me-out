CREATE DATABASE  IF NOT EXISTS `hear_me_out` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `hear_me_out`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: hear_me_out
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `data_lancamento` date NOT NULL,
  `capa` varchar(500) NOT NULL,
  `id_artista` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_artista` (`id_artista`),
  CONSTRAINT `album_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artista` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,'A Rush of Blood to the Head','2002-08-26','https://upload.wikimedia.org/wikipedia/pt/4/45/A_Rush_of_Blood_to_the_Head.jpg',1),(2,'Cowboy Carter','2024-05-24','https://upload.wikimedia.org/wikipedia/en/a/aa/Beyonc%C3%A9_-_Cowboy_Carter.png',2),(3,'1989','2014-10-27','https://upload.wikimedia.org/wikipedia/en/f/f6/Taylor_Swift_-_1989.png',3),(4,'Austin','2023-07-28','https://upload.wikimedia.org/wikipedia/pt/5/52/Austin_-_Post_Malone.png',4),(5,'Beerbongs & Bentleys','2018-04-27','https://cdn-images.dzcdn.net/images/cover/c000a4d39f31f3716bf3f11aa5fab080/500x500-000000-80-0-0.jpg',4),(6,'From Zero','2024-11-15','https://upload.wikimedia.org/wikipedia/en/9/90/Linkin_Park_-_From_Zero.png',5),(7,'Evolve','2017-06-23','https://upload.wikimedia.org/wikipedia/pt/b/b5/ImagineDragonsEvolve.jpg',6),(8,'Mercury – Acts 1 & 2','2022-07-01','https://upload.wikimedia.org/wikipedia/en/d/d9/Imagine_Dragons_Mercury_album_cover_2022.webp',6),(9,'Loom','2024-06-28','https://upload.wikimedia.org/wikipedia/en/4/44/Imagine_Dragons_-_Loom.png',6);
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artista`
--

DROP TABLE IF EXISTS `artista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `artista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `biografia` varchar(1000) NOT NULL,
  `email` varchar(40) NOT NULL,
  `imagem` varchar(500) NOT NULL,
  `data_formacao` date NOT NULL,
  `pais` varchar(100) NOT NULL,
  `site_oficial` varchar(255) NOT NULL,
  `genero` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `aprovado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artista`
--

LOCK TABLES `artista` WRITE;
/*!40000 ALTER TABLE `artista` DISABLE KEYS */;
INSERT INTO `artista` VALUES (1,'Coldplay','Coldplay é uma banda britânica de rock formada em Londres em 1996.','coldplay@email.com','https://upload.wikimedia.org/wikipedia/commons/2/2e/ColdplayBBC071221_%28cropped%29.jpg','1996-01-01','Reino Unido','https://www.coldplay.com','Rock alternativo','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(2,'Beyoncé','Beyoncé é uma cantora, compositora e atriz norte-americana.','beyonce@email.com','https://upload.wikimedia.org/wikipedia/commons/9/91/Beyonc%C3%A9_-_Tottenham_Hotspur_Stadium_-_1st_June_2023_%2811_of_118%29_%2852946364483%29_%28face_cropped%29.jpg','1997-01-01','Estados Unidos','https://www.beyonce.com','Pop','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(3,'Taylor Swift','Taylor Swift é uma cantora e compositora norte-americana.','taylor@email.com','https://upload.wikimedia.org/wikipedia/commons/e/e8/TaylorSwiftApr09.jpg','2006-01-01','Estados Unidos','https://www.taylorswift.com','Pop','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(4,'Post Malone','Post Malone é um cantor, rapper e compositor norte-americano conhecido por misturar estilos como hip hop, pop, rock e trap.','postmalone@email.com','https://hips.hearstapps.com/hmg-prod/images/post-malone-attends-the-road-house-world-premiere-during-news-photo-1727445361.jpg?crop=0.757xw:1.00xh;0.0986xw,0&resize=1200:*','2015-01-01','Estados Unidos','https://www.postmalone.com','Hip hop','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(5,'Linkin Park','Linkin Park foi uma banda de rock alternativo/nu metal dos Estados Unidos, formada em 1996 e conhecida por mesclar rock, rap e música eletrônica.','linkinpark@email.com','https://upload.wikimedia.org/wikipedia/commons/d/d8/Linkin_Park_-_From_Zero_Lead_Press_Photo_-_James_Minchin_III.jpg','1996-01-01','Estados Unidos','https://www.linkinpark.com','Rock alternativo','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(6,'Imagine Dragons','Imagine Dragons é uma banda de pop rock americana formada em Las Vegas, Nevada. Conhecida por hits como Radioactive, Believer e Demons.','imaginedragons@email.com','https://s2-gshow.glbimg.com/0wmhS5xGywLlXR9yfeXBO_2rR3o=/0x0:942x623/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_e84042ef78cb4708aeebdf1c68c6cbd6/internal_photos/bs/2023/V/2/BlhJ8gQoOESx3yDzGaTg/imagine-dragons.jpg','2008-01-01','Estados Unidos','https://www.imaginedragonsmusic.com','Pop rock','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1),(9,'Artista','Artista','artista@email.com','https://upload.wikimedia.org/wikipedia/commons/a/a3/Image-not-found.png','2000-01-01','Brasil','https://www.google.com.br/','Rock','$2y$10$hBzjRskV4PS/DdJtseu55uivLmVh0rlAfkkHqGMxhHwNOBJGYmgI2',1);
/*!40000 ALTER TABLE `artista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensagem` varchar(500) NOT NULL,
  `nota` float NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_critico` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_critico` (`id_critico`),
  CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`id_critico`) REFERENCES `critico` (`id`) ON DELETE CASCADE,
  CONSTRAINT `CONSTRAINT_1` CHECK (`id_usuario` is not null and `id_critico` is null or `id_usuario` is null and `id_critico` is not null)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
INSERT INTO `avaliacao` VALUES (2,'Bom álbum, porém nem tão bom',3.7,NULL,1),(5,'Avaliação teste',4,7,NULL),(8,'Excelente álbum',5,6,NULL);
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao_album`
--

DROP TABLE IF EXISTS `avaliacao_album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao_album` (
  `id_avaliacao` int(11) DEFAULT NULL,
  `id_album` int(11) DEFAULT NULL,
  KEY `id_avaliacao` (`id_avaliacao`),
  KEY `id_album` (`id_album`),
  CONSTRAINT `avaliacao_album_ibfk_1` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacao_album_ibfk_2` FOREIGN KEY (`id_album`) REFERENCES `album` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao_album`
--

LOCK TABLES `avaliacao_album` WRITE;
/*!40000 ALTER TABLE `avaliacao_album` DISABLE KEYS */;
INSERT INTO `avaliacao_album` VALUES (2,9),(5,9),(8,9);
/*!40000 ALTER TABLE `avaliacao_album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao_musica`
--

DROP TABLE IF EXISTS `avaliacao_musica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao_musica` (
  `id_avaliacao` int(11) DEFAULT NULL,
  `id_musica` int(11) DEFAULT NULL,
  KEY `id_avaliacao` (`id_avaliacao`),
  KEY `id_musica` (`id_musica`),
  CONSTRAINT `avaliacao_musica_ibfk_1` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacao_musica_ibfk_2` FOREIGN KEY (`id_musica`) REFERENCES `musica` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao_musica`
--

LOCK TABLES `avaliacao_musica` WRITE;
/*!40000 ALTER TABLE `avaliacao_musica` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao_musica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_autor` int(11) NOT NULL,
  `nome_autor` varchar(60) NOT NULL,
  `mensagem` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
INSERT INTO `comentario` VALUES (2,9,'Artista','Adoro esse álbum!'),(5,6,'Usuário teste','Esse álbum não é dos melhores, mas tem algumas boas músicas');
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario_album`
--

DROP TABLE IF EXISTS `comentario_album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario_album` (
  `id_album` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  KEY `id_album` (`id_album`),
  KEY `id_comentario` (`id_comentario`),
  CONSTRAINT `comentario_album_ibfk_1` FOREIGN KEY (`id_album`) REFERENCES `album` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentario_album_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario_album`
--

LOCK TABLES `comentario_album` WRITE;
/*!40000 ALTER TABLE `comentario_album` DISABLE KEYS */;
INSERT INTO `comentario_album` VALUES (9,2),(9,5);
/*!40000 ALTER TABLE `comentario_album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario_musica`
--

DROP TABLE IF EXISTS `comentario_musica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario_musica` (
  `id_musica` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  KEY `id_musica` (`id_musica`),
  KEY `id_comentario` (`id_comentario`),
  CONSTRAINT `comentario_musica_ibfk_1` FOREIGN KEY (`id_musica`) REFERENCES `musica` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentario_musica_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario_musica`
--

LOCK TABLES `comentario_musica` WRITE;
/*!40000 ALTER TABLE `comentario_musica` DISABLE KEYS */;
/*!40000 ALTER TABLE `comentario_musica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `critico`
--

DROP TABLE IF EXISTS `critico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `critico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `biografia` varchar(1000) NOT NULL,
  `data_nasc` date NOT NULL,
  `senha` varchar(255) NOT NULL,
  `genero` enum('M','F','I') DEFAULT NULL,
  `site` varchar(255) NOT NULL,
  `aprovado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `critico`
--

LOCK TABLES `critico` WRITE;
/*!40000 ALTER TABLE `critico` DISABLE KEYS */;
INSERT INTO `critico` VALUES (1,'Crítico','40582302072','critico@email.com','critico                        ','1990-01-01','$2y$10$U9/zrQ7QmPWXz.obV5zYDeo9Yi5W2ocJ/Y2uo2jiUKIy.LIRy9bYu','','https://www.google.com.br/',1);
/*!40000 ALTER TABLE `critico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `musica`
--

DROP TABLE IF EXISTS `musica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `musica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `duracao` int(11) NOT NULL,
  `data_lancamento` date NOT NULL,
  `capa` varchar(500) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_artista` (`id_artista`),
  KEY `id_album` (`id_album`),
  CONSTRAINT `musica_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artista` (`id`) ON DELETE CASCADE,
  CONSTRAINT `musica_ibfk_2` FOREIGN KEY (`id_album`) REFERENCES `album` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `musica`
--

LOCK TABLES `musica` WRITE;
/*!40000 ALTER TABLE `musica` DISABLE KEYS */;
INSERT INTO `musica` VALUES (1,'Clocks',307,'2002-08-26','https://upload.wikimedia.org/wikipedia/pt/9/96/Clocks_single.jpg',1,1),(2,'Ameriican Requiem',326,'2024-05-24','https://upload.wikimedia.org/wikipedia/en/a/aa/Beyonc%C3%A9_-_Cowboy_Carter.png',2,2),(3,'Blackbiird',131,'2024-05-24','https://upload.wikimedia.org/wikipedia/en/a/aa/Beyonc%C3%A9_-_Cowboy_Carter.png',2,2),(4,'Blank Space',231,'2014-10-27','https://upload.wikimedia.org/wikipedia/pt/1/10/Capa_de_Blank_Space.png',3,3),(5,'Style',231,'2014-10-27','https://upload.wikimedia.org/wikipedia/pt/4/41/Taylor_Swift_-_Style.png',3,3),(6,'Chemical',199,'2023-04-14','https://upload.wikimedia.org/wikipedia/pt/5/52/Austin_-_Post_Malone.png',4,4),(7,'Mourning',176,'2023-05-19','https://upload.wikimedia.org/wikipedia/pt/5/52/Austin_-_Post_Malone.png',4,4),(8,'Rockstar',218,'2017-09-15','https://cdn-images.dzcdn.net/images/cover/c000a4d39f31f3716bf3f11aa5fab080/500x500-000000-80-0-0.jpg',4,5),(9,'Better Now',231,'2018-05-25','https://cdn-images.dzcdn.net/images/cover/c000a4d39f31f3716bf3f11aa5fab080/500x500-000000-80-0-0.jpg',4,5),(10,'Psycho',221,'2018-02-23','https://cdn-images.dzcdn.net/images/cover/c000a4d39f31f3716bf3f11aa5fab080/500x500-000000-80-0-0.jpg',4,5),(11,'The Emptiness Machine',190,'2024-09-05','https://upload.wikimedia.org/wikipedia/en/6/6d/Linkin_Park_-_%22The_Emptiness_Machine%22_cover.jpg',5,6),(12,'Heavy Is the Crown',167,'2024-09-24','https://upload.wikimedia.org/wikipedia/en/9/90/Linkin_Park_-_From_Zero.png',5,6),(74,'Believer',204,'2017-02-01','https://upload.wikimedia.org/wikipedia/pt/b/b5/ImagineDragonsEvolve.jpg',6,7),(75,'Thunder',187,'2017-04-27','https://upload.wikimedia.org/wikipedia/pt/b/b5/ImagineDragonsEvolve.jpg',6,7),(76,'Whatever It Takes',201,'2017-05-08','https://upload.wikimedia.org/wikipedia/pt/b/b5/ImagineDragonsEvolve.jpg',6,7),(77,'Enemy',174,'2021-10-28','https://upload.wikimedia.org/wikipedia/en/d/d9/Imagine_Dragons_Mercury_album_cover_2022.webp',6,8),(78,'Bones',166,'2022-03-11','https://upload.wikimedia.org/wikipedia/en/d/d9/Imagine_Dragons_Mercury_album_cover_2022.webp',6,8),(79,'Sharks',189,'2022-06-24','https://upload.wikimedia.org/wikipedia/en/d/d9/Imagine_Dragons_Mercury_album_cover_2022.webp',6,8),(80,'Eyes Closed',190,'2024-04-03','https://upload.wikimedia.org/wikipedia/en/4/44/Imagine_Dragons_-_Loom.png',6,9),(81,'Nice to Meet You',197,'2024-05-10','https://upload.wikimedia.org/wikipedia/en/4/44/Imagine_Dragons_-_Loom.png',6,9),(82,'Wake Up',210,'2024-06-28','https://upload.wikimedia.org/wikipedia/en/4/44/Imagine_Dragons_-_Loom.png',6,9);
/*!40000 ALTER TABLE `musica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `data_nasc` date NOT NULL,
  `senha` varchar(255) NOT NULL,
  `genero` enum('M','F','I') DEFAULT NULL,
  `permissao` enum('normal','admin') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (6,'Usuário teste','22623283079','usuario@email.com','1990-01-01','$2y$10$YTMEoWjfflcIoYSLdbKKWuiAPrbcHiqY18EydLj3TvdH3U4waaW2q','M','normal'),(7,'Admin','70199679061','admin@email.com','1990-01-01','$2y$10$XgH.XRdIzAYfMPdMJCVTcehR50cZA/4hfZE5YqG8EMnGmUk2mPGmK','M','admin');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `view_albuns_com_nomes`
--

DROP TABLE IF EXISTS `view_albuns_com_nomes`;
/*!50001 DROP VIEW IF EXISTS `view_albuns_com_nomes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_albuns_com_nomes` AS SELECT 
 1 AS `album_id`,
 1 AS `id`,
 1 AS `nome_album`,
 1 AS `nome_artista`,
 1 AS `data_lancamento`,
 1 AS `capa`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_avaliacoes_album`
--

DROP TABLE IF EXISTS `view_avaliacoes_album`;
/*!50001 DROP VIEW IF EXISTS `view_avaliacoes_album`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_avaliacoes_album` AS SELECT 
 1 AS `id_album`,
 1 AS `id_avaliacao`,
 1 AS `mensagem`,
 1 AS `nota`,
 1 AS `id_avaliador`,
 1 AS `tipo_avaliador`,
 1 AS `nome_avaliador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_avaliacoes_musica`
--

DROP TABLE IF EXISTS `view_avaliacoes_musica`;
/*!50001 DROP VIEW IF EXISTS `view_avaliacoes_musica`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_avaliacoes_musica` AS SELECT 
 1 AS `id_musica`,
 1 AS `id_avaliacao`,
 1 AS `mensagem`,
 1 AS `nota`,
 1 AS `id_avaliador`,
 1 AS `tipo_avaliador`,
 1 AS `nome_avaliador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_musicas_com_nomes`
--

DROP TABLE IF EXISTS `view_musicas_com_nomes`;
/*!50001 DROP VIEW IF EXISTS `view_musicas_com_nomes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_musicas_com_nomes` AS SELECT 
 1 AS `id_musica`,
 1 AS `nome_musica`,
 1 AS `nome_album`,
 1 AS `nome_artista`,
 1 AS `duracao`,
 1 AS `data_lancamento`,
 1 AS `capa`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_albuns_com_nomes`
--

/*!50001 DROP VIEW IF EXISTS `view_albuns_com_nomes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_albuns_com_nomes` AS select `album`.`id` AS `album_id`,`artista`.`id` AS `id`,`album`.`nome` AS `nome_album`,`artista`.`nome` AS `nome_artista`,`album`.`data_lancamento` AS `data_lancamento`,`album`.`capa` AS `capa` from (`album` join `artista` on(`album`.`id_artista` = `artista`.`id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_avaliacoes_album`
--

/*!50001 DROP VIEW IF EXISTS `view_avaliacoes_album`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_avaliacoes_album` AS select `aa`.`id_album` AS `id_album`,`a`.`id` AS `id_avaliacao`,`a`.`mensagem` AS `mensagem`,`a`.`nota` AS `nota`,coalesce(`a`.`id_usuario`,`a`.`id_critico`) AS `id_avaliador`,case when `a`.`id_usuario` is not null then 'usuario' when `a`.`id_critico` is not null then 'critico' else 'desconhecido' end AS `tipo_avaliador`,coalesce(`u`.`nome`,`c`.`nome`) AS `nome_avaliador` from (((`avaliacao_album` `aa` join `avaliacao` `a` on(`aa`.`id_avaliacao` = `a`.`id`)) left join `usuario` `u` on(`a`.`id_usuario` = `u`.`id`)) left join `critico` `c` on(`a`.`id_critico` = `c`.`id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_avaliacoes_musica`
--

/*!50001 DROP VIEW IF EXISTS `view_avaliacoes_musica`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_avaliacoes_musica` AS select `aa`.`id_musica` AS `id_musica`,`a`.`id` AS `id_avaliacao`,`a`.`mensagem` AS `mensagem`,`a`.`nota` AS `nota`,coalesce(`a`.`id_usuario`,`a`.`id_critico`) AS `id_avaliador`,case when `a`.`id_usuario` is not null then 'usuario' when `a`.`id_critico` is not null then 'critico' else 'desconhecido' end AS `tipo_avaliador`,coalesce(`u`.`nome`,`c`.`nome`) AS `nome_avaliador` from (((`avaliacao_musica` `aa` join `avaliacao` `a` on(`aa`.`id_avaliacao` = `a`.`id`)) left join `usuario` `u` on(`a`.`id_usuario` = `u`.`id`)) left join `critico` `c` on(`a`.`id_critico` = `c`.`id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_musicas_com_nomes`
--

/*!50001 DROP VIEW IF EXISTS `view_musicas_com_nomes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_musicas_com_nomes` AS select `musica`.`id` AS `id_musica`,`musica`.`nome` AS `nome_musica`,`album`.`nome` AS `nome_album`,`artista`.`nome` AS `nome_artista`,`musica`.`duracao` AS `duracao`,`musica`.`data_lancamento` AS `data_lancamento`,`musica`.`capa` AS `capa` from ((`musica` join `album` on(`musica`.`id_album` = `album`.`id`)) join `artista` on(`musica`.`id_artista` = `artista`.`id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-27 16:30:29
