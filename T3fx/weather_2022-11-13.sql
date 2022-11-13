# ************************************************************
# Sequel Ace SQL dump
# Version 20039
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 5.5.5-10.6.7-MariaDB-2ubuntu1.1)
# Database: weather
# Generation Time: 2022-11-13 19:53:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tx_weather_domain_model_indoor_weather_ipcorrection
# ------------------------------------------------------------

LOCK TABLES `tx_weather_domain_model_indoor_weather_ipcorrection` WRITE;
/*!40000 ALTER TABLE `tx_weather_domain_model_indoor_weather_ipcorrection` DISABLE KEYS */;

INSERT INTO `tx_weather_domain_model_indoor_weather_ipcorrection` (`uid`, `crdate`, `tstamp`, `ip`, `temperature`, `humidity`)
VALUES
	(1,1668359443,1668359443,'1',0,0),
	(2,1668359443,1668359443,'2',0,-8),
	(3,1668359443,1668359443,'3',-6,10);

/*!40000 ALTER TABLE `tx_weather_domain_model_indoor_weather_ipcorrection` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tx_weather_domain_model_indoor_weather_ips
# ------------------------------------------------------------

LOCK TABLES `tx_weather_domain_model_indoor_weather_ips` WRITE;
/*!40000 ALTER TABLE `tx_weather_domain_model_indoor_weather_ips` DISABLE KEYS */;

INSERT INTO `tx_weather_domain_model_indoor_weather_ips` (`uid`, `crdate`, `tstamp`, `ip`, `name`)
VALUES
	(1,1647387516,1647387516,'192.168.1.198','Office'),
	(2,1647617184,1647617184,'192.168.1.234','Bedroom'),
	(3,1667767249,1667767249,'192.168.1.207','Bathroom');

/*!40000 ALTER TABLE `tx_weather_domain_model_indoor_weather_ips` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
