# ************************************************************
# Sequel Pro SQL dump
# Version 5224
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.37)
# Database: poker_olivier_blank
# Generation Time: 2018-07-30 05:22:41 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_affiliate_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_affiliate_user`;

CREATE TABLE `admin_affiliate_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(250) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table admin_points_transfer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_points_transfer`;

CREATE TABLE `admin_points_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(250) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table cata_apps_folders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_apps_folders`;

CREATE TABLE `cata_apps_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_order_id` int(11) DEFAULT NULL,
  `title` text,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_apps_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_apps_items`;

CREATE TABLE `cata_apps_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_order_id` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `icon` varchar(300) DEFAULT NULL,
  `link` varchar(300) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `information` text,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_catalogs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_catalogs`;

CREATE TABLE `cata_catalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `tables` text,
  `skin` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_env_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_env_items`;

CREATE TABLE `cata_env_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_order_id` int(11) DEFAULT NULL,
  `title` text,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_logger_journal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_logger_journal`;

CREATE TABLE `cata_logger_journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `milliseconds` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `user` varchar(500) DEFAULT NULL,
  `app` varchar(200) DEFAULT NULL,
  `content` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_manual
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_manual`;

CREATE TABLE `cata_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `content` mediumtext,
  `shorthand` text,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_settings_vars
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_settings_vars`;

CREATE TABLE `cata_settings_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `value` text,
  `type` int(11) DEFAULT NULL,
  `variants` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table cata_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cata_users`;

CREATE TABLE `cata_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(300) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `info` text,
  `ssid` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_affiliate_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_affiliate_requests`;

CREATE TABLE `poker_affiliate_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `answered` datetime DEFAULT NULL,
  `user` varchar(1000) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cache_files_player
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_files_player`;

CREATE TABLE `poker_cache_files_player` (
  `name` varchar(500) DEFAULT NULL,
  `playername` varchar(1000) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  KEY `name` (`name`),
  KEY `date` (`date`),
  KEY `playername` (`playername`(767))
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cache_handhistory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_handhistory`;

