-- MySQL dump 10.13  Distrib 8.4.6, for Win64 (x86_64)
--
-- Host: localhost    Database: estagioctism
-- ------------------------------------------------------
-- Server version	8.4.6

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bancohoras`
--

DROP TABLE IF EXISTS `bancohoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bancohoras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hora_entrada` time DEFAULT NULL,
  `hora_saida` time DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `idprojeto` int DEFAULT NULL,
  `idestagiario` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bancohoras`
--

LOCK TABLES `bancohoras` WRITE;
/*!40000 ALTER TABLE `bancohoras` DISABLE KEYS */;
INSERT INTO `bancohoras` VALUES (1,NULL,NULL,NULL,46,5),(2,'12:57:00','14:57:00','2025-12-12 17:05:09',5,46),(3,'12:57:00','14:57:00','2025-12-12 17:07:49',5,46),(4,'11:57:00','12:58:00','2025-12-13 17:55:49',1,1),(5,'14:41:00','14:44:00','2025-12-16 14:41:10',1,71),(6,'20:19:00','21:18:00','2025-12-21 20:18:31',4,80),(7,'12:11:00','15:12:00','2025-12-28 12:10:46',4,80);
/*!40000 ALTER TABLE `bancohoras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estagiarios`
--

DROP TABLE IF EXISTS `estagiarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estagiarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomecompleto` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `matricula` varchar(250) DEFAULT NULL,
  `supervisor` varchar(250) DEFAULT NULL,
  `MinHoras` varchar(250) DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `idprojeto` int DEFAULT NULL,
  `idorientador` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estagiarios`
--

LOCK TABLES `estagiarios` WRITE;
/*!40000 ALTER TABLE `estagiarios` DISABLE KEYS */;
INSERT INTO `estagiarios` VALUES (1,'fulano','fulano@mail.com','12333','beutrano','300',NULL,1,NULL),(14,'fulano','fulano2@gmail.aa','1234','Super','300',NULL,NULL,NULL),(15,'fulano','fulano2@gmail.aa','1234','Super','300',NULL,NULL,NULL),(16,'Fulano da Silva','fulano@ff.br','123455','Beutrano','300',NULL,NULL,NULL),(17,'Carlos Silva','carlossilva@ss.r','1111155','Fulano','300',NULL,NULL,NULL),(18,'Carlos Silva','carlossilva@ss.r','1111155','Fulano','300',NULL,NULL,NULL),(19,'Carlos Silva','carlossilva@ss.r','1111155','Fulano','300',NULL,NULL,NULL),(20,'Roberto da Silva','roberto44@email.net','1233334455687','Beutrano','360',NULL,NULL,NULL),(21,'Marcos Ferreira','marcosferreira@gmail.com','1234445','Fulano','300',NULL,NULL,NULL),(22,'Olinto araujo','olinto.araujo@ufsm.br','12333','Siclrano','300',NULL,NULL,NULL),(23,'Olinto araujo','olinto@ctism.ufsm.br','122222222','Thiago','300',NULL,NULL,NULL),(24,'Fulano Baccin','fulanobaccin@mail.ce','12333','Super','300',NULL,NULL,NULL),(25,'Thiago','thiago@gmail.com','122222','Fulano','',NULL,NULL,NULL),(26,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(27,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(28,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(29,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(30,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(31,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(32,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(33,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(34,'Thiago','thiago@gmail.com','1233333','Guilherme','300',NULL,NULL,NULL),(35,'fulano','fulano@gmail.com','2222222','thiago','',NULL,NULL,NULL),(36,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(37,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(38,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(39,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(40,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(41,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(42,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(43,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','200',NULL,NULL,NULL),(44,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','',NULL,NULL,NULL),(45,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','',NULL,1,NULL),(46,'Guilherme Kb','guilhermekb@gmail.com','12345','Thiago','',NULL,5,NULL),(47,'Paulo Henrique','ph@gmail.com','1234555','Guilherme','',NULL,1,NULL),(48,'Paulo Henrique2','ph2@gmail.com','12345550001','Guilherme','200',NULL,1,NULL),(49,'Ana','anasofia@gmail.com','00002144938886','Thiago','200',NULL,1,NULL),(50,'Felipe','Felipe@gmail.com','002213554988','Thiago','200',NULL,1,NULL),(51,'Felipe','Felipe@gmail.com','002213554988','Thiago','200',NULL,1,NULL),(52,'Felipe','felipe@gmail.com','1222990456765432','Thiago','200',NULL,1,NULL),(53,'Maria','maria@gmail.com','2220000994','Guilherme','',NULL,1,NULL),(54,'Maria','maria@gmail.com','2220000994','Guilherme','',NULL,1,NULL),(55,'Maria','maria@gmail.com','2220000994','Guilherme','',NULL,1,NULL),(56,'Maria','maria@gmail.com','2220000994','Guilherme','',NULL,1,NULL),(57,'Izadora','izadora@gmail.com','002144','CTISM','200',NULL,1,NULL),(58,'Izadora','izadora@gmail.com','002144','CTISM','200',NULL,1,NULL),(59,'Arthur Bernardo','arturber@gmail.com','12333333000097234','Rodrigo','200',NULL,1,NULL),(60,'Arthur Bernardo','arturber@gmail.com','12333333000097234','Rodrigo','',NULL,1,NULL),(61,'Lisboa ','lisboa@gmail.com','02213398401','Felipe','200',NULL,1,NULL),(62,'Lisboa ','lisboa@gmail.com','02213398401','Felipe','',NULL,1,NULL),(63,'Lucas','lucasborin@gmail.com','123990745','Guilherme','200',NULL,1,NULL),(64,'Lucas','lucasborin@gmail.com','123990745','Guilherme','',NULL,1,NULL),(65,'Lucas','lucasborin@gmail.com','123990745','Guilherme','',NULL,1,NULL),(66,'Lucas','lucasborin@gmail.com','123990745','Guilherme','',NULL,1,NULL),(67,'Lucas','lucasborin@gmail.com','123990745','Guilherme','',NULL,1,NULL),(68,'Bruno','brunobelinaso@gmail.com','0043339998274','Arthur','300',NULL,1,NULL),(69,'Bruno','brunobelinaso@gmail.com','0043339998274','Arthur','',NULL,1,NULL),(70,'Bruno','brunobelinaso@gmail.com','0043339998274','Arthur','',NULL,1,NULL),(71,'Fernando','fernando@gmail.com','0008843228','Guilherme','200',NULL,1,NULL),(72,'Fulano','fulanodasilva@gmail.com','00076699322','Thiago','200',NULL,1,NULL),(73,'Fulano','fulanodasilva@gmail.com','00076699322','Thiago','',NULL,1,NULL),(74,'Fulano','fulanodasilva@gmail.com','00076699322','Thiago','200',NULL,1,NULL),(75,'Fulano','fulanodasilva@gmail.com','00076699322','Thiago','',NULL,1,NULL),(76,'Fulano','fulanodasilva@gmail.com','00076699322','Thiago','200',NULL,1,NULL),(77,'Lucas Cardoso','lucascardoso@gmail.com','0038884','Guilherme','200',NULL,1,NULL),(78,'Arthur Caldeira','artur.cald@gmail.com','000544239','Fulano','200',NULL,4,NULL),(79,'Arthur Caldeira','arthurcald@gmail.com','999332','Fulano','200',NULL,4,NULL),(80,'Arthur','lisboa@arthur.com','11144435','Guilherme','202','guilherme',4,5),(81,'Jordane','jordanealves@gmail.com','111333000','Miguel Brasil','202',NULL,4,6),(82,'Noa','noa.ferreira@gmail.com','9009','Miguel Brasil','207',NULL,5,4),(83,'Noa','noa.ferreira@gmail.com','9009','Miguel Brasil','207',NULL,5,4);
/*!40000 ALTER TABLE `estagiarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estagiariosprojetos`
--

DROP TABLE IF EXISTS `estagiariosprojetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estagiariosprojetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idestagiarios` int DEFAULT NULL,
  `idprojeto` int DEFAULT NULL,
  `orientador` varchar(100) DEFAULT NULL,
  `supervisor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idestagiarios` (`idestagiarios`),
  KEY `idprojetos` (`idprojeto`),
  CONSTRAINT `estagiariosprojetos_ibfk_1` FOREIGN KEY (`idestagiarios`) REFERENCES `estagiarios` (`id`),
  CONSTRAINT `estagiariosprojetos_ibfk_2` FOREIGN KEY (`idprojeto`) REFERENCES `projetos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estagiariosprojetos`
--

LOCK TABLES `estagiariosprojetos` WRITE;
/*!40000 ALTER TABLE `estagiariosprojetos` DISABLE KEYS */;
INSERT INTO `estagiariosprojetos` VALUES (1,45,1,NULL,NULL),(2,1,1,NULL,NULL),(30,76,1,NULL,NULL),(31,77,1,NULL,NULL),(32,78,4,NULL,NULL),(33,79,4,NULL,NULL),(35,80,4,NULL,NULL),(36,81,4,NULL,NULL),(37,82,5,NULL,NULL),(38,83,5,NULL,NULL);
/*!40000 ALTER TABLE `estagiariosprojetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orientadores`
--

DROP TABLE IF EXISTS `orientadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orientadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_orientador` varchar(20) DEFAULT NULL,
  `matricula` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `idprojeto` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orientadores`
--

LOCK TABLES `orientadores` WRITE;
/*!40000 ALTER TABLE `orientadores` DISABLE KEYS */;
INSERT INTO `orientadores` VALUES (1,'Guilherme',NULL,'Guilherme@gmail.com',NULL,NULL),(2,'Guilherme',NULL,'Guilherme@gmail.com',NULL,NULL),(3,'Guilherme',NULL,'Guilherme@gmail.com',NULL,NULL),(4,'Lisboa',NULL,NULL,NULL,NULL),(5,'Tomás','0182459872','tomas@gmail.com',NULL,NULL),(6,'Diogo','999123','diogo@gmail.com',NULL,4),(7,'Diogo','999123','diogo@gmail.com','diogo123',4),(8,'Alberto','000123','alberto@gmail.com',NULL,4),(9,'Thiago Naidon','333440','thiagonaidon2@gmail.com',NULL,4),(10,'Luiz','111','luiz@gmail.com',NULL,4),(11,'Sérgio Pavani','33310','sergiopav@gmail.com',NULL,4),(13,'Fulano da Silva','3334','fulanodasilva@gmail.com',NULL,5),(14,'Felca Silva','1233212','felca.silva@gmail.com',NULL,5),(16,'Professor Thiago','111333002','prof.thiago@gmail.com',NULL,4),(17,'Gustavo Carneiro','000211','gustavocar@gmail.com',NULL,4),(18,'Edisson Silva','999222999','edissonsilva@hotmail.com',NULL,4);
/*!40000 ALTER TABLE `orientadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projetos`
--

DROP TABLE IF EXISTS `projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_projeto` varchar(250) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projetos`
--

LOCK TABLES `projetos` WRITE;
/*!40000 ALTER TABLE `projetos` DISABLE KEYS */;
INSERT INTO `projetos` VALUES (1,'braille','2025-11-11 15:53:50'),(4,'Projeto','2025-11-21 21:05:33'),(5,'Projeto Braille','2025-11-21 21:12:16'),(6,'foguetes',NULL),(7,'Colher para mau de Parkinson','2025-12-23 14:24:18'),(8,'Colher para mau de Parkinson','2025-12-23 14:26:44'),(9,'projeto111','2026-01-03 15:24:19');
/*!40000 ALTER TABLE `projetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'estagiário'),(2,'orientador');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-05 23:24:34
