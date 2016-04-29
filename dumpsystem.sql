-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: workflow2
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
-- Table structure for table `PN_X_FIRM`
--

DROP TABLE IF EXISTS `PN_X_FIRM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PN_X_FIRM` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firm_id` int(11) NOT NULL,
  `pn_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_firm_and_pn` (`firm_id`,`pn_id`),
  UNIQUE KEY `id` (`id`),
  KEY `firm_id` (`firm_id`),
  KEY `pn_id` (`pn_id`),
  CONSTRAINT `PN_X_FIRM_ibfk_1` FOREIGN KEY (`firm_id`) REFERENCES `FIRM` (`firm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `PN_X_FIRM_ibfk_2` FOREIGN KEY (`pn_id`) REFERENCES `petri_net` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PN_X_FIRM`
--

LOCK TABLES `PN_X_FIRM` WRITE;
/*!40000 ALTER TABLE `PN_X_FIRM` DISABLE KEYS */;
INSERT INTO `PN_X_FIRM` VALUES (66,1,21),(69,1,22),(67,2,22),(68,3,23),(70,4,21);
/*!40000 ALTER TABLE `PN_X_FIRM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `REFERENCES`
--

DROP TABLE IF EXISTS `REFERENCES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `REFERENCES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transition_id` int(11) NOT NULL,
  `referenced_transition_id` int(11) NOT NULL,
  `PN_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prechod` (`transition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `REFERENCES`
--

LOCK TABLES `REFERENCES` WRITE;
/*!40000 ALTER TABLE `REFERENCES` DISABLE KEYS */;
INSERT INTO `REFERENCES` VALUES (5,56,54,21),(6,52,56,21);
/*!40000 ALTER TABLE `REFERENCES` ENABLE KEYS */;
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
INSERT INTO `ROLES` VALUES (1,'uctovnik',0),(2,'zakaznik',0),(3,'upratovac',0),(4,' manazer',0);
/*!40000 ALTER TABLE `ROLES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ROLES_START_CASES`
--

DROP TABLE IF EXISTS `ROLES_START_CASES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ROLES_START_CASES` (
  `pn_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  KEY `pn_id` (`pn_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `ROLES_START_CASES_ibfk_1` FOREIGN KEY (`pn_id`) REFERENCES `petri_net` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ROLES_START_CASES_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `ROLES` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ROLES_START_CASES`
--

LOCK TABLES `ROLES_START_CASES` WRITE;
/*!40000 ALTER TABLE `ROLES_START_CASES` DISABLE KEYS */;
/*!40000 ALTER TABLE `ROLES_START_CASES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TRANSITIONS_X_ROLE`
--

DROP TABLE IF EXISTS `TRANSITIONS_X_ROLE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRANSITIONS_X_ROLE` (
  `id_prechod` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_prechod`,`id_role`),
  KEY `id_prechod` (`id_prechod`,`id_role`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `TRANSITIONS_X_ROLE_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `ROLES` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRANSITIONS_X_ROLE`
--

LOCK TABLES `TRANSITIONS_X_ROLE` WRITE;
/*!40000 ALTER TABLE `TRANSITIONS_X_ROLE` DISABLE KEYS */;
INSERT INTO `TRANSITIONS_X_ROLE` VALUES (51,1),(55,1),(52,4);
/*!40000 ALTER TABLE `TRANSITIONS_X_ROLE` ENABLE KEYS */;
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
-- Table structure for table `USERS_X_FIRM`
--

DROP TABLE IF EXISTS `USERS_X_FIRM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS_X_FIRM` (
  `user_id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_user` (`user_id`),
  KEY `id_firmy` (`firm_id`),
  CONSTRAINT `fk_USERS_X_FIRM_1` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERS_X_FIRM_2` FOREIGN KEY (`firm_id`) REFERENCES `FIRM` (`firm_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS_X_FIRM`
--

LOCK TABLES `USERS_X_FIRM` WRITE;
/*!40000 ALTER TABLE `USERS_X_FIRM` DISABLE KEYS */;
INSERT INTO `USERS_X_FIRM` VALUES (2,3,2),(2,4,3),(3,2,4),(3,4,5),(4,3,6),(1,1,7),(1,4,8),(2,1,9);
/*!40000 ALTER TABLE `USERS_X_FIRM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS_X_ROLE`
--

DROP TABLE IF EXISTS `USERS_X_ROLE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS_X_ROLE` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`,`firm_id`),
  KEY `id_user` (`user_id`,`role_id`),
  KEY `id_user_2` (`user_id`),
  KEY `id_rola` (`role_id`),
  KEY `id_rola_2` (`role_id`),
  KEY `fk_USERS_X_ROLE_3_idx` (`firm_id`),
  CONSTRAINT `fk_USERS_X_ROLE_1` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERS_X_ROLE_2` FOREIGN KEY (`firm_id`) REFERENCES `FIRM` (`firm_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_USERS_X_ROLE_3` FOREIGN KEY (`role_id`) REFERENCES `ROLES` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS_X_ROLE`
--