CREATE TABLE `poker_cache_handhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `data` mediumtext,
  `grabbed` datetime DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_data` (`data`(100)),
  KEY `idx_date` (`date`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cache_hands
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_hands`;

CREATE TABLE `poker_cache_hands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `hand_id` varchar(50) NOT NULL,
  `player_name` varchar(200) NOT NULL,
  `ring_name` varchar(200) NOT NULL,
  `player_rake` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_name_index` (`player_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_cache_ringgames
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_ringgames`;

CREATE TABLE `poker_cache_ringgames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `status` varchar(1000) DEFAULT NULL,
  `description` text,
  `auto` varchar(10) DEFAULT NULL,
  `game` varchar(1000) DEFAULT NULL,
  `pw` varchar(1000) DEFAULT NULL,
  `private` varchar(10) DEFAULT NULL,
  `permplay` text,
  `permobserve` text,
  `permplayerchat` text,
  `permobserverchat` text,
  `suspendchatallin` varchar(10) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `smallestchip` float DEFAULT NULL,
  `buyinmin` float DEFAULT NULL,
  `buyinmax` float DEFAULT NULL,
  `buyindef` float DEFAULT NULL,
  `rake` float DEFAULT NULL,
  `rakeevery` float DEFAULT NULL,
  `rakemax` float DEFAULT NULL,
  `turnclock` float DEFAULT NULL,
  `timebank` float DEFAULT NULL,
  `bankreset` float DEFAULT NULL,
  `disprotect` varchar(10) DEFAULT NULL,
  `smallblind` float DEFAULT NULL,
  `bigblind` float DEFAULT NULL,
  `dupeips` varchar(10) DEFAULT NULL,
  `ratholeminutes` float DEFAULT NULL,
  `sitoutminutes` float DEFAULT NULL,
  `sitoutrelaxed` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cache_tournament_results
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_tournament_results`;

CREATE TABLE `poker_cache_tournament_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `tournament` varchar(1000) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `buyin` varchar(1000) DEFAULT NULL,
  `prizebonus` varchar(1000) DEFAULT NULL,
  `multiplybonus` varchar(100) DEFAULT NULL,
  `entrants` int(11) DEFAULT NULL,
  `late` int(11) DEFAULT NULL,
  `removed` int(11) DEFAULT NULL,
  `rebuys` varchar(11) DEFAULT '0',
  `addons` varchar(11) DEFAULT '0',
  `rebuycost` varchar(1000) DEFAULT NULL,
  `netbonus` float DEFAULT NULL,
  `stoponchop` varchar(100) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `stop` datetime DEFAULT NULL,
  `places` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cache_tournaments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cache_tournaments`;

CREATE TABLE `poker_cache_tournaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point_fee` float DEFAULT '0',
  `restart_time` float DEFAULT '0',
  `point_enabled` int(11) DEFAULT '0',
  `name` varchar(1000) DEFAULT NULL,
  `status` varchar(1000) DEFAULT NULL,
  `description` text,
  `auto` varchar(100) DEFAULT NULL,
  `game` varchar(1000) DEFAULT NULL,
  `shootout` varchar(100) DEFAULT NULL,
  `pw` varchar(1000) DEFAULT NULL,
  `private` varchar(100) DEFAULT NULL,
  `permregister` text,
  `permunregister` text,
  `permobserve` text,
  `permplayerchat` text,
  `permobserverchat` text,
  `suspendchatallin` text,
  `tables` int(11) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `startfull` varchar(100) DEFAULT NULL,
  `startmin` int(11) DEFAULT NULL,
  `startcode` int(11) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL,
  `regminutes` int(11) DEFAULT NULL,
  `lateregminutes` int(11) DEFAULT NULL,
  `minplayers` int(11) DEFAULT NULL,
  `recurminutes` int(11) DEFAULT NULL,
  `noshowminutes` int(11) DEFAULT NULL,
  `buyin` float DEFAULT NULL,
  `entryfee` float DEFAULT NULL,
  `prizebonus` float DEFAULT NULL,
  `multiplybonus` varchar(50) DEFAULT NULL,
  `chips` float DEFAULT NULL,
  `addonchips` float DEFAULT NULL,
  `turnclock` float DEFAULT NULL,
  `timebank` float DEFAULT NULL,
  `bankreset` float DEFAULT NULL,
  `disprotect` varchar(100) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `rebuylevels` int(11) DEFAULT NULL,
  `threshold` float DEFAULT NULL,
  `maxrebuys` int(11) DEFAULT NULL,
  `rebuycost` float DEFAULT NULL,
  `rebuyfee` float DEFAULT NULL,
  `breaktime` float DEFAULT NULL,
  `breaklevels` float DEFAULT NULL,
  `stoponchop` varchar(100) DEFAULT NULL,
  `blinds` mediumtext,
  `payout` text,
  `unreglogout` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `show` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_cashout_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_cashout_transactions`;

CREATE TABLE `poker_cashout_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(1000) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `method` varchar(1000) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_chips_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_chips_transactions`;

CREATE TABLE `poker_chips_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(1000) DEFAULT NULL,
  `target` varchar(1000) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `data` text,
  `tournament_name` varchar(322) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_deposit_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_deposit_transactions`;

CREATE TABLE `poker_deposit_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(1000) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data` text,
  `payment_method` varchar(322) DEFAULT NULL,
  `paid_from` varchar(322) DEFAULT NULL,
  `paid_from_account` varchar(322) DEFAULT NULL,
  `paid_from_country` varchar(322) DEFAULT NULL,
  `transaction_id` varchar(322) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_frame_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_frame_requests`;

CREATE TABLE `poker_frame_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_messages`;

CREATE TABLE `poker_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_name` varchar(1000) DEFAULT NULL,
  `to_name` varchar(1000) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `mark_read` tinyint(1) NOT NULL DEFAULT '0',
  `sent_to_everyone` int(1) NOT NULL DEFAULT '0',
  `answer_from` int(11) DEFAULT NULL,
  `attachment` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message_from` (`from_name`(255)),
  KEY `message_to` (`to_name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_payment_methods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_payment_methods`;

CREATE TABLE `poker_payment_methods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(322) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_player_rake
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_player_rake`;

CREATE TABLE `poker_player_rake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `player_name` varchar(100) NOT NULL,
  `total_rake` decimal(11,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_tickets`;

CREATE TABLE `poker_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament` varchar(1000) DEFAULT NULL,
  `places` int(11) DEFAULT NULL,
  `tournament_for` varchar(1000) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_tournament_registrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_tournament_registrations`;

CREATE TABLE `poker_tournament_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(1000) DEFAULT NULL,
  `tournament` varchar(1000) DEFAULT NULL,
  `registered` datetime DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_tournament_request_invited_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_tournament_request_invited_users`;

CREATE TABLE `poker_tournament_request_invited_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tournament_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_tournament_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_tournament_requests`;

CREATE TABLE `poker_tournament_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game` varchar(255) DEFAULT NULL,
  `table` int(11) DEFAULT NULL,
  `seat` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `status` tinytext,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `invite_user_ids` varchar(500) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `chips_to_pay` int(11) DEFAULT NULL,
  `buyin` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_users`;

CREATE TABLE `poker_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `balance` float DEFAULT '0',
  `points` float DEFAULT '0',
  `points_dec` float DEFAULT '0',
  `tournaments_fee` float DEFAULT '0',
  `chips` float DEFAULT NULL,
  `referral` int(11) DEFAULT NULL,
  `referral_level` int(11) DEFAULT '1',
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  `location` varchar(1000) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `api_id` int(11) DEFAULT NULL,
  `registered` datetime DEFAULT NULL,
  `rake` float DEFAULT NULL,
  `income` float DEFAULT '0',
  `income_paid` float DEFAULT '0',
  `rake_parsed` datetime DEFAULT NULL,
  `totalrake` varchar(2000) DEFAULT '[{"rake":"0", "date":"2016-10-10 00:00:00"}]',
  `comission` float DEFAULT '0.01',
  `level2_comission` float DEFAULT '0.1',
  `level2` int(11) DEFAULT '0',
  `ssid` varchar(60) DEFAULT NULL,
  `link2_commission` float NOT NULL DEFAULT '0',
  `tournaments_fee_aff` float NOT NULL DEFAULT '0',
  `rake_aff` decimal(11,2) NOT NULL DEFAULT '0.00',
  `freeroll_fee_aff` float NOT NULL DEFAULT '0',
  `country_geolocated` varchar(200) DEFAULT NULL,
  `show_realname` tinyint(1) NOT NULL DEFAULT '0',
  `show_email` tinyint(1) NOT NULL DEFAULT '0',
  `points_spend_registration` float NOT NULL DEFAULT '0',
  `referrals_before_affiliate` int(11) NOT NULL DEFAULT '0',
  `tournaments_fee_aff2` float NOT NULL DEFAULT '0',
  `rake_aff2` decimal(11,2) NOT NULL DEFAULT '0.00',
  `freeroll_fee_aff2` float NOT NULL DEFAULT '0',
  `reset_password_token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral` (`referral`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;



# Dump of table poker_users_transfer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_users_transfer`;

CREATE TABLE `poker_users_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playername` varchar(200) NOT NULL,
  `created_time` datetime NOT NULL,
  `amount` float NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table poker_variables
# ------------------------------------------------------------

DROP TABLE IF EXISTS `poker_variables`;

CREATE TABLE `poker_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(3000) DEFAULT NULL,
  `value` varchar(1000) DEFAULT NULL,
  `changed` datetime DEFAULT NULL,
  `data` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

LOCK TABLES `poker_variables` WRITE;
/*!40000 ALTER TABLE `poker_variables` DISABLE KEYS */;

INSERT INTO `poker_variables` (`id`, `name`, `value`, `changed`, `data`)
VALUES
	(1,'points_invitation','30','2017-07-12 07:57:18',''),
	(2,'referral_mintime','10','2016-10-27 12:35:53',''),
	(3,'invitations_affiliate','2','2017-09-01 09:15:23',''),
	(4,'points_formula_rake','0.5','2018-07-15 23:04:11',''),
	(5,'points_invitation_rake','5','2018-07-15 15:43:41',''),
	(6,'invitations_affiliate_rake','4','2017-07-14 09:00:52',''),
	(7,'second_level_commission','','2017-07-05 08:34:55',''),
	(8,'url_game_frame','http://192.99.236.78','2018-05-03 19:54:40',''),
	(9,'domain_referrals','http://192.99.236.79','2018-06-04 04:59:16',''),
	(10,'social_link_description','Earn money with online poker!',NULL,NULL),
	(11,'smtp_host','smtp.yandex.com','2018-07-26 22:20:45',NULL),
	(12,'smtp_port','465','2018-07-26 22:06:48',NULL),
	(13,'smtp_secure','ssl','2018-07-26 22:06:47',NULL),
	(14,'smtp_username','smtp@daudau.cc','2018-07-26 22:06:51',NULL),
	(15,'smtp_password','123456','2018-07-26 22:10:58',NULL),
	(16,'enable_cashout','1','2018-07-15 21:27:08',NULL),
	(17,'deposit_currency','USD','2018-07-15 22:34:11',NULL),
	(18,'deposit_rate','8','2018-07-15 22:34:11',NULL),
	(19,'tournament_shootout','No','2018-07-17 02:55:15',NULL),
	(20,'tournament_auto','Yes','2018-07-17 02:55:14',NULL),
	(21,'tournament_suspendchatallin','No','2018-07-17 02:55:12',NULL),
	(22,'tournament_startfull','Yes','2018-07-17 02:55:35',NULL),
	(23,'tournament_startmin','2','2018-07-17 02:55:50',NULL),
	(24,'tournament_startcode','','2018-07-21 03:22:15',NULL),
	(25,'tournament_regminutes','','2018-07-21 03:22:19',NULL),
	(26,'tournament_lateregminutes','','2018-07-21 03:22:23',NULL),
	(27,'tournament_minplayers','','2018-07-21 03:22:54',NULL),
	(28,'tournament_recurminutes','','2018-07-21 03:22:33',NULL),
	(29,'tournament_resetseconds','','2018-07-21 03:22:41',NULL),
	(30,'tournament_minplayers','','2018-07-21 03:22:54',NULL),
	(31,'tournament_noshowminutes','','2018-07-21 03:23:00',NULL),
	(32,'tournament_buyin','','2018-07-21 03:23:02',NULL),
	(33,'tournament_entryfee','','2018-07-21 03:23:09',NULL),
	(34,'tournament_prizebonus','','2018-07-21 03:23:12',NULL),
	(35,'tournament_multiplybonus','Yes','2018-07-17 02:57:29',NULL),
	(36,'tournament_chips','10','2018-07-18 23:23:08',NULL),
	(37,'tournament_addonchips','','2018-07-21 03:23:41',NULL),
	(38,'tournament_turnclock','','2018-07-21 03:23:43',NULL),
	(39,'tournament_turnwarning','','2018-07-21 03:23:46',NULL),
	(40,'tournament_timebank','','2018-07-21 03:23:52',NULL),
	(41,'tournament_banksync','No','2018-07-18 23:23:10',NULL),
	(42,'tournament_bankreset','','2018-07-21 03:24:03',NULL),
	(43,'tournament_disprotect','No','2018-07-18 23:23:13',NULL),
	(44,'tournament_level','','2018-07-21 03:24:05',NULL),
	(45,'tournament_rebuylevels','','2018-07-21 03:24:08',NULL),
	(46,'tournament_threshold','','2018-07-21 03:24:10',NULL),
	(47,'tournament_maxrebuys','','2018-07-21 03:24:13',NULL),
	(48,'tournament_rebuycost','','2018-07-21 03:24:16',NULL),
	(49,'tournament_rebuyfee','','2018-07-21 03:24:18',NULL),
	(50,'tournament_breaktime','','2018-07-21 03:24:20',NULL),
	(51,'tournament_breaklevels','','2018-07-21 03:24:50',NULL),
	(52,'tournament_stoponchop','Yes','2018-07-18 23:23:23',NULL),
	(53,'tournament_bringinpercent','','2018-07-26 23:25:27',NULL),
	(54,'tournament_blinds','','2018-07-19 00:19:39',NULL),
	(55,'tournament_payout','60,40','2018-07-26 23:26:46',NULL),
	(56,'tournament_unreglogout','No','2018-07-17 02:52:36',NULL),
	(57,'tournament_request_chips_per_seat','10','2018-07-17 21:58:29',NULL),
	(58,'tournament_index','24','2018-07-30 05:35:23',NULL),
	(59,'tournament_fee','0.2','2018-07-26 23:24:30',NULL);

/*!40000 ALTER TABLE `poker_variables` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
