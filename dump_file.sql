-- MariaDB dump 10.19  Distrib 10.11.2-MariaDB, for osx10.18 (arm64)
--
-- Host: localhost    Database: drone_project
-- ------------------------------------------------------
-- Server version	10.11.2-MariaDB

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
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `customer_type` enum('privatKunde','Geschäftskunde') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES
(5,'Sepp@gmx.de','$2y$10$mpR7y374xotsuRfpUvdZ..9.DQggyTmFru9U1x.6XN91A/0cPhZa2','privatKunde'),
(11,'bruce@gmx.de','$2y$10$LLf2.KO9MEV0GSnDQilMs..EUiHN6RhRLfC5SFEHgGaMrDBRDy9Zu','privatKunde'),
(13,'helena@t-conline.de','$2y$10$DWRecCv8REFi07Ooell4KeYjqRNAZZ1/qFFrhCsKqtln5UgVMy4v.','Geschäftskunde'),
(14,'Lisa@d.de','$2y$10$m1JBwqOzlVxEw9CPH9hRvOyee9Ji6JG2jDcTI6UOmCMYZyMZ.MeVy','Geschäftskunde');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order`
--

DROP TABLE IF EXISTS `customer_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `category_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `url_key` varchar(255) NOT NULL,
  `package` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `customer_order_ibfk_2` (`category_id`),
  CONSTRAINT `customer_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  CONSTRAINT `customer_order_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `order_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_order`
--

LOCK TABLES `customer_order` WRITE;
/*!40000 ALTER TABLE `customer_order` DISABLE KEYS */;
INSERT INTO `customer_order` VALUES
(75,'Frankfurter Allee 81','großes Haus mit Pool und kleinen anliegendem Bürogebäude. Bitte einmal alles filmen',1,13,'frankfurter-allee-81','Unlimited'),
(76,'Genslerstraße 23','Wir benötigen einen professionellen Drohnenpiloten, der uns bei der Erstellung von atemberaubenden Luftaufnahmen von verschiedenen Sportaktivitäten und Sportstätten unterstützt. Die Drohne soll dabei helfen, die Bewegungen der Sportler aus der Vogelperspektive festzuhalten und die spektakuläre Atmosphäre der Veranstaltungen einzufangen. Wir benötigen jemanden, der über die erforderlichen Fähigkeiten und Kenntnisse im Umgang mit Drohnen verfügt und in der Lage ist, sich schnell an verschiedene Umgebungen und Anforderungen anzupassen.',1,5,'genslerstrasse-23','Unlimited'),
(77,'Seestraße','Haus am See',1,14,'seestrasse','Unlimited');
/*!40000 ALTER TABLE `customer_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_category`
--

DROP TABLE IF EXISTS `order_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_category`
--

LOCK TABLES `order_category` WRITE;
/*!40000 ALTER TABLE `order_category` DISABLE KEYS */;
INSERT INTO `order_category` VALUES
(1,'Wohnimmobilien'),
(2,'Bauindustrie'),
(3,'Fabriken-Museen'),
(4,'Hotels-Gastronomie'),
(5,'Firmengelaende-Firmenevents'),
(6,'Sportaktivitaeten-Sportstaetten');
/*!40000 ALTER TABLE `order_category` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-11 21:32:24