LOCK TABLES `USERS_X_ROLE` WRITE;
/*!40000 ALTER TABLE `USERS_X_ROLE` DISABLE KEYS */;
INSERT INTO `USERS_X_ROLE` VALUES (1,1,1),(1,1,4),(1,2,4),(1,3,4),(1,4,1),(2,1,1),(2,1,2),(2,1,3),(2,3,2),(2,4,3),(3,2,4);
/*!40000 ALTER TABLE `USERS_X_ROLE` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_PT`
--

LOCK TABLES `arc_PT` WRITE;
/*!40000 ALTER TABLE `arc_PT` DISABLE KEYS */;
INSERT INTO `arc_PT` VALUES (63,69,51,1,15),(64,70,52,1,17),(65,71,53,1,19),(66,71,54,1,20),(67,72,55,1,23),(68,73,56,1,24),(69,74,57,1,27),(70,75,57,1,28),(71,77,58,1,15),(72,78,59,1,17),(73,79,60,1,19),(74,79,61,1,20),(75,80,62,1,23),(76,81,63,1,24),(77,82,64,1,27),(78,83,64,1,28),(79,85,65,1,15),(80,86,66,1,17),(81,87,67,1,19),(82,87,68,1,20),(83,88,69,1,23),(84,89,70,1,24),(85,90,71,1,27),(86,91,71,1,28),(87,93,72,1,15),(88,94,73,1,17),(89,95,74,1,19),(90,95,75,1,20),(91,96,76,1,23),(92,97,77,1,24),(93,98,78,1,27),(94,99,78,1,28);
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arc_TP`
--

LOCK TABLES `arc_TP` WRITE;
/*!40000 ALTER TABLE `arc_TP` DISABLE KEYS */;
INSERT INTO `arc_TP` VALUES (63,51,70,1,16),(64,52,71,2,18),(65,53,72,1,21),(66,54,73,1,22),(67,56,75,1,25),(68,55,74,1,26),(69,57,76,1,29),(70,58,78,1,16),(71,59,79,2,18),(72,60,80,1,21),(73,61,81,1,22),(74,63,83,1,25),(75,62,82,1,26),(76,64,84,1,29),(77,65,86,1,16),(78,66,87,2,18),(79,67,88,1,21),(80,68,89,1,22),(81,70,91,1,25),(82,69,90,1,26),(83,71,92,1,29),(84,72,94,1,16),(85,73,95,2,18),(86,74,96,1,21),(87,75,97,1,22),(88,77,99,1,25),(89,76,98,1,26),(90,78,100,1,29);
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
  CONSTRAINT `fk_case_2` FOREIGN KEY (`started_by`) REFERENCES `USERS` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_case_3` FOREIGN KEY (`firm`) REFERENCES `FIRM` (`firm_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case`
--

LOCK TABLES `case` WRITE;
/*!40000 ALTER TABLE `case` DISABLE KEYS */;
INSERT INTO `case` VALUES (25,'Testovaci Case',21,'2016-04-05 19:41:12','2016-04-09 13:00:01',1,1),(26,'Vyzera ze funguje',21,'2016-04-09 13:00:26',NULL,1,1),(27,'case z firmy 4',21,'2016-04-09 14:50:17',NULL,1,4),(28,'caseíík',21,'2016-04-09 15:11:00',NULL,1,1),(29,'case anonym',21,'2016-04-09 20:11:56',NULL,2,1),(30,'referencie',21,'2016-04-11 19:05:25',NULL,1,1),(31,'refs',21,'2016-04-11 19:09:10','2016-04-16 17:50:12',1,1),(32,'refsdelete',21,'2016-04-11 19:20:13',NULL,2,1),(33,'Zasetest',21,'2016-04-11 19:41:14',NULL,2,1),(34,'zla referencia',21,'2016-04-11 19:49:22',NULL,1,1),(35,'casicek',21,'2016-04-11 22:41:06',NULL,1,1),(36,'Aha Palo vytvorila som Case',21,'2016-04-12 12:52:40',NULL,1,1),(37,'case z firmy 4',21,'2016-04-12 13:15:55',NULL,1,4),(38,'casicek4',21,'2016-04-12 13:16:49',NULL,1,4),(39,'Testujeme Apku',21,'2016-04-12 16:05:21','2016-04-16 17:51:48',1,1),(40,'bla bla',21,'2016-04-12 16:05:21',NULL,3,3),(41,'funguje?',21,'2016-04-17 18:07:25',NULL,1,1),(42,'2prechody',21,'2016-04-18 17:49:24',NULL,1,1),(43,'vsetko OK',21,'2016-04-18 21:26:35',NULL,1,1),(44,'Opat vsetko OK :)',21,'2016-04-18 21:27:25',NULL,1,1),(45,'zmena',21,'2016-04-19 08:59:18',NULL,1,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_marking`
--

LOCK TABLES `case_marking` WRITE;
/*!40000 ALTER TABLE `case_marking` DISABLE KEYS */;
INSERT INTO `case_marking` VALUES (134,25,69,0),(135,25,70,0),(136,25,71,0),(137,25,72,0),(138,25,73,0),(139,25,74,0),(140,25,75,0),(141,25,76,1),(142,26,69,0),(143,26,70,0),(144,26,71,1),(145,26,72,0),(146,26,73,0),(147,26,74,0),(148,26,75,0),(149,26,76,0),(150,27,69,1),(151,27,70,0),(152,27,71,0),(153,27,72,0),(154,27,73,0),(155,27,74,0),(156,27,75,0),(157,27,76,0),(158,28,69,0),(159,28,70,0),(160,28,71,0),(161,28,72,0),(162,28,73,0),(163,28,74,1),(164,28,75,0),(165,28,76,0),(166,29,69,0),(167,29,70,0),(168,29,71,0),(169,29,72,0),(170,29,73,0),(171,29,74,0),(172,29,75,0),(173,29,76,0),(174,30,69,0),(175,30,70,0),(176,30,71,0),(177,30,72,0),(178,30,73,0),(179,30,74,0),(180,30,75,0),(181,30,76,0),(182,31,69,0),(183,31,70,0),(184,31,71,0),(185,31,72,0),(186,31,73,0),(187,31,74,0),(188,31,75,0),(189,31,76,1),(190,32,69,0),(191,32,70,0),(192,32,71,0),(193,32,72,0),(194,32,73,0),(195,32,74,0),(196,32,75,0),(197,32,76,0),(198,33,69,1),(199,33,70,0),(200,33,71,0),(201,33,72,0),(202,33,73,0),(203,33,74,0),(204,33,75,0),(205,33,76,0),(206,34,69,1),(207,34,70,0),(208,34,71,0),(209,34,72,0),(210,34,73,0),(211,34,74,0),(212,34,75,0),(213,34,76,0),(214,35,69,0),(215,35,70,1),(216,35,71,0),(217,35,72,0),(218,35,73,0),(219,35,74,0),(220,35,75,0),(221,35,76,0),(222,36,69,0),(223,36,70,0),(224,36,71,2),(225,36,72,0),(226,36,73,0),(227,36,74,0),(228,36,75,0),(229,36,76,0),(230,37,69,1),(231,37,70,0),(232,37,71,0),(233,37,72,0),(234,37,73,0),(235,37,74,0),(236,37,75,0),(237,37,76,0),(238,38,69,1),(239,38,70,0),(240,38,71,0),(241,38,72,0),(242,38,73,0),(243,38,74,0),(244,38,75,0),(245,38,76,0),(246,39,69,0),(247,39,70,0),(248,39,71,0),(249,39,72,0),(250,39,73,0),(251,39,74,0),(252,39,75,0),(253,39,76,1),(254,41,69,1),(255,41,70,0),(256,41,71,0),(257,41,72,0),(258,41,73,0),(259,41,74,0),(260,41,75,0),(261,41,76,0),(262,42,69,0),(263,42,70,0),(264,42,71,0),(265,42,72,0),(266,42,73,0),(267,42,74,0),(268,42,75,0),(269,42,76,0),(270,43,69,1),(271,43,70,0),(272,43,71,0),(273,43,72,0),(274,43,73,0),(275,43,74,0),(276,43,75,0),(277,43,76,0),(278,44,69,1),(279,44,70,0),(280,44,71,0),(281,44,72,0),(282,44,73,0),(283,44,74,0),(284,44,75,0),(285,44,76,0),(286,45,69,1),(287,45,70,0),(288,45,71,0),(289,45,72,0),(290,45,73,0),(291,45,74,0),(292,45,75,0),(293,45,76,0);
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
  `consumed_tokens` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_case` (`id_case`),
  KEY `id_transition` (`id_transition`),
  KEY `started_by` (`started_by`),
  CONSTRAINT `case_progress_ibfk_1` FOREIGN KEY (`id_case`) REFERENCES `case` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_progress_ibfk_2` FOREIGN KEY (`id_transition`) REFERENCES `transition` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_progress_ibfk_3` FOREIGN KEY (`started_by`) REFERENCES `USERS` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_progress`
--

LOCK TABLES `case_progress` WRITE;
/*!40000 ALTER TABLE `case_progress` DISABLE KEYS */;
INSERT INTO `case_progress` VALUES (2,25,51,1,'2016-04-09 12:57:01','2016-04-09 12:57:22',NULL),(3,25,52,1,'2016-04-09 12:58:04','2016-04-09 12:58:15',NULL),(4,25,53,1,'2016-04-09 12:58:27','2016-04-09 12:58:40',NULL),(5,25,54,1,'2016-04-09 12:58:31','2016-04-09 12:58:46',NULL),(6,25,56,1,'2016-04-09 12:59:15','2016-04-09 12:59:35',NULL),(7,25,55,1,'2016-04-09 12:59:30','2016-04-09 12:59:40',NULL),(9,25,57,1,'2016-04-09 12:59:58','2016-04-09 13:00:01',NULL),(10,28,51,1,'2016-04-09 15:11:03','2016-04-09 20:03:45',NULL),(11,28,52,1,'2016-04-09 20:03:53','2016-04-09 20:04:08',NULL),(12,28,53,1,'2016-04-09 20:04:13','2016-04-09 20:04:23',NULL),(13,28,54,1,'2016-04-09 20:04:16','2016-04-09 20:04:22',NULL),(14,26,51,2,'2016-04-09 20:16:16','2016-04-09 20:16:42',NULL),(15,30,51,2,'2016-04-11 19:05:36','2016-04-11 19:05:44',NULL),(16,30,52,1,'2016-04-11 19:05:53','2016-04-11 19:06:00',NULL),(17,30,53,1,'2016-04-11 19:06:43','2016-04-11 19:06:58',NULL),(18,30,53,2,'2016-04-11 19:06:45','2016-04-11 19:07:00',NULL),(19,28,55,1,'2016-04-11 19:07:12','2016-04-11 19:07:49',NULL),(20,31,51,1,'2016-04-11 19:09:18','2016-04-11 19:09:21',NULL),(21,31,52,1,'2016-04-11 19:09:23','2016-04-11 19:09:28',NULL),(22,31,53,1,'2016-04-11 19:09:37','2016-04-11 19:09:45',NULL),(23,31,54,2,'2016-04-11 19:09:40','2016-04-11 19:09:51',NULL),(24,31,56,2,'2016-04-11 19:10:18','2016-04-11 19:10:26',NULL),(25,31,55,2,'2016-04-11 19:10:24','2016-04-11 19:10:27',NULL),(26,32,51,2,'2016-04-11 19:20:33','2016-04-11 19:20:52',NULL),(27,32,52,2,'2016-04-11 19:39:36',NULL,NULL),(28,26,52,1,'2016-04-11 19:57:24','2016-04-19 12:13:05',NULL),(29,31,57,1,'2016-04-11 21:14:49','2016-04-16 17:50:12',NULL),(30,30,55,1,'2016-04-11 21:14:50',NULL,NULL),(31,30,55,1,'2016-04-11 21:14:53',NULL,NULL),(33,35,51,1,'2016-04-11 22:41:30','2016-04-11 22:41:37',NULL),(35,36,51,1,'2016-04-12 12:52:53','2016-04-12 12:53:02',NULL),(37,39,51,1,'2016-04-12 16:06:27','2016-04-12 16:07:55',NULL),(38,36,52,1,'2016-04-12 21:24:14','2016-04-12 21:24:22',NULL),(39,39,52,1,'2016-04-16 17:49:58','2016-04-16 17:50:08',NULL),(40,39,53,1,'2016-04-16 17:50:27','2016-04-16 17:50:45',NULL),(41,39,54,1,'2016-04-16 17:50:34','2016-04-16 17:50:48',NULL),(42,39,56,1,'2016-04-16 17:51:03','2016-04-16 17:51:18',NULL),(43,39,55,1,'2016-04-16 17:51:09','2016-04-16 17:51:21',NULL),(44,39,57,1,'2016-04-16 17:51:38','2016-04-16 17:51:48',NULL),(45,28,56,1,'2016-04-17 14:35:27',NULL,NULL),(46,42,51,1,'2016-04-18 17:49:40','2016-04-18 17:49:48',NULL),(47,42,52,1,'2016-04-18 17:50:07','2016-04-18 17:50:16',NULL),(48,42,53,1,'2016-04-18 17:50:23',NULL,NULL),(49,42,54,1,'2016-04-18 17:50:29',NULL,NULL),(50,29,51,2,'2016-04-18 21:00:14',NULL,NULL),(51,26,54,1,'2016-04-19 13:57:18',NULL,NULL);
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
  `xml_file` blob NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `svg_file` blob NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petri_net`
--

LOCK TABLES `petri_net` WRITE;
/*!40000 ALTER TABLE `petri_net` DISABLE KEYS */;
INSERT INTO `petri_net` VALUES (21,'sietka','<document>\n                    <place>\n                    <id>0</id>\n                    <x>60</x>\n                    <y>250</y>\n                    <label>m1</label>\n                    <tokens>1</tokens>\n                    </place>\n                    <place>\n                    <id>1</id>\n                    <x>310</x>\n                    <y>255</y>\n                    <label>m2</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>2</id>\n                    <x>502</x>\n                    <y>263</y>\n                    <label>m3</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>3</id>\n                    <x>710</x>\n                    <y>139</y>\n                    <label>m4</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>4</id>\n                    <x>708</x>\n                    <y>355</y>\n                    <label>m5</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>5</id>\n                    <x>907</x>\n                    <y>143</y>\n                    <label>m6</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>6</id>\n                    <x>903</x>\n                    <y>345</y>\n                    <label>m7</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <place>\n                    <id>7</id>\n                    <x>1044</x>\n                    <y>245</y>\n                    <label>m8</label>\n                    <tokens>0</tokens>\n                    </place>\n                    <transition>\n                    <id>8</id>\n                    <x>207</x>\n                    <y>258</y>\n                    <label>p1</label>\n                    </transition>\n                    <transition>\n                    <id>9</id>\n                    <x>420</x>\n                    <y>259</y>\n                    <label>p2</label>\n                    </transition>\n                    <transition>\n                    <id>10</id>\n                    <x>591</x>\n                    <y>145</y>\n                    <label>p3</label>\n                    </transition>\n                    <transition>\n                    <id>11</id>\n                    <x>589</x>\n                    <y>353</y>\n                    <label>p4</label>\n                    </transition>\n                    <transition>\n                    <id>12</id>\n                    <x>803</x>\n                    <y>142</y>\n                    <label>p5</label>\n                    </transition>\n                    <transition>\n                    <id>13</id>\n                    <x>812</x>\n                    <y>356</y>\n                    <label>p6</label>\n                    </transition>\n                    <transition>\n                    <id>14</id>\n                    <x>975</x>\n                    <y>241</y>\n                    <label>p7</label>\n                    </transition>\n                    <arc>\n                    <id>15</id>\n                    <type>regular</type>\n                    <sourceId>0</sourceId>\n                    <destinationId>8</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>16</id>\n                    <type>regular</type>\n                    <sourceId>8</sourceId>\n                    <destinationId>1</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>17</id>\n                    <type>regular</type>\n                    <sourceId>1</sourceId>\n                    <destinationId>9</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>18</id>\n                    <type>regular</type>\n                    <sourceId>9</sourceId>\n                    <destinationId>2</destinationId>\n                    <multiplicity>2</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>19</id>\n                    <type>regular</type>\n                    <sourceId>2</sourceId>\n                    <destinationId>10</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>20</id>\n                    <type>regular</type>\n                    <sourceId>2</sourceId>\n                    <destinationId>11</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>21</id>\n                    <type>regular</type>\n                    <sourceId>10</sourceId>\n                    <destinationId>3</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>22</id>\n                    <type>regular</type>\n                    <sourceId>11</sourceId>\n                    <destinationId>4</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>23</id>\n                    <type>regular</type>\n                    <sourceId>3</sourceId>\n                    <destinationId>12</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>24</id>\n                    <type>regular</type>\n                    <sourceId>4</sourceId>\n                    <destinationId>13</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>25</id>\n                    <type>regular</type>\n                    <sourceId>13</sourceId>\n                    <destinationId>6</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>26</id>\n                    <type>regular</type>\n                    <sourceId>12</sourceId>\n                    <destinationId>5</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>27</id>\n                    <type>regular</type>\n                    <sourceId>5</sourceId>\n                    <destinationId>14</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>28</id>\n                    <type>regular</type>\n                    <sourceId>6</sourceId>\n                    <destinationId>14</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <arc>\n                    <id>29</id>\n                    <type>regular</type>\n                    <sourceId>14</sourceId>\n                    <destinationId>7</destinationId>\n                    <multiplicity>1</multiplicity>\n                    </arc>\n                    <description/>\n                    </document>',1,'2016-04-05 18:03:15','\n<?xml version=\"1.0\" standalone=\"no\"?>\n<svg width=\"1076.609375\" height=\"398\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns=\"http://www.w3.org/2000/svg\" id=\"netDrawArea\" style=\"width: 99%; height: 99%;\"><circle id=\"21\" cx=\"1044\" cy=\"245\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"1035\" cy=\"236\" r=\"4\" fill=\"white\"/><circle cx=\"1044\" cy=\"236\" r=\"4\" fill=\"white\"/><circle cx=\"1053\" cy=\"236\" r=\"4\" fill=\"white\"/><circle cx=\"1035\" cy=\"245\" r=\"4\" fill=\"white\"/><circle cx=\"1044\" cy=\"245\" r=\"4\" fill=\"white\"/><circle cx=\"1053\" cy=\"245\" r=\"4\" fill=\"white\"/><circle cx=\"1035\" cy=\"254\" r=\"4\" fill=\"white\"/><circle cx=\"1044\" cy=\"254\" r=\"4\" fill=\"white\"/><circle cx=\"1053\" cy=\"254\" r=\"4\" fill=\"white\"/><text x=\"1044\" y=\"245\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"79.97044833773937,251.0868271204212  177.01477583113032,256.3681510656398\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"79.97044833773937,251.0868271204212  177.01477583113032,256.3681510656398\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"187,256.91156462585036 176.74306905102503,261.3607631500746 177.2864826112356,251.37553898120493\" stroke=\"black\" fill=\"black\"/><rect x=\"132.48522416886968\" y=\"252.99919587313576\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"133.48522416886968\" y=\"249.99919587313576\"></text><circle id=\"14\" cx=\"60\" cy=\"250\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"51\" cy=\"241\" r=\"4\" fill=\"white\"/><circle cx=\"60\" cy=\"241\" r=\"4\" fill=\"white\"/><circle cx=\"69\" cy=\"241\" r=\"4\" fill=\"white\"/><circle cx=\"51\" cy=\"250\" r=\"4\" fill=\"white\"/><circle cx=\"60\" cy=\"250\" r=\"4\" fill=\"black\"/><circle cx=\"69\" cy=\"250\" r=\"4\" fill=\"white\"/><circle cx=\"51\" cy=\"259\" r=\"4\" fill=\"white\"/><circle cx=\"60\" cy=\"259\" r=\"4\" fill=\"white\"/><circle cx=\"69\" cy=\"259\" r=\"4\" fill=\"white\"/><text x=\"60\" y=\"250\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" fill=\"black\"></text><polyline points=\"227,257.41747572815535  280.01271695415454,255.87341601104404\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"227,257.41747572815535  280.01271695415454,255.87341601104404\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"290.00847796943634,255.58227734069604 280.15828628932854,260.87129651868497 279.86714761898054,250.87553550340314\" stroke=\"black\" fill=\"black\"/><rect x=\"257.5042389847182\" y=\"255.4998765344257\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"258.5042389847182\" y=\"252.4998765344257\"></text><rect id=\"0\" x=\"187\" y=\"238\" width=\"40\" height=\"40\" fill=\"#80ffaa\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"329.9867899589286,255.72679236214285  390.0066050205357,257.9093310916558\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"329.9867899589286,255.72679236214285  390.0066050205357,257.9093310916558\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"400,258.27272727272725 389.82490693,262.906028581388 390.1883031110714,252.91263360192366\" stroke=\"black\" fill=\"black\"/><rect x=\"363.9933949794643\" y=\"255.99975981743506\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"364.9933949794643\" y=\"252.99975981743506\"></text><circle id=\"15\" cx=\"310\" cy=\"255\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"301\" cy=\"246\" r=\"4\" fill=\"white\"/><circle cx=\"310\" cy=\"246\" r=\"4\" fill=\"white\"/><circle cx=\"319\" cy=\"246\" r=\"4\" fill=\"white\"/><circle cx=\"301\" cy=\"255\" r=\"4\" fill=\"white\"/><circle cx=\"310\" cy=\"255\" r=\"4\" fill=\"white\"/><circle cx=\"319\" cy=\"255\" r=\"4\" fill=\"white\"/><circle cx=\"301\" cy=\"264\" r=\"4\" fill=\"white\"/><circle cx=\"310\" cy=\"264\" r=\"4\" fill=\"white\"/><circle cx=\"319\" cy=\"264\" r=\"4\" fill=\"white\"/><text x=\"310\" y=\"255\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"440,259.9756097560976  472.0356294662531,261.53832338859775\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"440,259.9756097560976  472.0356294662531,261.53832338859775\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"482.02375297750206,262.0255489257318 471.79201669768605,266.53238514422225 472.2792422348202,256.54426163297325\" stroke=\"black\" fill=\"black\"/><rect x=\"455.746251488751\" y=\"245.00057934091467\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"10.53125\" height=\"15\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"456.746251488751\" y=\"257.00057934091467\">2</text><rect id=\"8\" x=\"400\" y=\"239\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"514.043257654492,247.03253479516798  569.8936254100422,172.983732602416\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"514.043257654492,247.03253479516798  569.8936254100422,172.983732602416\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"575.9152542372882,165 573.8854917112502,175.994547016039 565.9017591088342,169.97291818879302\" stroke=\"black\" fill=\"black\"/><rect x=\"543.9792559458901\" y=\"205.016267397584\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"544.9792559458901\" y=\"202.016267397584\"></text><polyline points=\"515.9004419369495,277.3797675209822  562.7164456981919,325.8101162395089\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"515.9004419369495,277.3797675209822  562.7164456981919,325.8101162395089\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"569.6666666666666,333 559.1215038179464,329.28522672374623 566.3113875784375,322.33500575527154\" stroke=\"black\" fill=\"black\"/><rect x=\"541.7835543018081\" y=\"304.1898837604911\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"542.7835543018081\" y=\"301.1898837604911\"></text><circle id=\"16\" cx=\"502\" cy=\"263\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"493\" cy=\"254\" r=\"4\" fill=\"white\"/><circle cx=\"502\" cy=\"254\" r=\"4\" fill=\"white\"/><circle cx=\"511\" cy=\"254\" r=\"4\" fill=\"white\"/><circle cx=\"493\" cy=\"263\" r=\"4\" fill=\"white\"/><circle cx=\"502\" cy=\"263\" r=\"4\" fill=\"white\"/><circle cx=\"511\" cy=\"263\" r=\"4\" fill=\"white\"/><circle cx=\"493\" cy=\"272\" r=\"4\" fill=\"white\"/><circle cx=\"502\" cy=\"272\" r=\"4\" fill=\"white\"/><circle cx=\"511\" cy=\"272\" r=\"4\" fill=\"white\"/><text x=\"502\" y=\"263\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"611,143.99159663865547  680.0380603480002,140.51068603287393\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"611,143.99159663865547  680.0380603480002,140.51068603287393\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"690.0253735653334,140.00712402191596 680.2898413534791,145.50434264154057 679.7862793425212,135.5170294242073\" stroke=\"black\" fill=\"black\"/><rect x=\"649.5126867826667\" y=\"140.9993603302857\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"650.5126867826667\" y=\"137.9993603302857\"></text><rect id=\"9\" x=\"571\" y=\"125\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"609,353.33613445378154  678.0042360915207,354.49586951414324\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"609,353.33613445378154  678.0042360915207,354.49586951414324\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"688.0028240610138,354.6639130094288 677.9202143438779,359.4951634988898 678.0882578391636,349.4965755293967\" stroke=\"black\" fill=\"black\"/><rect x=\"647.5014120305069\" y=\"353.0000237316052\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"648.5014120305069\" y=\"350.0000237316052\"></text><rect id=\"10\" x=\"569\" y=\"333\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"729.989602286794,139.64482588021914  773.005198856603,141.03242576956782\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"729.989602286794,139.64482588021914  773.005198856603,141.03242576956782\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"783,141.3548387096774 772.8439923865482,146.02982634126633 773.1664053266578,136.03502519786932\" stroke=\"black\" fill=\"black\"/><rect x=\"755.494801143397\" y=\"139.49983229494828\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"756.494801143397\" y=\"136.49983229494828\"></text><circle id=\"17\" cx=\"710\" cy=\"139\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"701\" cy=\"130\" r=\"4\" fill=\"white\"/><circle cx=\"710\" cy=\"130\" r=\"4\" fill=\"white\"/><circle cx=\"719\" cy=\"130\" r=\"4\" fill=\"white\"/><circle cx=\"701\" cy=\"139\" r=\"4\" fill=\"white\"/><circle cx=\"710\" cy=\"139\" r=\"4\" fill=\"white\"/><circle cx=\"719\" cy=\"139\" r=\"4\" fill=\"white\"/><circle cx=\"701\" cy=\"148\" r=\"4\" fill=\"white\"/><circle cx=\"710\" cy=\"148\" r=\"4\" fill=\"white\"/><circle cx=\"719\" cy=\"148\" r=\"4\" fill=\"white\"/><text x=\"710\" y=\"139\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"727.9990755078924,355.1922988029605  782.0004622460539,355.71154290621206\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"727.9990755078924,355.1922988029605  782.0004622460539,355.71154290621206\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"792,355.8076923076923 781.9523875453137,360.7113117831851 782.048536946794,350.711774029239\" stroke=\"black\" fill=\"black\"/><rect x=\"758.9995377539462\" y=\"354.49999555532645\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"759.9995377539462\" y=\"351.49999555532645\"></text><circle id=\"18\" cx=\"708\" cy=\"355\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"699\" cy=\"346\" r=\"4\" fill=\"white\"/><circle cx=\"708\" cy=\"346\" r=\"4\" fill=\"white\"/><circle cx=\"717\" cy=\"346\" r=\"4\" fill=\"white\"/><circle cx=\"699\" cy=\"355\" r=\"4\" fill=\"white\"/><circle cx=\"708\" cy=\"355\" r=\"4\" fill=\"white\"/><circle cx=\"717\" cy=\"355\" r=\"4\" fill=\"white\"/><circle cx=\"699\" cy=\"364\" r=\"4\" fill=\"white\"/><circle cx=\"708\" cy=\"364\" r=\"4\" fill=\"white\"/><circle cx=\"717\" cy=\"364\" r=\"4\" fill=\"white\"/><text x=\"708\" y=\"355\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"832,353.5824175824176  873.2168033904074,348.60016662313757\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"832,353.5824175824176  873.2168033904074,348.60016662313757\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"883.1445355936049,347.4001110820917 873.8168311609304,353.5640327247363 872.6167756198845,343.63630052153883\" stroke=\"black\" fill=\"black\"/><rect x=\"856.5722677968024\" y=\"349.4912643322547\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"857.5722677968024\" y=\"346.4912643322547\"></text><rect id=\"12\" x=\"792\" y=\"336\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"823,142.1923076923077  877.0013867381615,142.71155179555925\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"823,142.1923076923077  877.0013867381615,142.71155179555925\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"887.0009244921076,142.8077011970395 876.9533120374214,147.71132067253234 877.0494614389016,137.71178291858615\" stroke=\"black\" fill=\"black\"/><rect x=\"854.0004622460538\" y=\"141.5000044446736\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"855.0004622460538\" y=\"138.5000044446736\"></text><rect id=\"11\" x=\"783\" y=\"122\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><polyline points=\"918.4016309641258,159.4317622718285  955.4216334975289,212.78411886408577\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"918.4016309641258,159.4317622718285  955.4216334975289,212.78411886408577\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"961.1224489795918,221 951.3136929295717,215.63452660511723 959.529574065486,209.9337111230543\" stroke=\"black\" fill=\"black\"/><rect x=\"938.7620399718588\" y=\"189.21588113591423\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"939.7620399718588\" y=\"186.21588113591423\"></text><circle id=\"19\" cx=\"907\" cy=\"143\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"898\" cy=\"134\" r=\"4\" fill=\"white\"/><circle cx=\"907\" cy=\"134\" r=\"4\" fill=\"white\"/><circle cx=\"916\" cy=\"134\" r=\"4\" fill=\"white\"/><circle cx=\"898\" cy=\"143\" r=\"4\" fill=\"white\"/><circle cx=\"907\" cy=\"143\" r=\"4\" fill=\"white\"/><circle cx=\"916\" cy=\"143\" r=\"4\" fill=\"white\"/><circle cx=\"898\" cy=\"152\" r=\"4\" fill=\"white\"/><circle cx=\"907\" cy=\"152\" r=\"4\" fill=\"white\"/><circle cx=\"916\" cy=\"152\" r=\"4\" fill=\"white\"/><text x=\"907\" y=\"143\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"914.3841995766062,328.5561561671244  955.4617463655431,269.2219219164378\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"914.3841995766062,328.5561561671244  955.4617463655431,269.2219219164378\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"961.1538461538462,261 959.5727073237621,272.0679718105893 951.3507854073242,266.3758720222863\" stroke=\"black\" fill=\"black\"/><rect x=\"936.7690228652261\" y=\"293.7780780835622\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"937.7690228652261\" y=\"290.7780780835622\"></text><circle id=\"20\" cx=\"903\" cy=\"345\" r=\"20\" fill=\"white\" stroke=\"black\" stroke-width=\"2\" class=\"place\"/><circle cx=\"894\" cy=\"336\" r=\"4\" fill=\"white\"/><circle cx=\"903\" cy=\"336\" r=\"4\" fill=\"white\"/><circle cx=\"912\" cy=\"336\" r=\"4\" fill=\"white\"/><circle cx=\"894\" cy=\"345\" r=\"4\" fill=\"white\"/><circle cx=\"903\" cy=\"345\" r=\"4\" fill=\"white\"/><circle cx=\"912\" cy=\"345\" r=\"4\" fill=\"white\"/><circle cx=\"894\" cy=\"354\" r=\"4\" fill=\"white\"/><circle cx=\"903\" cy=\"354\" r=\"4\" fill=\"white\"/><circle cx=\"912\" cy=\"354\" r=\"4\" fill=\"white\"/><text x=\"903\" y=\"345\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\"></text><polyline points=\"995,242.15942028985506  1014.0502828763249,243.26378451456955\" fill=\"none\" stroke-width=\"4\" stroke=\"white\"/><polyline points=\"995,242.15942028985506  1014.0502828763249,243.26378451456955\" fill=\"none\" stroke-width=\"2\" stroke=\"black\"/><polygon points=\"1024.03352191755,243.84252300971303 1013.7609136287532,248.25540403518204 1014.3396521238966,238.27216499395706\" stroke=\"black\" fill=\"black\"/><rect x=\"1008.516760958775\" y=\"242.00097164978405\" opacity=\"0.6\" stroke-width=\"1\" fill=\"white\" width=\"2\" height=\"0\"/><text font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\" x=\"1009.516760958775\" y=\"239.00097164978405\"></text><rect id=\"13\" x=\"955\" y=\"221\" width=\"40\" height=\"40\" fill=\"#ffb3b3\" class=\"transition\" stroke=\"black\" stroke-width=\"2\"/><rect x=\"197.5390625\" y=\"279\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_0\" y=\"290\" x=\"198.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p1</text><rect x=\"410.5390625\" y=\"280\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_8\" y=\"291\" x=\"411.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p2</text><rect x=\"581.5390625\" y=\"166\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_9\" y=\"177\" x=\"582.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p3</text><rect x=\"579.5390625\" y=\"374\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_10\" y=\"385\" x=\"580.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p4</text><rect x=\"793.5390625\" y=\"163\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_11\" y=\"174\" x=\"794.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p5</text><rect x=\"802.5390625\" y=\"377\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_12\" y=\"388\" x=\"803.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p6</text><rect x=\"965.5390625\" y=\"262\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"18.921875\" height=\"12\"/><text id=\"label_13\" y=\"273\" x=\"966.5390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">p7</text><rect x=\"48.390625\" y=\"271\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"282\" x=\"49.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m1</text><rect x=\"298.390625\" y=\"276\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"287\" x=\"299.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m2</text><rect x=\"490.390625\" y=\"284\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"295\" x=\"491.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m3</text><rect x=\"698.390625\" y=\"160\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"171\" x=\"699.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m4</text><rect x=\"696.390625\" y=\"376\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"387\" x=\"697.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m5</text><rect x=\"895.390625\" y=\"164\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"175\" x=\"896.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m6</text><rect x=\"891.390625\" y=\"366\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"377\" x=\"892.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m7</text><rect x=\"1032.390625\" y=\"266\" stroke-width=\"1\" fill-opacity=\"0.6\" fill=\"white\" width=\"23.21875\" height=\"12\"/><text y=\"277\" x=\"1033.390625\" font-family=\"verdana\" font-weight=\"bold\" font-size=\"12\">m8</text></svg>',NULL),(22,'sietka2','sietka.xml',1,'2016-04-05 18:04:30','',NULL),(23,'sietka3','sietka.xml',1,'2016-04-05 18:04:37','',NULL),(24,'sietka4','sietka.xml',1,'2016-04-05 18:04:51','',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place`
--

LOCK TABLES `place` WRITE;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;
INSERT INTO `place` VALUES (69,1,'m1',21,0),(70,0,'m2',21,1),(71,0,'m3',21,2),(72,0,'m4',21,3),(73,0,'m5',21,4),(74,0,'m6',21,5),(75,0,'m7',21,6),(76,0,'m8',21,7),(77,1,'m1',22,0),(78,0,'m2',22,1),(79,0,'m3',22,2),(80,0,'m4',22,3),(81,0,'m5',22,4),(82,0,'m6',22,5),(83,0,'m7',22,6),(84,0,'m8',22,7),(85,1,'m1',23,0),(86,0,'m2',23,1),(87,0,'m3',23,2),(88,0,'m4',23,3),(89,0,'m5',23,4),(90,0,'m6',23,5),(91,0,'m7',23,6),(92,0,'m8',23,7),(93,1,'m1',24,0),(94,0,'m2',24,1),(95,0,'m3',24,2),(96,0,'m4',24,3),(97,0,'m5',24,4),(98,0,'m6',24,5),(99,0,'m7',24,6),(100,0,'m8',24,7);
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
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transition`
--

LOCK TABLES `transition` WRITE;
/*!40000 ALTER TABLE `transition` DISABLE KEYS */;
INSERT INTO `transition` VALUES (51,'p1',21,8),(52,'p2',21,9),(53,'p3',21,10),(54,'p4',21,11),(55,'p5',21,12),(56,'p6',21,13),(57,'p7',21,14),(58,'p1',22,8),(59,'p2',22,9),(60,'p3',22,10),(61,'p4',22,11),(62,'p5',22,12),(63,'p6',22,13),(64,'p7',22,14),(65,'p1',23,8),(66,'p2',23,9),(67,'p3',23,10),(68,'p4',23,11),(69,'p5',23,12),(70,'p6',23,13),(71,'p7',23,14),(72,'p1',24,8),(73,'p2',24,9),(74,'p3',24,10),(75,'p4',24,11),(76,'p5',24,12),(77,'p6',24,13),(78,'p7',24,14);
/*!40000 ALTER TABLE `transition` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-21 21:21:51
