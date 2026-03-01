/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: pmb
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `soal`
--

DROP TABLE IF EXISTS `soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `soal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pertanyaan` text NOT NULL,
  `opsi_a` varchar(255) NOT NULL,
  `opsi_b` varchar(255) NOT NULL,
  `opsi_c` varchar(255) NOT NULL,
  `opsi_d` varchar(255) NOT NULL,
  `jawaban` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `soal` WRITE;
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
INSERT INTO `soal` VALUES
(7,'Struktur data FIFO dikenal sebagai?','Stack','Queue','Tree','Graph','B'),
(10,'HTTP merupakan singkatan dari?','Hyper Transfer Text Protocol','Hyper Text Transfer Protocol','High Transfer Text Protocol','Hyperlink Text Transfer Process','B'),
(15,'Bahasa query untuk mengelola database MySQL adalah?','HTML','SQL','CSS','JavaScript','B'),
(16,'Git digunakan untuk?','Desain grafis','Version control','Manajemen database','Membuat server','B');
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_tes` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_plain` varchar(100) DEFAULT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'belum_tes',
  `nilai` int(11) DEFAULT NULL,
  `status_lulus` enum('lulus','tidak_lulus') DEFAULT NULL,
  `status_daftar_ulang` enum('belum','selesai','terverifikasi') DEFAULT 'belum',
  `tanggal_daftar_ulang` datetime DEFAULT NULL,
  `nomor_daftar_ulang` varchar(20) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nomor_tes` (`nomor_tes`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(37,'PMB20264949','olivia@gmail.com','Olivia ','jakut','8373702357','$2y$12$rZ/TIMaRJEmISNa6V5rPSOTsUHAqCgxgs.oUkzRQowp4r9MBI1lgq','111','user','2026-02-18 09:03:23','selesai_tes',100,'lulus','terverifikasi','2026-02-18 16:04:27','DU-2026-12163','NIM-2026-4525'),
(40,'PMB20269318','sandika@gmail.com','Sandika ','pkj','92710919741794','$2y$12$T672arH29nmcSdKlXeEbc.FoGMT7l1T3rVJAQaQG4O9.rkG/rMV/a','333','user','2026-02-18 17:45:22','selesai_tes',100,'lulus','terverifikasi','2026-02-19 00:47:00','DU-2026-32632','NIM-2026-5557'),
(41,'PMB20268732','zufar@gmail.com','Zufar','dursaw','98746534232','$2y$12$nxbRlDSl0t.EEoRYhBeeiuxbo5XmJjb3iZt6HJuNjcdX7BtnudPNq','67','user','2026-02-24 01:48:03','selesai_tes',100,'lulus','terverifikasi','2026-02-24 08:49:05','DU-2026-76356','NIM-2026-8841');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-02-24  9:20:29
