-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: webuser
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

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
-- Table structure for table `bp_accept`
--

DROP TABLE IF EXISTS `bp_accept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bp_accept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=574 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bp_accept`
--

LOCK TABLES `bp_accept` WRITE;
/*!40000 ALTER TABLE `bp_accept` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_accept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bp_userlist`
--

DROP TABLE IF EXISTS `bp_userlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bp_userlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `dtstart` datetime NOT NULL,
  `comment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dtend` datetime NOT NULL,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2712 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bp_userlist`
--

LOCK TABLES `bp_userlist` WRITE;
/*!40000 ALTER TABLE `bp_userlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_userlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bp_xml`
--

DROP TABLE IF EXISTS `bp_xml`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bp_xml` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ERPcode` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `node` int(11) NOT NULL,
  `xml` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `step` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bp_xml`
--

LOCK TABLES `bp_xml` WRITE;
/*!40000 ALTER TABLE `bp_xml` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_xml` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bp_xml_userlist`
--

DROP TABLE IF EXISTS `bp_xml_userlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bp_xml_userlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpid` int(11) NOT NULL,
  `dtstart` datetime NOT NULL,
  `dtend` datetime NOT NULL,
  `timer` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `accept` int(11) NOT NULL,
  `cancel` int(11) NOT NULL,
  `thinking` int(11) NOT NULL,
  `yes` int(11) NOT NULL,
  `no` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `two` int(11) NOT NULL,
  `three` int(11) NOT NULL,
  `four` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `result` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `node` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=778 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bp_xml_userlist`
--

LOCK TABLES `bp_xml_userlist` WRITE;
/*!40000 ALTER TABLE `bp_xml_userlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_xml_userlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cloud_dirs`
--

DROP TABLE IF EXISTS `cloud_dirs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cloud_dirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_dirs`
--

LOCK TABLES `cloud_dirs` WRITE;
/*!40000 ALTER TABLE `cloud_dirs` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_dirs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cloud_files`
--

DROP TABLE IF EXISTS `cloud_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cloud_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloud_dirs_id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `filename` varchar(150) COLLATE utf8_bin NOT NULL,
  `dt` datetime NOT NULL,
  `sz` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cloud_files`
--

LOCK TABLES `cloud_files` WRITE;
/*!40000 ALTER TABLE `cloud_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `cloud_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad` tinyint(1) NOT NULL,
  `domain1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `domain2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ldap` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usercanregistrate` tinyint(1) NOT NULL,
  `useraddfromad` tinyint(1) NOT NULL,
  `theme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sitename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `emailadmin` varchar(100) CHARACTER SET latin1 NOT NULL,
  `smtphost` varchar(20) CHARACTER SET latin1 NOT NULL,
  `smtpauth` tinyint(1) NOT NULL,
  `smtpport` varchar(20) CHARACTER SET latin1 NOT NULL,
  `smtpusername` varchar(40) CHARACTER SET latin1 NOT NULL,
  `smtppass` varchar(20) CHARACTER SET latin1 NOT NULL,
  `emailreplyto` varchar(40) CHARACTER SET latin1 NOT NULL,
  `sendemail` tinyint(1) NOT NULL,
  `version` varchar(10) CHARACTER SET latin1 NOT NULL,
  `urlsite` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,0,'','','',1,1,'bootstrap','Учет ТМЦ в организации','','',0,'25','','','',0,'3.90','http://localhost');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_common`
--

DROP TABLE IF EXISTS `config_common`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameparam` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `valueparam` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_common`
--

LOCK TABLES `config_common` WRITE;
/*!40000 ALTER TABLE `config_common` DISABLE KEYS */;
INSERT INTO `config_common` VALUES (119,'modulename_schedule','0'),(120,'modulecomment_schedule','Расписание уведомлений'),(121,'modulecopy_schedule','Грибов Павел'),(122,'modulename_arduinorele','0'),(123,'modulecomment_arduinorele','Управление реле Arduino'),(124,'modulecopy_arduinorele','Грибов Павел'),(125,'modulename_astra','0'),(126,'modulecomment_astra','Управление серверами Astra'),(127,'modulecopy_astra','Грибов Павел'),(128,'modulename_pbi','0'),(129,'modulecomment_pbi','Управление станциями PBI'),(130,'modulecopy_pbi','Грибов Павел'),(131,'modulename_cloud','1'),(132,'modulecomment_cloud','Хранилище документов'),(133,'modulecopy_cloud','Грибов Павел'),(134,'modulename_devicescontrol','0'),(135,'modulecomment_devicescontrol','Управление устройствами'),(136,'modulecopy_devicescontrol','Грибов Павел'),(137,'modulename_bprocess','0'),(138,'modulecomment_bprocess','Бизнес-процессы'),(139,'modulecopy_bprocess','Грибов Павел'),(140,'modulename_cables','0'),(141,'modulecomment_cables',''),(142,'modulecopy_cables','Справочник кабелей и муфт'),(143,'modulename_dop-pol','0'),(144,'modulecopy_dop-pol','Справочник дополнительных полей'),(145,'modulename_scriptalert','0'),(146,'modulecomment_scriptalert','Мониторинг выполнения скриптов'),(147,'modulecopy_scriptalert','Грибов Павел'),(148,'modulename_smscenter','0'),(149,'modulecomment_smscenter','СМС-Центр'),(150,'modulecopy_smscenter','Грибов Павел'),(151,'modulename_viber','0'),(152,'modulecomment_viber','отправка сообщений Viber'),(153,'modulecopy_viber','Грибов Павел'),(154,'modulename_worktime','0'),(155,'modulecomment_worktime','Вход и выход работников организации (турникет Орион)'),(156,'modulecopy_worktime','Грибов Павел'),(157,'modulename_workandplans','0'),(158,'modulecomment_workandplans','Оперативная обстановка на заводе'),(159,'modulecopy_workandplans','Грибов Павел'),(160,'modulename_zabbix-mon','0'),(161,'modulecomment_zabbix-mon','Мониторинг dashboard серверов Zabbix'),(162,'modulecopy_zabbix-mon','Грибов Павел'),(163,'modulename_ical','0'),(164,'modulecomment_ical','Календарь'),(165,'modulecopy_ical','Грибов Павел'),(166,'modulename_tasks','0'),(167,'modulecomment_tasks','Задачи'),(168,'modulecopy_tasks','Грибов Павел'),(169,'modulename_workmen','1'),(170,'modulecomment_workmen','Менеджер по обслуживанию '),(171,'modulecopy_workmen','Грибов Павел'),(172,'modulename_ping','1'),(173,'modulecomment_ping','Проверка доступности ТМЦ по ping'),(174,'modulecopy_ping','Грибов Павел'),(175,'modulename_chat','0'),(176,'modulecomment_chat','Чат поддержки/Общий чат'),(177,'modulecopy_chat','Грибов Павел'),(178,'modulename_htmlentry','0'),(179,'modulecomment_htmlentry','Произвольный html код на странице перед футером'),(180,'modulecopy_htmlentry','Грибов Павел'),(181,'modulename_news','1'),(182,'modulecomment_news','Модуль новостей'),(183,'modulecopy_news','Грибов Павел'),(184,'modulename_stiknews','0'),(185,'modulecomment_stiknews','Закрепленные новости'),(186,'modulecopy_stiknews','Грибов Павел'),(187,'modulename_lastmoved','1'),(188,'modulecomment_lastmoved','Последние перемещения ТМЦ'),(189,'modulecopy_lastmoved','Грибов Павел'),(190,'modulename_usersfaze','0'),(191,'modulecomment_usersfaze','Где сотрудник?'),(192,'modulecopy_usersfaze','Грибов Павел'),(193,'modulename_whoonline','0'),(194,'modulecomment_whoonline','Кто на сайте?'),(195,'modulecopy_whoonline','Грибов Павел'),(196,'modulename_commits-widget','1'),(197,'modulecomment_commits-widget','Виджет разработки на github.com на главной странице'),(198,'modulecopy_commits-widget','Солодягин Сергей');
/*!40000 ALTER TABLE `config_common` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract`
--

DROP TABLE IF EXISTS `contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kntid` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datestart` date NOT NULL,
  `dateend` date NOT NULL,
  `work` int(11) NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract`
--

LOCK TABLES `contract` WRITE;
/*!40000 ALTER TABLE `contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devgroups`
--

DROP TABLE IF EXISTS `devgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dgname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dcomment` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devgroups`
--

LOCK TABLES `devgroups` WRITE;
/*!40000 ALTER TABLE `devgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `devgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbase` int(11) NOT NULL,
  `devname` varchar(255) COLLATE utf8_bin NOT NULL,
  `whereis` varchar(255) COLLATE utf8_bin NOT NULL,
  `address` varchar(255) COLLATE utf8_bin NOT NULL,
  `param_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `param_value` varchar(255) COLLATE utf8_bin NOT NULL,
  `cnt` int(11) NOT NULL,
  `devid` int(11) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `child` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devnames`
--

DROP TABLE IF EXISTS `devnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devnames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `command` text COLLATE utf8_bin,
  `devid` int(11) DEFAULT NULL,
  `bcolor` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devnames`
--

LOCK TABLES `devnames` WRITE;
/*!40000 ALTER TABLE `devnames` DISABLE KEYS */;
/*!40000 ALTER TABLE `devnames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entropia`
--

DROP TABLE IF EXISTS `entropia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entropia` (
  `cnt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entropia`
--

LOCK TABLES `entropia` WRITE;
/*!40000 ALTER TABLE `entropia` DISABLE KEYS */;
INSERT INTO `entropia` VALUES (0);
/*!40000 ALTER TABLE `entropia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eq_param`
--

DROP TABLE IF EXISTS `eq_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eq_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grpid` int(11) NOT NULL,
  `paramid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `param` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eq_param`
--

LOCK TABLES `eq_param` WRITE;
/*!40000 ALTER TABLE `eq_param` DISABLE KEYS */;
/*!40000 ALTER TABLE `eq_param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `placesid` int(11) NOT NULL,
  `usersid` int(11) NOT NULL,
  `nomeid` int(11) NOT NULL,
  `buhname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datepost` datetime NOT NULL,
  `cost` int(11) NOT NULL,
  `currentcost` int(11) NOT NULL,
  `sernum` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `invnum` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shtrihkod` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `os` tinyint(1) NOT NULL,
  `mode` tinyint(1) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `repair` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mapx` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mapy` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mapmoved` int(2) NOT NULL,
  `mapyet` tinyint(4) NOT NULL DEFAULT '0',
  `kntid` int(11) NOT NULL,
  `dtendgar` date NOT NULL,
  `tmcgo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=400 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES (398,1,46,1,151,'Коробочка с вентилятором','2017-12-12 00:00:00',0,0,'262546234623','211201714831','',0,0,'','',1,1,'10.12.13.14','','',0,0,1008,'2017-12-12',0),(399,1,46,1,152,'Черная штучка с лампочкой, пищит','2017-12-12 00:00:00',0,0,'24123412','2112017141022','4806785252001',0,0,'','',0,1,'','','',0,0,1008,'2017-12-12',0);
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exp_log`
--

DROP TABLE IF EXISTS `exp_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TimeVal` datetime NOT NULL,
  `event` int(11) NOT NULL,
  `hozorgan` int(11) NOT NULL,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`)
) ENGINE=InnoDB AUTO_INCREMENT=221645 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exp_log`
--

LOCK TABLES `exp_log` WRITE;
/*!40000 ALTER TABLE `exp_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `exp_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bpid` int(11) NOT NULL,
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files_contract`
--

DROP TABLE IF EXISTS `files_contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcontract` int(11) NOT NULL,
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userfreandlyfilename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=935 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files_contract`
--

LOCK TABLES `files_contract` WRITE;
/*!40000 ALTER TABLE `files_contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `files_contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geouserhist`
--

DROP TABLE IF EXISTS `geouserhist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geouserhist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `dt` datetime NOT NULL,
  `Nlongitude` varchar(20) NOT NULL,
  `Nlatitude` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geouserhist`
--

LOCK TABLES `geouserhist` WRITE;
/*!40000 ALTER TABLE `geouserhist` DISABLE KEYS */;
/*!40000 ALTER TABLE `geouserhist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_nome`
--

DROP TABLE IF EXISTS `group_nome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_nome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_nome`
--

LOCK TABLES `group_nome` WRITE;
/*!40000 ALTER TABLE `group_nome` DISABLE KEYS */;
INSERT INTO `group_nome` VALUES (1,'Мониторы','',1),(2,'ИБП','',1),(3,'Роутеры/Маршрутизаторы/Свичи','',1),(4,'Системные блоки','',1),(5,'Принтера','',1),(6,'Столы','',1),(7,'Стулья','',1),(8,'Телевизоры','',1),(9,'Чайники','',1),(10,'Мышки','',1);
/*!40000 ALTER TABLE `group_nome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_param`
--

DROP TABLE IF EXISTS `group_param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_param`
--

LOCK TABLES `group_param` WRITE;
/*!40000 ALTER TABLE `group_param` DISABLE KEYS */;
INSERT INTO `group_param` VALUES (22,4,'OS',1),(23,4,'RAM',1),(24,4,'HDD',1),(25,4,'Прочее',1),(28,5,'Модель катриджа',1),(29,10,'Тип разьема',1);
/*!40000 ALTER TABLE `group_param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jqcalendar`
--

DROP TABLE IF EXISTS `jqcalendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jqcalendar` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `IsAllDayEvent` smallint(6) NOT NULL,
  `Color` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `RecurringRule` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `uidview` varchar(10) COLLATE utf8_bin NOT NULL,
  `lbid` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jqcalendar`
--

LOCK TABLES `jqcalendar` WRITE;
/*!40000 ALTER TABLE `jqcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `jqcalendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knt`
--

DROP TABLE IF EXISTS `knt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fullname` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ERPCode` int(11) NOT NULL,
  `INN` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `KPP` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bayer` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `dog` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1010 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knt`
--

LOCK TABLES `knt` WRITE;
/*!40000 ALTER TABLE `knt` DISABLE KEYS */;
INSERT INTO `knt` VALUES (1008,'ООО \"Ракета\"','Закупка оргтехники',1,'',0,'352501001','352501001',0,0,1),(1009,'ООО \"Экспрес-сервис\"','Ремонт и заправка принтеров',1,'',0,'24243523452','',0,0,0);
/*!40000 ALTER TABLE `knt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_cable_lines`
--

DROP TABLE IF EXISTS `lib_cable_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_cable_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_calble_module` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `color1` varchar(100) CHARACTER SET utf8 NOT NULL,
  `color2` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_cable_lines`
--

LOCK TABLES `lib_cable_lines` WRITE;
/*!40000 ALTER TABLE `lib_cable_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_cable_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_cable_modules`
--

DROP TABLE IF EXISTS `lib_cable_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_cable_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cable_id` int(11) NOT NULL,
  `number` varchar(11) CHARACTER SET utf8 NOT NULL,
  `color` varchar(100) CHARACTER SET utf8 NOT NULL,
  `color1` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_cable_modules`
--

LOCK TABLES `lib_cable_modules` WRITE;
/*!40000 ALTER TABLE `lib_cable_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_cable_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_cable_muft`
--

DROP TABLE IF EXISTS `lib_cable_muft`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_cable_muft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_cable_muft`
--

LOCK TABLES `lib_cable_muft` WRITE;
/*!40000 ALTER TABLE `lib_cable_muft` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_cable_muft` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_cable_name_mark`
--

DROP TABLE IF EXISTS `lib_cable_name_mark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_cable_name_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mark` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_cable_name_mark`
--

LOCK TABLES `lib_cable_name_mark` WRITE;
/*!40000 ALTER TABLE `lib_cable_name_mark` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_cable_name_mark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_cable_spliter`
--

DROP TABLE IF EXISTS `lib_cable_spliter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_cable_spliter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `exitcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_cable_spliter`
--

LOCK TABLES `lib_cable_spliter` WRITE;
/*!40000 ALTER TABLE `lib_cable_spliter` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_cable_spliter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_lines_in_muft`
--

DROP TABLE IF EXISTS `lib_lines_in_muft`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_lines_in_muft` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор волокна в муфте на карте',
  `mufta_id` int(11) NOT NULL COMMENT 'Идентификатор муфты на карте',
  `obj_edit_id` int(11) NOT NULL COMMENT 'Идентификатор кабеля на карте',
  `lib_line_id` int(11) NOT NULL COMMENT 'ссылка на волокно из справочника',
  `start_id` int(11) NOT NULL COMMENT 'идентификатор стыковки начала волокна',
  `end_id` int(11) NOT NULL COMMENT 'идентификатор конца волокна',
  `type_obj` varchar(20) CHARACTER SET utf8 NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_lines_in_muft`
--

LOCK TABLES `lib_lines_in_muft` WRITE;
/*!40000 ALTER TABLE `lib_lines_in_muft` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_lines_in_muft` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailq`
--

DROP TABLE IF EXISTS `mailq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `btxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailq`
--

LOCK TABLES `mailq` WRITE;
/*!40000 ALTER TABLE `mailq` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  `parents` int(11) NOT NULL COMMENT 'Родитель',
  `sort_id` int(11) NOT NULL COMMENT 'Сортировка',
  `name` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT 'Название',
  `comment` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT 'Пояснение',
  `uid` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'некий идентификатор (можно использовать для автосоздания менюшек)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mobilemessages`
--

DROP TABLE IF EXISTS `mobilemessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobilemessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `dtwrite` datetime DEFAULT NULL,
  `dtread` timestamp NULL DEFAULT NULL,
  `idsms` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mobilemessages`
--

LOCK TABLES `mobilemessages` WRITE;
/*!40000 ALTER TABLE `mobilemessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `mobilemessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `move`
--

DROP TABLE IF EXISTS `move`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `move` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `orgidfrom` int(11) NOT NULL,
  `orgidto` int(11) NOT NULL,
  `placesidfrom` int(11) NOT NULL,
  `placesidto` int(11) NOT NULL,
  `useridfrom` int(11) NOT NULL,
  `useridto` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=779 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `move`
--

LOCK TABLES `move` WRITE;
/*!40000 ALTER TABLE `move` DISABLE KEYS */;
/*!40000 ALTER TABLE `move` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `stiker` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (26,'2017-03-02 00:00:00','Учет оргтехники в WEB 3.xx','<p>Добро пожаловать!</p><p>Представляю вам демо ПО для учета оргтехники в небольшой организации. Ну и плюс еще несколько \"плюшек\".</p><p>Домашняя страница проекта:&nbsp;<a href=\"http://xn--90acbu5aj5f.xn--p1ai/?page_id=1202\">http://грибовы.рф</a></p><p>Контакты: skype: pvtuning </p>',0);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nome`
--

DROP TABLE IF EXISTS `nome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nome`
--

LOCK TABLES `nome` WRITE;
/*!40000 ALTER TABLE `nome` DISABLE KEYS */;
INSERT INTO `nome` VALUES (151,4,6,'Системный блок Лидер-1',1),(152,2,30,'ИБП SmartMaster 1200',1);
/*!40000 ALTER TABLE `nome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org`
--

DROP TABLE IF EXISTS `org`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `picmap` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org`
--

LOCK TABLES `org` WRITE;
/*!40000 ALTER TABLE `org` DISABLE KEYS */;
INSERT INTO `org` VALUES (1,'ООО Рога и Копыта','06716875881465578757.PNG',1);
/*!40000 ALTER TABLE `org` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `opgroup` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `places`
--

LOCK TABLES `places` WRITE;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` VALUES (46,1,'Серверная','',1,'АСУ'),(47,1,'Главный бухгалтер','',1,'Бухгалтерия'),(48,1,'Зарплата','',1,'Бухгалтерия');
/*!40000 ALTER TABLE `places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `places_users`
--

DROP TABLE IF EXISTS `places_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `places_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placesid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `places_users`
--

LOCK TABLES `places_users` WRITE;
/*!40000 ALTER TABLE `places_users` DISABLE KEYS */;
INSERT INTO `places_users` VALUES (91,46,1);
/*!40000 ALTER TABLE `places_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_users`
--

DROP TABLE IF EXISTS `post_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL,
  `orgid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `post` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_users`
--

LOCK TABLES `post_users` WRITE;
/*!40000 ALTER TABLE `post_users` DISABLE KEYS */;
INSERT INTO `post_users` VALUES (26,1,1,408,'Системный администратор');
/*!40000 ALTER TABLE `post_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime(6) NOT NULL,
  `eqid` int(11) NOT NULL,
  `moveid` int(11) DEFAULT NULL,
  `cnt` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `placesid` int(11) NOT NULL,
  `usersid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `register`
--

LOCK TABLES `register` WRITE;
/*!40000 ALTER TABLE `register` DISABLE KEYS */;
INSERT INTO `register` VALUES (1,'2017-12-12 00:00:00.000000',398,NULL,1,1,46,1),(2,'2017-12-12 00:00:00.000000',399,NULL,1,1,46,1);
/*!40000 ALTER TABLE `register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repair`
--

DROP TABLE IF EXISTS `repair`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` date NOT NULL,
  `kntid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `cost` float NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dtend` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `userfrom` int(11) NOT NULL,
  `userto` int(11) NOT NULL,
  `doc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repair`
--

LOCK TABLES `repair` WRITE;
/*!40000 ALTER TABLE `repair` DISABLE KEYS */;
INSERT INTO `repair` VALUES (12,'2017-12-12',1009,398,0,'Не включается','2018-02-09',1,408,-1,'');
/*!40000 ALTER TABLE `repair` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rss`
--

DROP TABLE IF EXISTS `rss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avtor` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dt` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `generator` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=484 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rss`
--

LOCK TABLES `rss` WRITE;
/*!40000 ALTER TABLE `rss` DISABLE KEYS */;
/*!40000 ALTER TABLE `rss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_center_config`
--

DROP TABLE IF EXISTS `sms_center_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_center_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agname` varchar(50) COLLATE utf8_bin NOT NULL,
  `smslogin` varchar(50) COLLATE utf8_bin NOT NULL,
  `smspass` varchar(50) COLLATE utf8_bin NOT NULL,
  `fileagent` varchar(50) COLLATE utf8_bin NOT NULL,
  `smsdiff` varchar(10) COLLATE utf8_bin NOT NULL,
  `sel` varchar(10) COLLATE utf8_bin NOT NULL,
  `sender` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_center_config`
--

LOCK TABLES `sms_center_config` WRITE;
/*!40000 ALTER TABLE `sms_center_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_center_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smslist`
--

DROP TABLE IF EXISTS `smslist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smslist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) CHARACTER SET utf8 NOT NULL,
  `smstxt` text CHARACTER SET utf8 NOT NULL,
  `status` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smslist`
--

LOCK TABLES `smslist` WRITE;
/*!40000 ALTER TABLE `smslist` DISABLE KEYS */;
/*!40000 ALTER TABLE `smslist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smsstat`
--

DROP TABLE IF EXISTS `smsstat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smsstat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `countok` int(10) NOT NULL,
  `countfail` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smsstat`
--

LOCK TABLES `smsstat` WRITE;
/*!40000 ALTER TABLE `smsstat` DISABLE KEYS */;
/*!40000 ALTER TABLE `smsstat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `touserid` int(11) NOT NULL,
  `mainuseid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `txt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `maxdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `randomid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `orgid` int(11) NOT NULL,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mode` int(11) NOT NULL,
  `lastdt` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `lastactivemob` datetime DEFAULT NULL,
  `Longitude` varchar(20) COLLATE utf8_bin NOT NULL,
  `Latitude` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=409 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'534742080754244214882660638232114002258853163157700475856647',1,'test','1b3cfc8b4a0708a3421384020bb996003e1f76ac','BfYKz]uH','test@gmail.com',1,'2017-12-12 17:18:54',1,NULL,'',''),(408,'262673588340410612400811600225553821370215267348580681751784',1,'admin','53f1f258c1b5c017e43f7e8c9da32389bb3488b3','(vZA){5','asda@mail.ru',1,'2017-12-12 14:13:11',1,NULL,'','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_ori`
--

DROP TABLE IF EXISTS `users_ori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_ori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ori_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tabnumber` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `schedule` int(11) NOT NULL,
  `fio` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_ori`
--

LOCK TABLES `users_ori` WRITE;
/*!40000 ALTER TABLE `users_ori` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_ori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_profile`
--

DROP TABLE IF EXISTS `users_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usersid` int(11) NOT NULL,
  `fio` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `faza` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `enddate` date NOT NULL,
  `post` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `res1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `res2` int(100) NOT NULL,
  `res3` int(100) NOT NULL,
  `res4` datetime NOT NULL,
  `telephonenumber` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `homephone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `jpegphoto` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_profile`
--

LOCK TABLES `users_profile` WRITE;
/*!40000 ALTER TABLE `users_profile` DISABLE KEYS */;
INSERT INTO `users_profile` VALUES (2,1,'Администратор системы','Абырвалг','88000280','0001-01-01','Начальник','115',16,0,'0000-00-00 00:00:00','+79657400222','+60222','noimage.jpg'),(390,408,'admin','','','2017-12-12','','',0,0,'0000-00-00 00:00:00','','','');
/*!40000 ALTER TABLE `users_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_quick_menu`
--

DROP TABLE IF EXISTS `users_quick_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_quick_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `ico` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_quick_menu`
--

LOCK TABLES `users_quick_menu` WRITE;
/*!40000 ALTER TABLE `users_quick_menu` DISABLE KEYS */;
INSERT INTO `users_quick_menu` VALUES (1,'Имущество','http://demo.xn--90acbu5aj5f.xn--p1ai/index.php?content_page=equipment',1,'<i class=\'fa fa-empire fa-fw\'> </i>');
/*!40000 ALTER TABLE `users_quick_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usersroles`
--

DROP TABLE IF EXISTS `usersroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usersroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usersroles`
--

LOCK TABLES `usersroles` WRITE;
/*!40000 ALTER TABLE `usersroles` DISABLE KEYS */;
INSERT INTO `usersroles` VALUES (1,1,1);
/*!40000 ALTER TABLE `usersroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (1,'Cisco','',1),(2,'Panasonic','',1),(3,'LG','',1),(4,'Citezen','',1),(6,'Сборка','',1),(8,'E-machines','',1),(9,'HP','',1),(11,'Xerox','',1),(12,'Acer','',1),(13,'Ubnt','',1),(16,'Mustek','',1),(17,'Canon','',1),(18,'Genius','',1),(20,'Epson','',1),(21,'ViewSonic','',1),(22,'MGE','',1),(23,'BENQ','',1),(24,'PLUS UPS Systems','',1),(26,'ICON','',1),(28,'Bay Networks','',1),(29,'HardLink','',1),(30,'Accorp','',1),(31,'Kyosera','',1),(32,'APC','',1),(34,'Metrologic','',1),(35,'Samsung','',1),(36,'Planet','',1),(37,'D-link','',1),(38,'Tandberg','',1),(39,'Sony','',1),(41,'Sharp','',1),(42,'Asus','',1),(43,'TP-Link','',1),(44,'DataMax','',1),(45,'Logitech','',1),(46,'Philips','',1),(47,'QUATROCLIMAT','',1),(48,'3 Com','',1),(49,'Fellowes','',1),(50,'Toshiba','',1),(51,'Western Digital','',1),(52,'FunkWerk','',1),(53,'Pascard Bell','',1);
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-12 14:19:01
