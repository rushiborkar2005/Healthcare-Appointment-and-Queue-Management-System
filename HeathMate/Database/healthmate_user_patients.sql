CREATE DATABASE  IF NOT EXISTS `healthmate` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `healthmate`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: healthmate
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `user_patients`
--

DROP TABLE IF EXISTS `user_patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_patients` (
  `patient_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`patient_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_patients`
--

LOCK TABLES `user_patients` WRITE;
/*!40000 ALTER TABLE `user_patients` DISABLE KEYS */;
INSERT INTO `user_patients` VALUES (17,'rushi','$2y$10$1UmLV6pnSbLLHUUjuYJ2legbfMN8WK9jJAx3kfLDWH/1BiaJZYdgC','rushiborkar2005@gmail.com'),(18,'vinay','$2y$10$cGJsp8MTz5L1qMwm4b.FVO6PAA0Si/tIrHjA6iTKE6/bY4lLsl8pC','vinay@gmail.com'),(19,'prerna','$2y$10$41qhL3KdSP3Vrj.XTnpHrONIva6Dfx5RLaTQXq0StlbWIvvY8.FPW','ppr2005@gmail.com'),(20,'abc','$2y$10$LfjtE9QdwJekNPUx4Dw8FeI/luGVqZvjKDX1AxbnII20eb6dxzqsa','abc@gmail.com'),(21,'mahima','$2y$10$gZLsrT4uWB7d0snY1TsyJe3UoQsf5Y2QSvwZUH6c2nTcmzT7CV3T6','mahi@gmail.com'),(22,'SANS','$2y$10$LToC6YrrRmWCGz2.WZEJSuyPyhuzMhf9gjem3qeZ0knZohtOazs.i','SANS2005@gmail.com'),(24,'bit','$2y$10$Tr59IEmSp4a54T70kz2ErOx.OM/uHh2up8Jx50IAj0/.PA8VsdLI2','bit@gmail.com'),(26,'xyz','$2y$10$AY1NV10R8YDSirnbRJsLEucTSv2oL8nR8LKdmlgKLfAQaP3Bu9pd2','xyz@gmail.com');
/*!40000 ALTER TABLE `user_patients` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-10 16:53:29
