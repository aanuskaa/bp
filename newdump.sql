-- MySQL dump 10.15  Distrib 10.0.24-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: workflow
-- ------------------------------------------------------
-- Server version	10.0.24-MariaDB-7

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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `id_in_xml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `from_idx` (`from`),
  KEY `to_idx` (`to`),
  CONSTRAINT `from` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `to` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `id_in_xml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `from_idx` (`from`),
  KEY `to_idx` (`to`),
  CONSTRAINT `arc_TP_ibfk_1` FOREIGN KEY (`from`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `arc_TP_ibfk_2` FOREIGN KEY (`to`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `id_in_xml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_arc_inhibitor_1_idx` (`from`),
  KEY `fk_arc_inhibitor_2_idx` (`to`),
  CONSTRAINT `fk_arc_inhibitor_1` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_arc_inhibitor_2` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `id_in_xml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_arc_reset_1_idx` (`from`),
  KEY `fk_arc_reset_2_idx` (`to`),
  CONSTRAINT `fk_arc_reset_1` FOREIGN KEY (`from`) REFERENCES `place` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_arc_reset_2` FOREIGN KEY (`to`) REFERENCES `transition` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=318 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'workflow'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-24 11:52:56
