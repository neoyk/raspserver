-- MySQL dump 10.13  Distrib 5.1.60, for redhat-linux-gnu (i386)
--
-- Host: localhost    Database: raspberry
-- ------------------------------------------------------
-- Server version	5.1.60-log

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
-- Current Database: `raspberry`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `raspberry` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `raspberry`;

--
-- Table structure for table `ipv4server`
--

DROP TABLE IF EXISTS `ipv4server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipv4server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webdomain` varchar(800) NOT NULL,
  `asn` varchar(14) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `location` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `crawl` int(11) DEFAULT '1',
  `slow` int(11) DEFAULT '0',
  `error` int(11) DEFAULT '0',
  `aspath` varchar(100) DEFAULT NULL,
  `pagesize` double DEFAULT '1000000',
  `performance` varchar(200) DEFAULT NULL,
  `bandwidth` double DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`,`webdomain`)
) ENGINE=MyISAM AUTO_INCREMENT=2440 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ipv6server`
--

DROP TABLE IF EXISTS `ipv6server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipv6server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webdomain` varchar(1000) DEFAULT NULL,
  `asn` varchar(14) DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `location` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `crawl` int(11) DEFAULT '1',
  `slow` int(11) DEFAULT '0',
  `error` int(11) DEFAULT '0',
  `aspath` varchar(100) DEFAULT NULL,
  `pagesize` double DEFAULT '1000000',
  `performance` varchar(200) DEFAULT NULL,
  `bandwidth` double DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `webdomain` (`webdomain`)
) ENGINE=MyISAM AUTO_INCREMENT=646 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `siteinfo`
--

DROP TABLE IF EXISTS `siteinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siteinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `mac` varchar(20) DEFAULT NULL,
  `ipv4count` int(11) DEFAULT '0',
  `ipv6count` int(11) DEFAULT '0',
  `description` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `latest` datetime DEFAULT '2000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_mac` (`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Current Database: `raspresults`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `raspresults` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `raspresults`;

--
-- Table structure for table `avgbw4`
--

DROP TABLE IF EXISTS `avgbw4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgbw4` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgbw` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avgbw6`
--

DROP TABLE IF EXISTS `avgbw6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgbw6` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgbw` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avgloss4`
--

DROP TABLE IF EXISTS `avgloss4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgloss4` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgloss` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avgloss6`
--

DROP TABLE IF EXISTS `avgloss6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgloss6` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgloss` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avgrtt4`
--

DROP TABLE IF EXISTS `avgrtt4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgrtt4` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgrtt` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avgrtt6`
--

DROP TABLE IF EXISTS `avgrtt6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avgrtt6` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `avgrtt` double NOT NULL,
  `type` varchar(50) NOT NULL,
  KEY `selection` (`mac`,`time`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (mac)
PARTITIONS 100 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `current4`
--

DROP TABLE IF EXISTS `current4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `current4` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `genre` varchar(20) NOT NULL,
  `type` varchar(50) NOT NULL,
  `vmin` double NOT NULL,
  `vmax` double NOT NULL,
  `vmean` double NOT NULL,
  `stdv` double NOT NULL,
  UNIQUE KEY `mac` (`mac`,`genre`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `current6`
--

DROP TABLE IF EXISTS `current6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `current6` (
  `mac` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `genre` varchar(20) NOT NULL,
  `type` varchar(50) NOT NULL,
  `vmin` double NOT NULL,
  `vmax` double NOT NULL,
  `vmean` double NOT NULL,
  `stdv` double NOT NULL,
  UNIQUE KEY `mac` (`mac`,`genre`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb39794d_address`
--

DROP TABLE IF EXISTS `perf_b827eb39794d_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb39794d_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` char(20) NOT NULL,
  `ipv4` varchar(200) NOT NULL,
  `asn4` varchar(100) NOT NULL,
  `ipv6` varchar(400) NOT NULL,
  `asn6` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb39794d_v4`
--

DROP TABLE IF EXISTS `perf_b827eb39794d_v4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb39794d_v4` (
  `id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb39794d_v6`
--

DROP TABLE IF EXISTS `perf_b827eb39794d_v6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb39794d_v6` (
  `id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb6c383f_address`
--

DROP TABLE IF EXISTS `perf_b827eb6c383f_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb6c383f_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` char(20) NOT NULL,
  `ipv4` varchar(200) NOT NULL,
  `asn4` varchar(100) NOT NULL,
  `ipv6` varchar(400) NOT NULL,
  `asn6` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb6c383f_v4`
--

DROP TABLE IF EXISTS `perf_b827eb6c383f_v4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb6c383f_v4` (
  `id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827eb6c383f_v6`
--

DROP TABLE IF EXISTS `perf_b827eb6c383f_v6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827eb6c383f_v6` (
  `id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827ebb0dec6_address`
--

DROP TABLE IF EXISTS `perf_b827ebb0dec6_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827ebb0dec6_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` char(20) DEFAULT NULL,
  `ipv4` varchar(200) NOT NULL,
  `asn4` varchar(200) NOT NULL,
  `ipv6` varchar(400) NOT NULL,
  `asn6` varchar(200) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827ebb0dec6_v4`
--

DROP TABLE IF EXISTS `perf_b827ebb0dec6_v4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827ebb0dec6_v4` (
  `id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float DEFAULT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `id_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_b827ebb0dec6_v6`
--

DROP TABLE IF EXISTS `perf_b827ebb0dec6_v6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_b827ebb0dec6_v6` (
  `id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float DEFAULT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_e84e06202971_address`
--

DROP TABLE IF EXISTS `perf_e84e06202971_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_e84e06202971_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` char(20) NOT NULL,
  `ipv4` varchar(200) NOT NULL,
  `asn4` varchar(100) NOT NULL,
  `ipv6` varchar(400) NOT NULL,
  `asn6` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_e84e06202971_v4`
--

DROP TABLE IF EXISTS `perf_e84e06202971_v4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_e84e06202971_v4` (
  `id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perf_e84e06202971_v6`
--

DROP TABLE IF EXISTS `perf_e84e06202971_v6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perf_e84e06202971_v6` (
  `id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `asn` varchar(20) DEFAULT NULL,
  `webdomain` varchar(500) NOT NULL,
  `time` datetime NOT NULL,
  `bandwidth` double NOT NULL,
  `pagesize` double NOT NULL,
  `latency` float NOT NULL,
  `lossrate` float NOT NULL,
  `actual_loss` float NOT NULL,
  `maxbw` double NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  UNIQUE KEY `idx_time` (`id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-26 20:06:41
