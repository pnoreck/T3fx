# ************************************************************
# T3fx table structure
# ************************************************************

# tx_t3xmailscanner_domain_model_blacklist
# ------------------------------------------------------------
CREATE TABLE `tx_t3xmailscanner_domain_model_blacklist` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `mail` varchar(255) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `tstamp` int(11) unsigned NOT NULL DEFAULT 0,
  `crdate` int(11) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `starttime` int(11) unsigned NOT NULL DEFAULT 0,
  `endtime` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# tx_t3xmailscanner_domain_model_content_filter
# ------------------------------------------------------------
CREATE TABLE `tx_t3xmailscanner_domain_model_content_filter` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `tstamp` int(11) NOT NULL,
  `crdate` int(11) NOT NULL,
  `cruser_id` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `sha1` varchar(40) DEFAULT NULL,
  `regex` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL DEFAULT '',
  `filter_type` enum('SUBJECT','BODY','BOTH','') NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# tx_t3xmailscanner_domain_model_imap_folder
# ------------------------------------------------------------
CREATE TABLE `tx_t3xmailscanner_domain_model_imap_folder` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `full_name` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `tstamp` int(11) unsigned NOT NULL DEFAULT 0,
  `crdate` int(11) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `starttime` int(11) unsigned NOT NULL DEFAULT 0,
  `endtime` int(11) unsigned NOT NULL DEFAULT 0,
  `mailscanner` int(11) unsigned NOT NULL DEFAULT 0,

  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# tx_t3xmailscanner_domain_model_sender
# ------------------------------------------------------------
CREATE TABLE `tx_t3xmailscanner_domain_model_sender` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `imap_folder` int(11) unsigned DEFAULT 0,
  `tstamp` int(11) unsigned NOT NULL DEFAULT 0,
  `crdate` int(11) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `starttime` int(11) unsigned NOT NULL DEFAULT 0,
  `endtime` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`),
  KEY `name` (`name`,`deleted`,`hidden`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# tx_t3xmailscanner_domain_model_domain_whitelist
# ------------------------------------------------------------
CREATE TABLE `tx_t3xmailscanner_domain_model_whitelist` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `tstamp` int(11) unsigned NOT NULL DEFAULT 0,
  `crdate` int(11) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `starttime` int(11) unsigned NOT NULL DEFAULT 0,
  `endtime` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`),
  KEY `name` (`name`,`deleted`,`hidden`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# t3fx_weather
# ------------------------------------------------------------
CREATE TABLE `t3fx_weather` (
  `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `crdate` int(11) NOT NULL,
  `tstamp` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `json` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# t3fx_indoor_weather_ips
# ------------------------------------------------------------
CREATE TABLE `tx_weather_domain_model_indoor_weather_ips` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crdate` int(11) NOT NULL,
  `tstamp` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL DEFAULT '',
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

# t3fx_indoor_weather
# ------------------------------------------------------------
CREATE TABLE `tx_weather_domain_model_indoor_weather` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crdate` int(11) NOT NULL,
  `tstamp` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `humidity` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

# t3fx_indoor_weather_ips_correction
# ------------------------------------------------------------
CREATE TABLE `tx_weather_domain_model_indoor_weather_ipcorrection` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crdate` int(11) NOT NULL,
  `tstamp` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL DEFAULT '',
  `temperature` int(11) NOT NULL,
  `humidity` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

