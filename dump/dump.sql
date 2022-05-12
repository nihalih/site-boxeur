-- MySQL dump 10.19  Distrib 10.3.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: mysql.info.unicaen.fr    Database: 22008897_dev
-- ------------------------------------------------------
-- Server version	10.5.11-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `22008897_dev`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `22008897_dev` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `22008897_dev`;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `statut` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`id`, `login`, `password`, `name`, `statut`) VALUES (1,'lecarpentier','$2y$10$VMED3Uxi2OydviEPj127UeqheuWaYSac.aa464YS5tbsSep4i.vuu','Jean-Marc Lecarpentier','user'),(2,'admin','$2y$10$VMED3Uxi2OydviEPj127UeqheuWaYSac.aa464YS5tbsSep4i.vuu','Toto Dupont','admin'),(3,'vanier','$2y$10$VMED3Uxi2OydviEPj127UeqheuWaYSac.aa464YS5tbsSep4i.vuu','Pascal Vanier','user');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boxeur`
--

DROP TABLE IF EXISTS `boxeur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boxeur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `poids` int(11) DEFAULT NULL,
  `origine` varchar(255) DEFAULT NULL,
  `champion` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boxeur`
--

LOCK TABLES `boxeur` WRITE;
/*!40000 ALTER TABLE `boxeur` DISABLE KEYS */;
INSERT INTO `boxeur` (`id`, `nom`, `prenom`, `age`, `poids`, `origine`, `champion`,`login`,`img`) VALUES (1,'Vasyl','Lomachenko',33,61,'ukrainien','o',null,'img/vasyl.PNG'),(2,'Mike','Tyson',55,100,'americain','o',null,'img/miketyson.PNG'),(3,'Ryan','Garcia',23,61,'americain','o',null,'img/ryangarcia.PNG');
/*!40000 ALTER TABLE `boxeur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-01  2:33:15

