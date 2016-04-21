-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: workflow
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `FIRM`
--

DROP TABLE IF EXISTS `FIRM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FIRM` (
  `firm_id` int(11) NOT NULL AUTO_INCREMENT,
  `firm_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `join_free` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`firm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FIRM`
--

LOCK TABLES `FIRM` WRITE;
/*!40000 ALTER TABLE `FIRM` DISABLE KEYS */;
INSERT INTO `FIRM` VALUES (1,'firma1',1),(2,'firma2',1),(3,'firma3',0),(4,'firma4',0);
/*!40000 ALTER TABLE `FIRM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ROLES`
--

DROP TABLE IF EXISTS `ROLES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ROLES` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(40) COLLATE utf8_bin NOT NULL,
  `needOfInvitation` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`),
  KEY `role_name_2` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ROLES`
--

LOCK TABLES `ROLES` WRITE;
/*!40000 ALTER TABLE `ROLES` DISABLE KEYS */;
INSERT INTO `ROLES` VALUES (1,'uctovnik',0),(2,'zakaznik',0),(3,'upratovac',0),(4,'  manazer',0);
/*!40000 ALTER TABLE `ROLES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `heslo` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES (1,'Anna','Demeterova','demeterova.anna@gmail.com','heslo'),(2,'Janko','Mrkvicka','jm@mail.com','heslo'),(3,'Ferko','Lomavy','fl@post.sk','heslo'),(4,'Monika','Svejkova','ms@gmail.com','heslo'),(5,'Lacko','Packo','lpp@gmail.com','heslo'),(6,'Zuzka','Mrkvickova','mzmzmz@gmail.com','heslo');
/*!40000 ALTER TABLE `USERS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arc_PT`
--

DROP TABLE IF EXISTS `arc_PT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arc_PT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `id_in_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `from_idx` (`from`),
  KEY `to_idx` (`to`),
  CONSTRAINT `from` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `to` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_PT`
--

LOCK TABLES `arc_PT` WRITE;
/*!40000 ALTER TABLE `arc_PT` DISABLE KEYS */;
INSERT INTO `arc_PT` VALUES (1,1,1,2,15),(2,3,3,1,18),(3,2,2,1,19),(4,5,4,1,22),(5,4,4,1,23),(6,6,5,1,25),(7,7,6,1,28),(8,8,6,1,29),(9,8,7,1,37),(10,11,8,1,39),(11,9,8,1,40),(12,12,9,2,15),(13,14,11,1,18),(14,13,10,1,19),(15,16,12,1,22),(16,15,12,1,23),(17,17,13,1,25),(18,18,14,1,28),(19,19,14,1,29),(20,19,15,1,37),(21,22,16,1,39),(22,20,16,1,40),(23,23,17,2,15),(24,25,19,1,18),(25,24,18,1,19),(26,27,20,1,22),(27,26,20,1,23),(28,28,21,1,25),(29,29,22,1,28),(30,30,22,1,29),(31,30,23,1,37),(32,33,24,1,39),(33,31,24,1,40),(34,34,25,2,15),(35,36,27,1,18),(36,35,26,1,19),(37,38,28,1,22),(38,37,28,1,23),(39,39,29,1,25),(40,40,30,1,28),(41,41,30,1,29),(42,41,31,1,37),(43,44,32,1,39),(44,42,32,1,40),(45,45,33,1,3),(46,47,34,1,3),(47,49,35,1,3),(48,50,36,1,10),(49,51,37,1,11),(50,52,38,1,16),(51,54,39,1,3),(52,55,40,1,10),(53,56,41,1,11),(54,57,42,2,16),(55,59,43,1,3),(56,60,44,1,10),(57,61,45,1,11),(58,62,46,2,16),(59,64,47,1,3),(60,65,48,1,10),(61,66,49,1,11),(62,67,50,2,16);
/*!40000 ALTER TABLE `arc_PT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arc_TP`
--

DROP TABLE IF EXISTS `arc_TP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arc_TP` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `id_in_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `from_idx` (`from`),
  KEY `to_idx` (`to`),
  CONSTRAINT `arc_TP_ibfk_1` FOREIGN KEY (`from`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `arc_TP_ibfk_2` FOREIGN KEY (`to`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_TP`
--

LOCK TABLES `arc_TP` WRITE;
/*!40000 ALTER TABLE `arc_TP` DISABLE KEYS */;
INSERT INTO `arc_TP` VALUES (1,1,2,1,16),(2,1,3,1,17),(3,2,4,1,20),(4,3,5,1,21),(5,4,6,1,24),(6,5,8,2,26),(7,5,7,1,27),(8,6,9,1,30),(9,7,11,1,38),(10,8,10,1,41),(11,9,13,1,16),(12,9,14,1,17),(13,10,15,1,20),(14,11,16,1,21),(15,12,17,1,24),(16,13,19,2,26),(17,13,18,1,27),(18,14,20,1,30),(19,15,22,1,38),(20,16,21,1,41),(21,17,24,1,16),(22,17,25,1,17),(23,18,26,1,20),(24,19,27,1,21),(25,20,28,1,24),(26,21,30,2,26),(27,21,29,1,27),(28,22,31,1,30),(29,23,33,1,38),(30,24,32,1,41),(31,25,35,1,16),(32,25,36,1,17),(33,26,37,1,20),(34,27,38,1,21),(35,28,39,1,24),(36,29,41,2,26),(37,29,40,1,27),(38,30,42,1,30),(39,31,44,1,38),(40,32,43,1,41),(41,33,46,1,4),(42,34,48,1,4),(43,35,50,1,4),(44,35,51,1,7),(45,36,52,1,13),(46,37,52,1,14),(47,38,53,1,18),(48,39,55,1,4),(49,39,56,1,7),(50,40,57,1,13),(51,41,57,1,14),(52,42,58,1,18),(53,43,60,1,4),(54,43,61,1,7),(55,44,62,1,13),(56,45,62,1,14),(57,46,63,1,18),(58,47,65,1,4),(59,47,66,1,7),(60,48,67,1,13),(61,49,67,1,14),(62,50,68,1,18);
/*!40000 ALTER TABLE `arc_TP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arc_inhibitor`
--

DROP TABLE IF EXISTS `arc_inhibitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arc_inhibitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `id_in_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_arc_inhibitor_1_idx` (`from`),
  KEY `fk_arc_inhibitor_2_idx` (`to`),
  CONSTRAINT `fk_arc_inhibitor_1` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_arc_inhibitor_2` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_inhibitor`
--

LOCK TABLES `arc_inhibitor` WRITE;
/*!40000 ALTER TABLE `arc_inhibitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `arc_inhibitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arc_reset`
--

DROP TABLE IF EXISTS `arc_reset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arc_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `id_in_xml` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_arc_reset_1_idx` (`from`),
  KEY `fk_arc_reset_2_idx` (`to`),
  CONSTRAINT `fk_arc_reset_1` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_arc_reset_2` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_reset`
--

LOCK TABLES `arc_reset` WRITE;
/*!40000 ALTER TABLE `arc_reset` DISABLE KEYS */;
/*!40000 ALTER TABLE `arc_reset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case`
--

DROP TABLE IF EXISTS `case`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_pn` int(11) NOT NULL,
  `timestamp_start` datetime NOT NULL,
  `timestamp_stop` datetime DEFAULT NULL,
  `started_by` int(11) NOT NULL,
  `firm` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_UNIQUE` (`id`),
  KEY `fk_case_1_idx` (`id_pn`),
  KEY `fk_case_2_idx` (`started_by`),
  KEY `fk_case_3_idx` (`firm`),
  CONSTRAINT `fk_case_1` FOREIGN KEY (`id_pn`) REFERENCES `petri_net` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_case_2` FOREIGN KEY (`started_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_case_3` FOREIGN KEY (`firm`) REFERENCES `FIRM` (`firm_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case`
--

LOCK TABLES `case` WRITE;
/*!40000 ALTER TABLE `case` DISABLE KEYS */;
INSERT INTO `case` VALUES (1,'AlfaRomeo',11,'2016-01-19 20:29:46',NULL,1,1),(2,'AlfaRomeo',12,'2016-01-19 21:42:32',NULL,1,2),(3,'test',14,'2016-02-29 12:30:27',NULL,1,3),(4,'test2',14,'2016-02-29 12:31:13',NULL,1,4),(5,'bla',14,'2016-02-29 18:30:46',NULL,1,1),(6,'test',14,'2016-02-29 18:32:35',NULL,1,2),(7,'test2',14,'2016-02-29 18:37:49',NULL,1,3),(8,'Testujem',16,'2016-02-29 18:52:23',NULL,1,4),(9,'Ukazka',17,'2016-02-29 19:23:49',NULL,1,1),(10,'ukazka2',18,'2016-02-29 19:27:37',NULL,1,2),(11,'DalsiTest',18,'2016-02-29 19:29:47',NULL,1,3),(12,'Vecerny tst',19,'2016-02-29 19:32:16',NULL,1,4),(13,'zase',19,'2016-02-29 19:36:16',NULL,1,1),(14,'1245',20,'2016-03-01 12:45:50',NULL,1,2),(15,'1247',20,'2016-03-01 12:47:49','2016-03-01 12:49:16',1,3),(16,'testovaci case',20,'2016-03-01 13:13:30',NULL,1,4),(17,'case pre mata',20,'2016-03-01 13:53:12',NULL,1,1),(18,'testnacancel',20,'2016-03-01 15:00:11',NULL,1,2),(19,'zasetest',20,'2016-03-01 15:21:03',NULL,1,3),(20,'regioTest',20,'2016-03-02 17:51:44','2016-03-02 17:53:51',1,4),(21,'yraj',19,'2016-03-28 12:17:51',NULL,1,4);
/*!40000 ALTER TABLE `case` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_marking`
--

DROP TABLE IF EXISTS `case_marking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_marking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_case` int(11) NOT NULL,
  `id_place` int(11) NOT NULL,
  `marking` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_case` (`id_case`),
  KEY `id_place` (`id_place`),
  CONSTRAINT `case_marking_ibfk_1` FOREIGN KEY (`id_case`) REFERENCES `case` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_marking_ibfk_2` FOREIGN KEY (`id_place`) REFERENCES `place` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_marking`
--

LOCK TABLES `case_marking` WRITE;
/*!40000 ALTER TABLE `case_marking` DISABLE KEYS */;
INSERT INTO `case_marking` VALUES (1,1,1,0),(2,1,2,0),(3,1,3,0),(4,1,4,0),(5,1,5,0),(6,1,6,0),(7,1,7,0),(8,1,8,0),(9,1,9,0),(10,1,10,0),(11,1,11,0),(12,2,12,0),(13,2,13,1),(14,2,14,0),(15,2,15,0),(16,2,16,1),(17,2,17,0),(18,2,18,0),(19,2,19,0),(20,2,20,0),(21,2,21,0),(22,2,22,0),(23,4,34,0),(24,4,35,0),(25,4,36,0),(26,4,37,0),(27,4,38,0),(28,4,39,0),(29,4,40,0),(30,4,41,0),(31,4,42,0),(32,4,43,0),(33,4,44,1),(34,5,34,0),(35,5,35,1),(36,5,36,0),(37,5,37,0),(38,5,38,0),(39,5,39,0),(40,5,40,1),(41,5,41,1),(42,5,42,0),(43,5,43,0),(44,5,44,1),(45,6,34,0),(46,6,35,0),(47,6,36,0),(48,6,37,0),(49,6,38,0),(50,6,39,0),(51,6,40,1),(52,6,41,2),(53,6,42,1),(54,6,43,0),(55,6,44,2),(56,7,34,2),(57,7,35,0),(58,7,36,0),(59,7,37,1),(60,7,38,1),(61,7,39,0),(62,7,40,0),(63,7,41,1),(64,7,42,0),(65,7,43,0),(66,7,44,0),(67,8,47,0),(68,8,48,1),(69,9,49,0),(70,9,50,0),(71,9,51,1),(72,9,52,1),(73,9,53,0),(74,10,54,0),(75,10,55,0),(76,10,56,0),(77,10,57,2),(78,10,58,0),(79,11,54,0),(80,11,55,0),(81,11,56,0),(82,11,57,0),(83,11,58,1),(84,12,59,0),(85,12,60,0),(86,12,61,0),(87,12,62,0),(88,12,63,2),(89,13,59,0),(90,13,60,2),(91,13,61,2),(92,13,62,0),(93,13,63,0),(94,14,64,0),(95,14,65,0),(96,14,66,0),(97,14,67,0),(98,14,68,1),(99,15,64,0),(100,15,65,0),(101,15,66,0),(102,15,67,0),(103,15,68,1),(104,16,64,0),(105,16,65,0),(106,16,66,0),(107,16,67,0),(108,16,68,0),(109,17,64,0),(110,17,65,0),(111,17,66,0),(112,17,67,0),(113,17,68,0),(114,18,64,0),(115,18,65,0),(116,18,66,0),(117,18,67,0),(118,18,68,0),(119,19,64,0),(120,19,65,0),(121,19,66,0),(122,19,67,0),(123,19,68,0),(124,20,64,0),(125,20,65,0),(126,20,66,0),(127,20,67,0),(128,20,68,1),(129,21,59,2),(130,21,60,0),(131,21,61,0),(132,21,62,0),(133,21,63,0);
/*!40000 ALTER TABLE `case_marking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_progress`
--

DROP TABLE IF EXISTS `case_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_case` int(11) NOT NULL,
  `id_transition` int(11) NOT NULL,
  `started_by` int(11) NOT NULL,
  `timestamp_start` datetime NOT NULL,
  `timestamp_stop` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_case` (`id_case`),
  KEY `id_transition` (`id_transition`),
  KEY `started_by` (`started_by`),
  CONSTRAINT `case_progress_ibfk_1` FOREIGN KEY (`id_case`) REFERENCES `case` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_progress_ibfk_2` FOREIGN KEY (`id_transition`) REFERENCES `transition` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_progress_ibfk_3` FOREIGN KEY (`started_by`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_progress`
--

LOCK TABLES `case_progress` WRITE;
/*!40000 ALTER TABLE `case_progress` DISABLE KEYS */;
INSERT INTO `case_progress` VALUES (2,4,31,1,'2016-02-29 17:26:48','2016-02-29 18:30:32'),(3,2,11,1,'2016-02-29 18:06:41','2016-02-29 18:32:24'),(4,5,31,1,'2016-02-29 18:31:05','2016-02-29 18:31:16'),(5,6,25,1,'2016-02-29 18:32:44','2016-02-29 18:33:24'),(6,6,26,1,'2016-02-29 18:33:40','2016-02-29 18:37:05'),(7,6,27,1,'2016-02-29 18:33:46','2016-02-29 18:37:26'),(8,6,28,1,'2016-02-29 18:33:52','2016-02-29 18:37:30'),(9,6,31,1,'2016-02-29 18:33:55','2016-02-29 18:37:33'),(10,6,28,1,'2016-02-29 18:38:02','2016-02-29 18:42:56'),(11,6,29,1,'2016-02-29 18:38:06','2016-02-29 18:43:00'),(12,6,29,1,'2016-02-29 18:43:19','2016-02-29 18:43:31'),(13,6,30,1,'2016-02-29 18:43:23','2016-02-29 18:43:36'),(14,6,31,1,'2016-02-29 18:43:27','2016-02-29 18:43:40'),(15,5,28,1,'2016-02-29 18:43:55','2016-02-29 18:44:01'),(16,5,25,1,'2016-02-29 18:44:14','2016-02-29 18:44:26'),(17,5,29,1,'2016-02-29 18:44:20','2016-02-29 18:44:29'),(18,8,34,1,'2016-02-29 18:53:10','2016-02-29 18:53:17'),(19,9,35,1,'2016-02-29 19:24:03','2016-02-29 19:24:17'),(20,9,36,1,'2016-02-29 19:24:33','2016-02-29 19:24:50'),(22,12,43,1,'2016-02-29 19:32:28','2016-02-29 19:33:16'),(23,12,43,1,'2016-02-29 19:32:50','2016-02-29 19:33:22'),(24,12,44,1,'2016-02-29 19:33:28','2016-02-29 19:33:49'),(25,12,44,1,'2016-02-29 19:33:32','2016-02-29 19:34:02'),(26,12,45,1,'2016-02-29 19:33:35','2016-02-29 19:33:59'),(27,12,45,1,'2016-02-29 19:33:37','2016-02-29 19:34:28'),(28,12,46,1,'2016-02-29 19:34:07','2016-02-29 19:34:18'),(29,12,46,1,'2016-02-29 19:34:34','2016-02-29 19:34:43'),(30,13,43,1,'2016-02-29 19:36:24','2016-02-29 19:36:32'),(31,13,43,1,'2016-02-29 19:36:27','2016-02-29 19:36:34'),(32,11,42,1,'2016-03-01 11:33:12','2016-03-01 11:33:15'),(33,14,47,1,'2016-03-01 12:45:56','2016-03-01 12:46:03'),(34,14,48,1,'2016-03-01 12:46:08','2016-03-01 12:46:16'),(35,14,49,1,'2016-03-01 12:46:12','2016-03-01 12:46:25'),(36,14,50,1,'2016-03-01 12:46:30','2016-03-01 12:46:35'),(37,15,47,1,'2016-03-01 12:47:56','2016-03-01 12:47:59'),(38,15,48,1,'2016-03-01 12:48:03','2016-03-01 12:49:06'),(39,15,49,1,'2016-03-01 12:48:07','2016-03-01 12:49:07'),(40,15,50,1,'2016-03-01 12:49:12','2016-03-01 12:49:16'),(41,16,47,1,'2016-03-01 13:13:38','2016-03-01 13:13:45'),(42,16,48,1,'2016-03-01 13:13:52','2016-03-01 13:13:58'),(43,16,49,1,'2016-03-01 13:13:55','2016-03-01 13:14:11'),(44,1,1,1,'2016-03-01 13:18:22',NULL),(47,2,12,1,'2016-03-01 13:58:31',NULL),(48,5,27,1,'2016-03-01 13:58:33',NULL),(49,5,31,1,'2016-03-01 13:58:33',NULL),(55,20,47,1,'2016-03-02 17:52:57','2016-03-02 17:53:02'),(56,20,48,1,'2016-03-02 17:53:09','2016-03-02 17:53:17'),(58,20,49,1,'2016-03-02 17:53:25','2016-03-02 17:53:30'),(60,20,50,1,'2016-03-02 17:53:47','2016-03-02 17:53:51');
/*!40000 ALTER TABLE `case_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `petri_net`
--

DROP TABLE IF EXISTS `petri_net`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `petri_net` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `xml_file` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petri_net`
--

LOCK TABLES `petri_net` WRITE;
/*!40000 ALTER TABLE `petri_net` DISABLE KEYS */;
INSERT INTO `petri_net` VALUES (11,'newNet','newNet.xml',NULL,'0000-00-00 00:00:00'),(12,'newNet','newNet.xml',NULL,'0000-00-00 00:00:00'),(13,'newNet','newNet.xml',NULL,'0000-00-00 00:00:00'),(14,'newNet','newNet.xml',NULL,'0000-00-00 00:00:00'),(15,'newNet','newNet.xml',NULL,'2016-02-29 18:50:16'),(16,'newNet','newNet.xml',1,'2016-02-29 18:51:37'),(17,'newNet','newNet.xml',1,'2016-02-29 19:23:27'),(18,'sietnaukazku','sietnaukazku.xml',1,'2016-02-29 19:27:06'),(19,'sietnaukazku','sietnaukazku.xml',1,'2016-02-29 19:31:52'),(20,'sietnaukazku','sietnaukazku.xml',0,'2016-03-01 11:32:34');
/*!40000 ALTER TABLE `petri_net` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `place`
--

DROP TABLE IF EXISTS `place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initial_marking` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `id_pn` int(11) NOT NULL,
  `id_in_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pn_idx` (`id_pn`),
  CONSTRAINT `id_pn` FOREIGN KEY (`id_pn`) REFERENCES `petri_net` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place`
--

LOCK TABLES `place` WRITE;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;
INSERT INTO `place` VALUES (1,2,'miesto1',11,0),(2,0,'miesto2',11,1),(3,0,'miesto3',11,2),(4,0,'miesto4',11,3),(5,0,'miesto5',11,4),(6,0,'miesto6',11,5),(7,0,'miesto8',11,6),(8,0,'miesto7',11,7),(9,0,'miesto10',11,8),(10,0,'miesto11',11,34),(11,0,'miesto9',11,35),(12,2,'miesto1',12,0),(13,0,'miesto2',12,1),(14,0,'miesto3',12,2),(15,1,'miesto4',12,3),(16,1,'miesto5',12,4),(17,0,'miesto6',12,5),(18,0,'miesto8',12,6),(19,1,'miesto7',12,7),(20,0,'miesto10',12,8),(21,0,'miesto11',12,34),(22,0,'miesto9',12,35),(23,2,'miesto1',13,0),(24,0,'miesto2',13,1),(25,0,'miesto3',13,2),(26,1,'miesto4',13,3),(27,1,'miesto5',13,4),(28,0,'miesto6',13,5),(29,0,'miesto8',13,6),(30,1,'miesto7',13,7),(31,0,'miesto10',13,8),(32,0,'miesto11',13,34),(33,0,'miesto9',13,35),(34,2,'miesto1',14,0),(35,0,'miesto2',14,1),(36,0,'miesto3',14,2),(37,1,'miesto4',14,3),(38,1,'miesto5',14,4),(39,0,'miesto6',14,5),(40,0,'miesto8',14,6),(41,1,'miesto7',14,7),(42,0,'miesto10',14,8),(43,0,'miesto11',14,34),(44,0,'miesto9',14,35),(45,1,NULL,15,0),(46,0,NULL,15,2),(47,1,NULL,16,0),(48,0,NULL,16,2),(49,1,NULL,17,0),(50,0,NULL,17,2),(51,0,NULL,17,5),(52,0,NULL,17,12),(53,0,NULL,17,17),(54,0,NULL,18,0),(55,0,NULL,18,2),(56,0,NULL,18,5),(57,2,NULL,18,12),(58,0,NULL,18,17),(59,2,NULL,19,0),(60,0,NULL,19,2),(61,0,NULL,19,5),(62,0,NULL,19,12),(63,0,NULL,19,17),(64,1,NULL,20,0),(65,0,NULL,20,2),(66,0,NULL,20,5),(67,0,NULL,20,12),(68,0,NULL,20,17);
/*!40000 ALTER TABLE `place` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transition`
--

DROP TABLE IF EXISTS `transition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `id_pn` int(11) NOT NULL,
  `id_in_xml` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `id_pn_idx` (`id_pn`),
  CONSTRAINT `transition_ibfk_1` FOREIGN KEY (`id_pn`) REFERENCES `petri_net` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transition`
--

LOCK TABLES `transition` WRITE;
/*!40000 ALTER TABLE `transition` DISABLE KEYS */;
INSERT INTO `transition` VALUES (1,'prechod1',11,9),(2,'prechod2',11,10),(3,'prechod3',11,11),(4,'prechod4',11,12),(5,'prechod5',11,13),(6,'prechod7',11,14),(7,'prechod6',11,31),(8,'prechod8',11,32),(9,'prechod1',12,9),(10,'prechod2',12,10),(11,'prechod3',12,11),(12,'prechod4',12,12),(13,'prechod5',12,13),(14,'prechod7',12,14),(15,'prechod6',12,31),(16,'prechod8',12,32),(17,'prechod1',13,9),(18,'prechod2',13,10),(19,'prechod3',13,11),(20,'prechod4',13,12),(21,'prechod5',13,13),(22,'prechod7',13,14),(23,'prechod6',13,31),(24,'prechod8',13,32),(25,'prechod1',14,9),(26,'prechod2',14,10),(27,'prechod3',14,11),(28,'prechod4',14,12),(29,'prechod5',14,13),(30,'prechod7',14,14),(31,'prechod6',14,31),(32,'prechod8',14,32),(33,'prechod',15,1),(34,'prechod',16,1),(35,'prechod1',17,1),(36,'prechod2',17,8),(37,'prechod3',17,9),(38,'prechod3',17,15),(39,'prechod1',18,1),(40,'prechod2',18,8),(41,'prechod3',18,9),(42,'prechod4',18,15),(43,'prechod1',19,1),(44,'prechod2',19,8),(45,'prechod3',19,9),(46,'prechod4',19,15),(47,'prechod1',20,1),(48,'prechod2',20,8),(49,'prechod3',20,9),(50,'prechod4',20,15);
/*!40000 ALTER TABLE `transition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'anuska','heslo'),(2,'ferkomrkvicka','heslo'),(3,'jozko','heslo');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-21 21:23:03
