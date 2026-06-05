-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: simak_terpadu
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `alokasi_data`
--

DROP TABLE IF EXISTS `alokasi_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alokasi_data` (
  `id_alokasi` bigint NOT NULL AUTO_INCREMENT,
  `nama_fragmen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_fragmen` enum('HORIZONTAL','VERTIKAL','CAMPURAN','REPLIKASI') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_site` int NOT NULL,
  `tabel_asal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aturan_alokasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_alokasi`),
  KEY `id_site` (`id_site`),
  CONSTRAINT `alokasi_data_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id_site`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alokasi_data`
--

LOCK TABLES `alokasi_data` WRITE;
/*!40000 ALTER TABLE `alokasi_data` DISABLE KEYS */;
INSERT INTO `alokasi_data` VALUES (1,'frag_h_antrian_umum','HORIZONTAL',1,'antrian','Antrian Poli Umum dialokasikan ke Klinik Pusat.'),(2,'frag_h_antrian_gigi','HORIZONTAL',2,'antrian','Antrian Poli Gigi dialokasikan ke Cabang Gigi.'),(3,'frag_h_antrian_kia','HORIZONTAL',3,'antrian','Antrian Poli KIA dialokasikan ke Cabang KIA.'),(4,'frag_v_pasien_identitas','VERTIKAL',1,'pasien','Kolom identitas pasien disimpan di pusat.'),(5,'frag_v_pasien_kontak','VERTIKAL',2,'pasien','Kolom kontak pasien dialokasikan ke cabang untuk akses cepat.'),(6,'repl_antrian_master_master','REPLIKASI',1,'antrian','Data antrian siap direplikasi antar node master.');
/*!40000 ALTER TABLE `alokasi_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `antrian`
--

DROP TABLE IF EXISTS `antrian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antrian` (
  `id_antrian` bigint NOT NULL AUTO_INCREMENT,
  `no_antrian` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pasien` bigint NOT NULL,
  `id_layanan` int NOT NULL,
  `id_dokter` int DEFAULT NULL,
  `id_site` int NOT NULL,
  `tanggal` date NOT NULL,
  `prioritas` enum('NORMAL','DARURAT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `status` enum('MENUNGGU','DIPANGGIL','SELESAI','BATAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MENUNGGU',
  `waktu_daftar` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `waktu_dipanggil` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci,
  `source_node` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MASTER-1',
  `versi_data` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_antrian`),
  UNIQUE KEY `uq_antrian_harian` (`tanggal`,`id_layanan`,`no_antrian`),
  KEY `idx_antrian_status` (`tanggal`,`status`,`id_layanan`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_layanan` (`id_layanan`),
  KEY `id_dokter` (`id_dokter`),
  KEY `id_site` (`id_site`),
  CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  CONSTRAINT `antrian_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`),
  CONSTRAINT `antrian_ibfk_3` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  CONSTRAINT `antrian_ibfk_4` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id_site`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antrian`
--

LOCK TABLES `antrian` WRITE;
/*!40000 ALTER TABLE `antrian` DISABLE KEYS */;
INSERT INTO `antrian` VALUES (1,'UM-001',1,1,1,1,'2026-06-05','NORMAL','MENUNGGU','2026-06-05 20:58:52',NULL,NULL,'Demam','MASTER-1',1),(2,'GG-001',2,2,2,2,'2026-06-05','NORMAL','DIPANGGIL','2026-06-05 20:38:52','2026-06-05 20:53:52',NULL,'Sakit gigi','MASTER-1',1),(3,'KIA-001',3,3,3,3,'2026-06-05','DARURAT','MENUNGGU','2026-06-05 20:48:52',NULL,NULL,'Kontrol','MASTER-1',1),(4,'LAB-001',4,4,4,1,'2026-06-05','NORMAL','SELESAI','2026-06-05 19:58:52','2026-06-05 20:13:52','2026-06-05 20:33:52','Cek darah','MASTER-1',1);
/*!40000 ALTER TABLE `antrian` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_antrian_before_insert` BEFORE INSERT ON `antrian` FOR EACH ROW BEGIN
    IF NEW.no_antrian IS NULL OR NEW.no_antrian = '' THEN
        SET NEW.no_antrian = CONCAT(
            (SELECT kode_layanan FROM layanan WHERE id_layanan = NEW.id_layanan),
            '-',
            LPAD((SELECT COUNT(*) + 1 FROM antrian WHERE tanggal = NEW.tanggal AND id_layanan = NEW.id_layanan), 3, '0')
        );
    END IF;
    SET NEW.versi_data = 1;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_antrian_after_insert` AFTER INSERT ON `antrian` FOR EACH ROW BEGIN
    UPDATE layanan SET total_antrian = total_antrian + 1 WHERE id_layanan = NEW.id_layanan;
    INSERT INTO audit_antrian (id_antrian, aksi, status_baru, data_baru)
    VALUES (NEW.id_antrian, 'INSERT', NEW.status, JSON_OBJECT('no_antrian', NEW.no_antrian, 'id_pasien', NEW.id_pasien, 'id_layanan', NEW.id_layanan, 'status', NEW.status));
    INSERT INTO log_status_antrian (id_antrian, status_baru, keterangan)
    VALUES (NEW.id_antrian, NEW.status, 'Antrian dibuat otomatis oleh trigger.');
    INSERT INTO repl_event_log (node_asal, tabel_target, operasi, primary_key_value, checksum_data)
    VALUES (NEW.source_node, 'antrian', 'INSERT', NEW.id_antrian, SHA2(CONCAT(NEW.id_antrian, NEW.no_antrian, NEW.status), 256));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_antrian_after_update` AFTER UPDATE ON `antrian` FOR EACH ROW BEGIN
    INSERT INTO audit_antrian (id_antrian, aksi, status_lama, status_baru, data_lama, data_baru)
    VALUES (NEW.id_antrian, 'UPDATE', OLD.status, NEW.status, JSON_OBJECT('status', OLD.status, 'versi_data', OLD.versi_data), JSON_OBJECT('status', NEW.status, 'versi_data', NEW.versi_data));
    IF OLD.status <> NEW.status THEN
        INSERT INTO log_status_antrian (id_antrian, status_lama, status_baru, keterangan)
        VALUES (NEW.id_antrian, OLD.status, NEW.status, 'Perubahan status dicatat otomatis oleh trigger.');
    END IF;
    INSERT INTO repl_event_log (node_asal, tabel_target, operasi, primary_key_value, checksum_data)
    VALUES (NEW.source_node, 'antrian', 'UPDATE', NEW.id_antrian, SHA2(CONCAT(NEW.id_antrian, NEW.no_antrian, NEW.status, NEW.versi_data), 256));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `audit_antrian`
--

DROP TABLE IF EXISTS `audit_antrian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_antrian` (
  `id_audit` bigint NOT NULL AUTO_INCREMENT,
  `id_antrian` bigint DEFAULT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_lama` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_baru` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_lama` json DEFAULT NULL,
  `data_baru` json DEFAULT NULL,
  `user_aksi` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sistem',
  `waktu_aksi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_audit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_antrian`
--

LOCK TABLES `audit_antrian` WRITE;
/*!40000 ALTER TABLE `audit_antrian` DISABLE KEYS */;
INSERT INTO `audit_antrian` VALUES (1,1,'INSERT',NULL,'MENUNGGU',NULL,'{\"status\": \"MENUNGGU\", \"id_pasien\": 1, \"id_layanan\": 1, \"no_antrian\": \"UM-001\"}','sistem','2026-06-05 20:58:52'),(2,2,'INSERT',NULL,'DIPANGGIL',NULL,'{\"status\": \"DIPANGGIL\", \"id_pasien\": 2, \"id_layanan\": 2, \"no_antrian\": \"GG-001\"}','sistem','2026-06-05 20:58:52'),(3,3,'INSERT',NULL,'MENUNGGU',NULL,'{\"status\": \"MENUNGGU\", \"id_pasien\": 3, \"id_layanan\": 3, \"no_antrian\": \"KIA-001\"}','sistem','2026-06-05 20:58:52'),(4,4,'INSERT',NULL,'SELESAI',NULL,'{\"status\": \"SELESAI\", \"id_pasien\": 4, \"id_layanan\": 4, \"no_antrian\": \"LAB-001\"}','sistem','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `audit_antrian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_log`
--

DROP TABLE IF EXISTS `backup_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_log` (
  `id_backup` bigint NOT NULL AUTO_INCREMENT,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode` enum('MANUAL','OTOMATIS','WEB') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('BERHASIL','GAGAL') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_backup`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_log`
--

LOCK TABLES `backup_log` WRITE;
/*!40000 ALTER TABLE `backup_log` DISABLE KEYS */;
INSERT INTO `backup_log` VALUES (1,'simak_terpadu_auto_20260605_205852.sql','storage/backups/otomatis','','BERHASIL','Log backup otomatis dari MySQL Event Scheduler','2026-06-05 20:58:52'),(2,'simak_terpadu_backup_2026-06-05_14-37-25.sql','C:\\laragon\\www\\SIMAK FIX\\actions/../storage/backups\\simak_terpadu_backup_2026-06-05_14-37-25.sql','WEB','BERHASIL','Backup database berhasil dibuat dari halaman admin.','2026-06-05 21:37:26'),(3,'simak_terpadu_backup_2026-06-05_14-41-38.sql','C:\\laragon\\www\\SIMAK FIX\\actions/../storage/backups\\simak_terpadu_backup_2026-06-05_14-41-38.sql','WEB','BERHASIL','Backup database berhasil dibuat dari halaman admin.','2026-06-05 21:41:39'),(4,'simak_terpadu_backup_2026-06-05_14-50-05.sql','C:\\laragon\\www\\SIMAK FIX\\actions/../storage/backups\\simak_terpadu_backup_2026-06-05_14-50-05.sql','WEB','BERHASIL','Backup database berhasil dibuat dari halaman laporan admin.','2026-06-05 21:50:06');
/*!40000 ALTER TABLE `backup_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dokter`
--

DROP TABLE IF EXISTS `dokter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokter` (
  `id_dokter` int NOT NULL AUTO_INCREMENT,
  `kode_dokter` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_dokter` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesialisasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_layanan` int DEFAULT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jadwal` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokter`),
  UNIQUE KEY `kode_dokter` (`kode_dokter`),
  KEY `id_layanan` (`id_layanan`),
  CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dokter`
--

LOCK TABLES `dokter` WRITE;
/*!40000 ALTER TABLE `dokter` DISABLE KEYS */;
INSERT INTO `dokter` VALUES (1,'D-001','dr. Nadira Putri','Dokter Umum',1,'0811111111','nadira@klinik.test','Senin-Jumat 08.00-12.00','aktif','2026-06-05 20:58:52'),(2,'D-002','drg. Rangga Mahesa','Dokter Gigi',2,'0822222222','rangga@klinik.test','Selasa-Kamis 09.00-13.00','aktif','2026-06-05 20:58:52'),(3,'D-003','dr. Safira Aulia','KIA',3,'0833333333','safira@klinik.test','Senin-Rabu 10.00-14.00','aktif','2026-06-05 20:58:52'),(4,'D-004','Analis Raka','Laboratorium',4,'0844444444','raka@klinik.test','Setiap hari 08.00-15.00','aktif','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `dokter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layanan`
--

DROP TABLE IF EXISTS `layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL AUTO_INCREMENT,
  `kode_layanan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_layanan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_site` int NOT NULL,
  `kapasitas_harian` int NOT NULL DEFAULT '50',
  `total_antrian` int NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_layanan`),
  UNIQUE KEY `kode_layanan` (`kode_layanan`),
  KEY `id_site` (`id_site`),
  CONSTRAINT `layanan_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id_site`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layanan`
--

LOCK TABLES `layanan` WRITE;
/*!40000 ALTER TABLE `layanan` DISABLE KEYS */;
INSERT INTO `layanan` VALUES (1,'UM','Poli Umum',1,60,1,1),(2,'GG','Poli Gigi',2,35,1,1),(3,'KIA','Poli KIA',3,40,1,1),(4,'LAB','Laboratorium',1,30,1,1);
/*!40000 ALTER TABLE `layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_aktivitas` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `aksi` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel_terkait` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
INSERT INTO `log_aktivitas` VALUES (1,'INSERT','pasien','Pasien baru: Alya Maharani','2026-06-05 20:58:52'),(2,'INSERT','pasien','Pasien baru: Bima Pratama','2026-06-05 20:58:52'),(3,'INSERT','pasien','Pasien baru: Citra Lestari','2026-06-05 20:58:52'),(4,'INSERT','pasien','Pasien baru: Dimas Saputra','2026-06-05 20:58:52'),(5,'INSERT','rekam_medis','Rekam medis pasien ID 4 ditambahkan','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_status_antrian`
--

DROP TABLE IF EXISTS `log_status_antrian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_status_antrian` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `id_antrian` bigint NOT NULL,
  `status_lama` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_baru` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_antrian` (`id_antrian`),
  CONSTRAINT `log_status_antrian_ibfk_1` FOREIGN KEY (`id_antrian`) REFERENCES `antrian` (`id_antrian`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_status_antrian`
--

LOCK TABLES `log_status_antrian` WRITE;
/*!40000 ALTER TABLE `log_status_antrian` DISABLE KEYS */;
INSERT INTO `log_status_antrian` VALUES (1,1,NULL,'MENUNGGU','Antrian dibuat otomatis oleh trigger.','2026-06-05 20:58:52'),(2,2,NULL,'DIPANGGIL','Antrian dibuat otomatis oleh trigger.','2026-06-05 20:58:52'),(3,3,NULL,'MENUNGGU','Antrian dibuat otomatis oleh trigger.','2026-06-05 20:58:52'),(4,4,NULL,'SELESAI','Antrian dibuat otomatis oleh trigger.','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `log_status_antrian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_stok_obat`
--

DROP TABLE IF EXISTS `log_stok_obat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_stok_obat` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `id_obat` int NOT NULL,
  `jenis_perubahan` enum('masuk','keluar','koreksi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `stok_sebelum` int NOT NULL,
  `stok_sesudah` int NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_obat` (`id_obat`),
  CONSTRAINT `log_stok_obat_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_stok_obat`
--

LOCK TABLES `log_stok_obat` WRITE;
/*!40000 ALTER TABLE `log_stok_obat` DISABLE KEYS */;
INSERT INTO `log_stok_obat` VALUES (1,1,'masuk',100,0,100,'Stok awal obat baru','2026-06-05 20:58:52'),(2,2,'masuk',40,0,40,'Stok awal obat baru','2026-06-05 20:58:52'),(3,3,'masuk',20,0,20,'Stok awal obat baru','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `log_stok_obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obat`
--

DROP TABLE IF EXISTS `obat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obat` (
  `id_obat` int NOT NULL AUTO_INCREMENT,
  `kode_obat` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_obat` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `stok_minimum` int NOT NULL DEFAULT '5',
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_obat`),
  UNIQUE KEY `kode_obat` (`kode_obat`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obat`
--

LOCK TABLES `obat` WRITE;
/*!40000 ALTER TABLE `obat` DISABLE KEYS */;
INSERT INTO `obat` VALUES (1,'OBT-001','Paracetamol 500mg','Tablet','strip',100,10,12000.00,'2026-06-05 20:58:52'),(2,'OBT-002','Amoxicillin 500mg','Kapsul','strip',40,10,18000.00,'2026-06-05 20:58:52'),(3,'OBT-003','Vitamin C','Tablet','botol',20,5,25000.00,'2026-06-05 20:58:52');
/*!40000 ALTER TABLE `obat` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_obat_after_insert` AFTER INSERT ON `obat` FOR EACH ROW BEGIN
    INSERT INTO log_stok_obat (id_obat, jenis_perubahan, jumlah, stok_sebelum, stok_sesudah, keterangan)
    VALUES (NEW.id_obat, 'masuk', NEW.stok, 0, NEW.stok, 'Stok awal obat baru');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `pasien`
--

DROP TABLE IF EXISTS `pasien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien` (
  `id_pasien` bigint NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rekam_medis` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pasien` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `wilayah` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan_darah` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alergi` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pasien`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `no_rekam_medis` (`no_rekam_medis`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien`
--

LOCK TABLES `pasien` WRITE;
/*!40000 ALTER TABLE `pasien` DISABLE KEYS */;
INSERT INTO `pasien` VALUES (1,'1871010101010001','RM-00001','Alya Maharani','P','2004-03-01','081234567890','081234567890','Jl. Mawar No. 1','Tanjung Karang','O','-','2026-06-05 20:58:52'),(2,'1871010101010002','RM-00002','Bima Pratama','L','2002-07-12','081298765432','081298765432','Jl. Melati No. 2','Rajabasa','A','-','2026-06-05 20:58:52'),(3,'1871010101010003','RM-00003','Citra Lestari','P','1998-11-21','082112223333','082112223333','Jl. Kenanga No. 3','Kedaton','B','Debu','2026-06-05 20:58:52'),(4,'1871010101010004','RM-00004','Dimas Saputra','L','1995-05-20','083145678999','083145678999','Jl. Anggrek No. 4','Way Halim','AB','-','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `pasien` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_pasien_before_insert` BEFORE INSERT ON `pasien` FOR EACH ROW BEGIN
    IF NEW.no_rekam_medis IS NULL OR NEW.no_rekam_medis = '' THEN
        SET NEW.no_rekam_medis = CONCAT('RM-', LPAD((SELECT COUNT(*) + 1 FROM pasien), 5, '0'));
    END IF;
    IF NEW.no_hp IS NULL OR NEW.no_hp = '' THEN
        SET NEW.no_hp = NEW.no_telepon;
    END IF;
    IF NEW.no_telepon IS NULL OR NEW.no_telepon = '' THEN
        SET NEW.no_telepon = NEW.no_hp;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_pasien_after_insert` AFTER INSERT ON `pasien` FOR EACH ROW BEGIN
    INSERT INTO log_aktivitas (aksi, tabel_terkait, detail)
    VALUES ('INSERT', 'pasien', CONCAT('Pasien baru: ', NEW.nama_pasien));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `rekam_medis`
--

DROP TABLE IF EXISTS `rekam_medis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rekam_medis` (
  `id_rekam_medis` bigint NOT NULL AUTO_INCREMENT,
  `id_antrian` bigint DEFAULT NULL,
  `id_pasien` bigint NOT NULL,
  `id_dokter` int DEFAULT NULL,
  `tanggal_periksa` date NOT NULL,
  `anamnesis` text COLLATE utf8mb4_unicode_ci,
  `pemeriksaan_fisik` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindakan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resep_obat` text COLLATE utf8mb4_unicode_ci,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tekanan_darah` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suhu` decimal(4,1) DEFAULT NULL,
  `nadi` int DEFAULT NULL,
  `berat_badan` decimal(5,2) DEFAULT NULL,
  `tinggi_badan` decimal(5,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rekam_medis`),
  KEY `id_antrian` (`id_antrian`),
  KEY `id_pasien` (`id_pasien`),
  KEY `id_dokter` (`id_dokter`),
  CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`id_antrian`) REFERENCES `antrian` (`id_antrian`) ON DELETE SET NULL,
  CONSTRAINT `rekam_medis_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  CONSTRAINT `rekam_medis_ibfk_3` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rekam_medis`
--

LOCK TABLES `rekam_medis` WRITE;
/*!40000 ALTER TABLE `rekam_medis` DISABLE KEYS */;
INSERT INTO `rekam_medis` VALUES (1,4,4,4,'2026-06-05','Pasien datang untuk cek darah','Kondisi stabil','Pemeriksaan laboratorium','Pengambilan sampel darah','-','Hasil menyusul','120/80',36.7,80,68.50,170.00,'2026-06-05 20:58:52');
/*!40000 ALTER TABLE `rekam_medis` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_rekam_medis_after_insert` AFTER INSERT ON `rekam_medis` FOR EACH ROW BEGIN
    INSERT INTO log_aktivitas (aksi, tabel_terkait, detail)
    VALUES ('INSERT', 'rekam_medis', CONCAT('Rekam medis pasien ID ', NEW.id_pasien, ' ditambahkan'));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `repl_event_log`
--

DROP TABLE IF EXISTS `repl_event_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `repl_event_log` (
  `id_event` bigint NOT NULL AUTO_INCREMENT,
  `node_asal` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel_target` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operasi` enum('INSERT','UPDATE','DELETE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_key_value` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checksum_data` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_event`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repl_event_log`
--

LOCK TABLES `repl_event_log` WRITE;
/*!40000 ALTER TABLE `repl_event_log` DISABLE KEYS */;
INSERT INTO `repl_event_log` VALUES (1,'MASTER-1','antrian','INSERT','1','e8c70e9900565fda3dee12e86a8c3ea619843ac5f2e7ef7cd6f7fa527d744805','2026-06-05 20:58:52'),(2,'MASTER-1','antrian','INSERT','2','945a808123c33c7a3ea20ffae87e91b6358583ce4ba4a8d5ba2f36328ff8eae0','2026-06-05 20:58:52'),(3,'MASTER-1','antrian','INSERT','3','7a8f5521ab121c8b11894cae86f948cd66d0609dc6d3775ae9cc483c589d30f2','2026-06-05 20:58:52'),(4,'MASTER-1','antrian','INSERT','4','8bc46d36a0b8e396967297490c69767013e8df3cf67142c497b24225b4e99eb2','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `repl_event_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sites` (
  `id_site` int NOT NULL AUTO_INCREMENT,
  `kode_site` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_site` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wilayah` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_alokasi` enum('CENTRALIZED','PARTITIONED','REPLICATED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CENTRALIZED',
  `host_info` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_site`),
  UNIQUE KEY `kode_site` (`kode_site`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` VALUES (1,'PST','Klinik Pusat','Tanjung Karang','CENTRALIZED','127.0.0.1'),(2,'CBG1','Klinik Cabang Gigi','Rajabasa','PARTITIONED','192.168.21.60'),(3,'CBG2','Klinik Cabang KIA','Kedaton','REPLICATED','192.168.21.242');
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tagihan`
--

DROP TABLE IF EXISTS `tagihan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tagihan` (
  `id_tagihan` bigint NOT NULL AUTO_INCREMENT,
  `id_antrian` bigint DEFAULT NULL,
  `id_pasien` bigint NOT NULL,
  `biaya_konsultasi` decimal(12,2) NOT NULL DEFAULT '0.00',
  `biaya_tindakan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `biaya_obat` decimal(12,2) NOT NULL DEFAULT '0.00',
  `diskon` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_tagihan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status_bayar` enum('belum_bayar','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `metode_pembayaran` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_bayar` decimal(12,2) DEFAULT NULL,
  `kembalian` decimal(12,2) DEFAULT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tagihan`),
  KEY `id_antrian` (`id_antrian`),
  KEY `id_pasien` (`id_pasien`),
  CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`id_antrian`) REFERENCES `antrian` (`id_antrian`) ON DELETE SET NULL,
  CONSTRAINT `tagihan_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagihan`
--

LOCK TABLES `tagihan` WRITE;
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
INSERT INTO `tagihan` VALUES (1,4,4,25000.00,50000.00,0.00,0.00,75000.00,'lunas','Tunai',75000.00,0.00,'2026-06-05 20:58:52','Tagihan lab','2026-06-05 20:58:52');
/*!40000 ALTER TABLE `tagihan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','resepsionis','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','$2y$12$kMEJuP8iRKVjRzYY6hQzOOJQoXpJtjnT6wzdT4O9rrUW9bbO73CTi','admin','aktif','2026-06-05 20:58:52'),(2,'Resepsionis Klinik','resepsionis','$2y$12$fO.JsdPwu6H4cGSNTOimwuaw0WFBhcm2i8ZBBcdbP1m3674Iel4Si','resepsionis','aktif','2026-06-05 20:58:52'),(3,'Pasien Demo','pasien','$2y$12$wpa/FfSh.faFiRdD9SUFaOCCkQf/7WSMq0EENcG7.YkeJRVecru3e','user','aktif','2026-06-05 20:58:52'),(4,'adminn','adminnn','$2y$10$pxc//tAub51PpMgg.vC83OUXMAanBKdn8/xJ.Q99MEEPaiDYi4Uii','admin','aktif','2026-06-05 21:05:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `v_antrian_aktif`
--

DROP TABLE IF EXISTS `v_antrian_aktif`;
/*!50001 DROP VIEW IF EXISTS `v_antrian_aktif`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_antrian_aktif` AS SELECT 
 1 AS `id_antrian`,
 1 AS `no_antrian`,
 1 AS `tanggal`,
 1 AS `prioritas`,
 1 AS `status`,
 1 AS `label_status`,
 1 AS `waktu_daftar`,
 1 AS `waktu_dipanggil`,
 1 AS `waktu_selesai`,
 1 AS `id_pasien`,
 1 AS `nik`,
 1 AS `no_rekam_medis`,
 1 AS `nama_pasien`,
 1 AS `no_hp`,
 1 AS `no_telepon`,
 1 AS `umur`,
 1 AS `id_layanan`,
 1 AS `kode_layanan`,
 1 AS `nama_layanan`,
 1 AS `id_dokter`,
 1 AS `nama_dokter`,
 1 AS `spesialisasi`,
 1 AS `id_site`,
 1 AS `nama_site`,
 1 AS `wilayah_site`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_laporan_harian`
--

DROP TABLE IF EXISTS `v_laporan_harian`;
/*!50001 DROP VIEW IF EXISTS `v_laporan_harian`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_laporan_harian` AS SELECT 
 1 AS `tanggal`,
 1 AS `nama_layanan`,
 1 AS `nama_site`,
 1 AS `total`,
 1 AS `menunggu`,
 1 AS `dipanggil`,
 1 AS `selesai`,
 1 AS `batal`,
 1 AS `rata_rata_tunggu`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_rekam_medis_lengkap`
--

DROP TABLE IF EXISTS `v_rekam_medis_lengkap`;
/*!50001 DROP VIEW IF EXISTS `v_rekam_medis_lengkap`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_rekam_medis_lengkap` AS SELECT 
 1 AS `id_rekam_medis`,
 1 AS `id_antrian`,
 1 AS `id_pasien`,
 1 AS `id_dokter`,
 1 AS `tanggal_periksa`,
 1 AS `anamnesis`,
 1 AS `pemeriksaan_fisik`,
 1 AS `diagnosis`,
 1 AS `tindakan`,
 1 AS `resep_obat`,
 1 AS `catatan`,
 1 AS `tekanan_darah`,
 1 AS `suhu`,
 1 AS `nadi`,
 1 AS `berat_badan`,
 1 AS `tinggi_badan`,
 1 AS `created_at`,
 1 AS `nama_pasien`,
 1 AS `no_rekam_medis`,
 1 AS `nama_dokter`,
 1 AS `no_antrian`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_tagihan_lengkap`
--

DROP TABLE IF EXISTS `v_tagihan_lengkap`;
/*!50001 DROP VIEW IF EXISTS `v_tagihan_lengkap`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_tagihan_lengkap` AS SELECT 
 1 AS `id_tagihan`,
 1 AS `id_antrian`,
 1 AS `id_pasien`,
 1 AS `biaya_konsultasi`,
 1 AS `biaya_tindakan`,
 1 AS `biaya_obat`,
 1 AS `diskon`,
 1 AS `total_tagihan`,
 1 AS `status_bayar`,
 1 AS `metode_pembayaran`,
 1 AS `jumlah_bayar`,
 1 AS `kembalian`,
 1 AS `waktu_bayar`,
 1 AS `catatan`,
 1 AS `created_at`,
 1 AS `nama_pasien`,
 1 AS `no_rekam_medis`,
 1 AS `no_antrian`,
 1 AS `nama_dokter`,
 1 AS `nama_layanan`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `v_antrian_aktif`
--

/*!50001 DROP VIEW IF EXISTS `v_antrian_aktif`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_antrian_aktif` AS select `a`.`id_antrian` AS `id_antrian`,`a`.`no_antrian` AS `no_antrian`,`a`.`tanggal` AS `tanggal`,`a`.`prioritas` AS `prioritas`,`a`.`status` AS `status`,`fn_label_status`(`a`.`status`) AS `label_status`,`a`.`waktu_daftar` AS `waktu_daftar`,`a`.`waktu_dipanggil` AS `waktu_dipanggil`,`a`.`waktu_selesai` AS `waktu_selesai`,`p`.`id_pasien` AS `id_pasien`,`p`.`nik` AS `nik`,`p`.`no_rekam_medis` AS `no_rekam_medis`,`p`.`nama_pasien` AS `nama_pasien`,`p`.`no_hp` AS `no_hp`,`p`.`no_telepon` AS `no_telepon`,`fn_hitung_umur`(`p`.`tanggal_lahir`) AS `umur`,`l`.`id_layanan` AS `id_layanan`,`l`.`kode_layanan` AS `kode_layanan`,`l`.`nama_layanan` AS `nama_layanan`,`d`.`id_dokter` AS `id_dokter`,`d`.`nama_dokter` AS `nama_dokter`,`d`.`spesialisasi` AS `spesialisasi`,`s`.`id_site` AS `id_site`,`s`.`nama_site` AS `nama_site`,`s`.`wilayah` AS `wilayah_site` from ((((`antrian` `a` join `pasien` `p` on((`a`.`id_pasien` = `p`.`id_pasien`))) join `layanan` `l` on((`a`.`id_layanan` = `l`.`id_layanan`))) left join `dokter` `d` on((`a`.`id_dokter` = `d`.`id_dokter`))) join `sites` `s` on((`a`.`id_site` = `s`.`id_site`))) where (`a`.`status` in ('MENUNGGU','DIPANGGIL')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_laporan_harian`
--

/*!50001 DROP VIEW IF EXISTS `v_laporan_harian`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_laporan_harian` AS select `a`.`tanggal` AS `tanggal`,`l`.`nama_layanan` AS `nama_layanan`,`s`.`nama_site` AS `nama_site`,count(0) AS `total`,sum((`a`.`status` = 'MENUNGGU')) AS `menunggu`,sum((`a`.`status` = 'DIPANGGIL')) AS `dipanggil`,sum((`a`.`status` = 'SELESAI')) AS `selesai`,sum((`a`.`status` = 'BATAL')) AS `batal`,coalesce(round(avg(timestampdiff(MINUTE,`a`.`waktu_daftar`,coalesce(`a`.`waktu_dipanggil`,`a`.`waktu_selesai`))),0),0) AS `rata_rata_tunggu` from ((`antrian` `a` join `layanan` `l` on((`a`.`id_layanan` = `l`.`id_layanan`))) join `sites` `s` on((`a`.`id_site` = `s`.`id_site`))) group by `a`.`tanggal`,`l`.`nama_layanan`,`s`.`nama_site` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_rekam_medis_lengkap`
--

/*!50001 DROP VIEW IF EXISTS `v_rekam_medis_lengkap`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_rekam_medis_lengkap` AS select `rm`.`id_rekam_medis` AS `id_rekam_medis`,`rm`.`id_antrian` AS `id_antrian`,`rm`.`id_pasien` AS `id_pasien`,`rm`.`id_dokter` AS `id_dokter`,`rm`.`tanggal_periksa` AS `tanggal_periksa`,`rm`.`anamnesis` AS `anamnesis`,`rm`.`pemeriksaan_fisik` AS `pemeriksaan_fisik`,`rm`.`diagnosis` AS `diagnosis`,`rm`.`tindakan` AS `tindakan`,`rm`.`resep_obat` AS `resep_obat`,`rm`.`catatan` AS `catatan`,`rm`.`tekanan_darah` AS `tekanan_darah`,`rm`.`suhu` AS `suhu`,`rm`.`nadi` AS `nadi`,`rm`.`berat_badan` AS `berat_badan`,`rm`.`tinggi_badan` AS `tinggi_badan`,`rm`.`created_at` AS `created_at`,`p`.`nama_pasien` AS `nama_pasien`,`p`.`no_rekam_medis` AS `no_rekam_medis`,`d`.`nama_dokter` AS `nama_dokter`,`a`.`no_antrian` AS `no_antrian` from (((`rekam_medis` `rm` join `pasien` `p` on((`rm`.`id_pasien` = `p`.`id_pasien`))) left join `dokter` `d` on((`rm`.`id_dokter` = `d`.`id_dokter`))) left join `antrian` `a` on((`rm`.`id_antrian` = `a`.`id_antrian`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_tagihan_lengkap`
--

/*!50001 DROP VIEW IF EXISTS `v_tagihan_lengkap`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_tagihan_lengkap` AS select `t`.`id_tagihan` AS `id_tagihan`,`t`.`id_antrian` AS `id_antrian`,`t`.`id_pasien` AS `id_pasien`,`t`.`biaya_konsultasi` AS `biaya_konsultasi`,`t`.`biaya_tindakan` AS `biaya_tindakan`,`t`.`biaya_obat` AS `biaya_obat`,`t`.`diskon` AS `diskon`,`t`.`total_tagihan` AS `total_tagihan`,`t`.`status_bayar` AS `status_bayar`,`t`.`metode_pembayaran` AS `metode_pembayaran`,`t`.`jumlah_bayar` AS `jumlah_bayar`,`t`.`kembalian` AS `kembalian`,`t`.`waktu_bayar` AS `waktu_bayar`,`t`.`catatan` AS `catatan`,`t`.`created_at` AS `created_at`,`p`.`nama_pasien` AS `nama_pasien`,`p`.`no_rekam_medis` AS `no_rekam_medis`,`a`.`no_antrian` AS `no_antrian`,`d`.`nama_dokter` AS `nama_dokter`,`l`.`nama_layanan` AS `nama_layanan` from ((((`tagihan` `t` join `pasien` `p` on((`t`.`id_pasien` = `p`.`id_pasien`))) left join `antrian` `a` on((`t`.`id_antrian` = `a`.`id_antrian`))) left join `dokter` `d` on((`a`.`id_dokter` = `d`.`id_dokter`))) left join `layanan` `l` on((`a`.`id_layanan` = `l`.`id_layanan`))) */;
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

-- Dump completed on 2026-06-05 21:53:27
