-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 11 2014 г., 15:08
-- Версия сервера: 5.5.37
-- Версия PHP: 5.4.4-14+deb7u12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `webuseorg3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bp_accept`
--

CREATE TABLE IF NOT EXISTS `bp_accept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `randomid` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=574 ;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_userlist`
--

CREATE TABLE IF NOT EXISTS `bp_userlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `dtstart` datetime NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dtend` datetime NOT NULL,
  `randomid` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2712 ;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_xml`
--

CREATE TABLE IF NOT EXISTS `bp_xml` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ERPcode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `node` int(11) NOT NULL,
  `xml` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `step` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=328 ;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_xml_userlist`
--

CREATE TABLE IF NOT EXISTS `bp_xml_userlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpid` int(11) NOT NULL,
  `dtstart` datetime NOT NULL,
  `dtend` datetime NOT NULL,
  `timer` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
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
  `result` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `node` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=778 ;

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad` tinyint(1) NOT NULL,
  `domain1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `domain2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ldap` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `usercanregistrate` tinyint(1) NOT NULL,
  `useraddfromad` tinyint(1) NOT NULL,
  `theme` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sitename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `emailadmin` varchar(100) NOT NULL,
  `smtphost` varchar(20) NOT NULL,
  `smtpauth` tinyint(1) NOT NULL,
  `smtpport` varchar(20) NOT NULL,
  `smtpusername` varchar(40) NOT NULL,
  `smtppass` varchar(20) NOT NULL,
  `emailreplyto` varchar(40) NOT NULL,
  `sendemail` tinyint(1) NOT NULL,
  `version` varchar(10) NOT NULL,
  `urlsite` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `ad`, `domain1`, `domain2`, `ldap`, `usercanregistrate`, `useraddfromad`, `theme`, `sitename`, `emailadmin`, `smtphost`, `smtpauth`, `smtpport`, `smtpusername`, `smtppass`, `emailreplyto`, `sendemail`, `version`, `urlsite`) VALUES
(1, 0, '', '', '', 1, 1, 'bootstrap', 'Ð¡Ñ‚Ð°Ñ€Ñ‚Ð¾Ð²Ð°Ñ "Ð£Ñ‡ÐµÑ‚ Ð¾Ñ€Ð³Ñ‚ÐµÑ…Ð½Ð¸ÐºÐ¸"', 'donpadlo@gmail.com', '10.3.3.1', 0, '25', 'test', 'test', 'donpadlo@gmail.com', 0, '3.03', 'http://localhost');

-- --------------------------------------------------------

--
-- Структура таблицы `config_common`
--

CREATE TABLE IF NOT EXISTS `config_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameparam` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `valueparam` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Дамп данных таблицы `config_common`
--

INSERT INTO `config_common` (`id`, `nameparam`, `valueparam`) VALUES
(1, 'modulename_ping', '1'),
(2, 'modulecomment_ping', 'ÐŸÑ€Ð¾Ð²ÐµÑ€ÐºÐ° Ð´Ð¾ÑÑ‚ÑƒÐ¿Ð½Ð¾ÑÑ‚Ð¸ Ð¢ÐœÐ¦ Ð¿Ð¾ ping'),
(3, 'modulecopy_ping', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(7, 'modulename_worktime', '0'),
(8, 'modulecomment_worktime', 'Ð’Ñ…Ð¾Ð´ Ð¸ Ð²Ñ‹Ñ…Ð¾Ð´ Ñ€Ð°Ð±Ð¾Ñ‚Ð½Ð¸ÐºÐ¾Ð² Ð¾Ñ€Ð³Ð°Ð½Ð¸Ð·Ð°Ñ†Ð¸Ð¸ (Ñ‚ÑƒÑ€Ð½Ð¸ÐºÐµÑ‚ ÐžÑ€Ð¸Ð¾Ð½)'),
(9, 'modulecopy_worktime', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(10, 'modulename_bprocess', '1'),
(11, 'modulecomment_bprocess', 'Ð‘Ð¸Ð·Ð½ÐµÑ-Ð¿Ñ€Ð¾Ñ†ÐµÑÑÑ‹'),
(12, 'modulecopy_bprocess', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(16, 'modulename_workandplans', '0'),
(17, 'modulecomment_workandplans', 'ÐžÐ¿ÐµÑ€Ð°Ñ‚Ð¸Ð²Ð½Ð°Ñ Ð¾Ð±ÑÑ‚Ð°Ð½Ð¾Ð²ÐºÐ° Ð½Ð° Ð·Ð°Ð²Ð¾Ð´Ðµ'),
(18, 'modulecopy_workandplans', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(19, 'modulename_tasks', '0'),
(20, 'modulecomment_tasks', 'Ð—Ð°Ð´Ð°Ñ‡Ð¸'),
(21, 'modulecopy_tasks', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(22, 'modulename_workmen', '0'),
(23, 'modulecomment_workmen', 'ÐœÐµÐ½ÐµÐ´Ð¶ÐµÑ€ Ð¿Ð¾ Ð¾Ð±ÑÐ»ÑƒÐ¶Ð¸Ð²Ð°Ð½Ð¸ÑŽ '),
(24, 'modulecopy_workmen', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(25, 'modulename_news', '0'),
(26, 'modulecomment_news', 'ÐœÐ¾Ð´ÑƒÐ»ÑŒ Ð½Ð¾Ð²Ð¾ÑÑ‚ÐµÐ¹'),
(27, 'modulecopy_news', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(28, 'modulename_stiknews', '0'),
(29, 'modulecomment_stiknews', 'Ð—Ð°ÐºÑ€ÐµÐ¿Ð»ÐµÐ½Ð½Ñ‹Ðµ Ð½Ð¾Ð²Ð¾ÑÑ‚Ð¸'),
(30, 'modulecopy_stiknews', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(31, 'modulename_lastmoved', '0'),
(32, 'modulecomment_lastmoved', 'ÐŸÐ¾ÑÐ»ÐµÐ´Ð½Ð¸Ðµ Ð¿ÐµÑ€ÐµÐ¼ÐµÑ‰ÐµÐ½Ð¸Ñ Ð¢ÐœÐ¦'),
(33, 'modulecopy_lastmoved', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»'),
(34, 'modulename_usersfaze', '0'),
(35, 'modulecomment_usersfaze', 'Ð“Ð´Ðµ ÑÐ¾Ñ‚Ñ€ÑƒÐ´Ð½Ð¸Ðº?'),
(36, 'modulecopy_usersfaze', 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ»');

-- --------------------------------------------------------

--
-- Структура таблицы `contract`
--

CREATE TABLE IF NOT EXISTS `contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kntid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datestart` date NOT NULL,
  `dateend` date NOT NULL,
  `work` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `num` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=424 ;

-- --------------------------------------------------------

--
-- Структура таблицы `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `placesid` int(11) NOT NULL,
  `usersid` int(11) NOT NULL,
  `nomeid` int(11) NOT NULL,
  `buhname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datepost` datetime NOT NULL,
  `cost` int(11) NOT NULL,
  `currentcost` int(11) NOT NULL,
  `sernum` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `invnum` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shtrihkod` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `os` tinyint(1) NOT NULL,
  `mode` tinyint(1) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `repair` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mapx` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `mapy` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `mapmoved` int(2) NOT NULL,
  `mapyet` tinyint(4) NOT NULL DEFAULT '0',
  `kntid` int(11) NOT NULL,
  `dtendgar` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=398 ;

-- --------------------------------------------------------

--
-- Структура таблицы `eq_param`
--

CREATE TABLE IF NOT EXISTS `eq_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grpid` int(11) NOT NULL,
  `paramid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `param` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=150 ;

-- --------------------------------------------------------

--
-- Структура таблицы `exp_log`
--

CREATE TABLE IF NOT EXISTS `exp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `TimeVal` datetime NOT NULL,
  `event` int(11) NOT NULL,
  `hozorgan` int(11) NOT NULL,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=221645 ;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `randomid` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `bpid` int(11) NOT NULL,
  `filename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Структура таблицы `files_contract`
--

CREATE TABLE IF NOT EXISTS `files_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcontract` int(11) NOT NULL,
  `filename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `userfreandlyfilename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=935 ;

-- --------------------------------------------------------

--
-- Структура таблицы `group_nome`
--

CREATE TABLE IF NOT EXISTS `group_nome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `group_nome`
--

INSERT INTO `group_nome` (`id`, `name`, `comment`, `active`) VALUES
(1, 'Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½Ñ‹/Ð¤Ð°ÐºÑÑ‹', '', 1),
(2, 'ÐœÐ¾Ð½Ð¸Ñ‚Ð¾Ñ€Ñ‹', '', 1),
(3, 'ÐšÐ°Ð»ÑŒÐºÑƒÐ»ÑÑ‚Ð¾Ñ€', '', 1),
(4, 'Ð¡Ð¸ÑÑ‚ÐµÐ¼Ð½Ñ‹Ð¹ Ð±Ð»Ð¾Ðº', '', 1),
(5, 'Ð¢Ð¾Ñ‡ÐºÐ° Ð´Ð¾ÑÑ‚ÑƒÐ¿Ð° WiFi', '', 1),
(6, 'ÐŸÑ€Ð¸Ð½Ñ‚ÐµÑ€Ñ‹', '', 1),
(7, 'Ð¡ÐºÐ°Ð½ÐµÑ€Ñ‹', '', 1),
(8, 'Ð”Ð¸ÐºÑ‚Ð¾Ñ„Ð¾Ð½Ñ‹', '', 1),
(9, 'Ð¤Ð¾Ñ‚Ð¾Ð°Ð¿Ð¿Ð°Ñ€Ð°Ñ‚Ñ‹', '', 1),
(10, 'Ð˜Ð‘ÐŸ', '', 1),
(12, 'ÐÐ¢Ð¡', '', 1),
(13, 'ÐœÐ°Ñ€ÑˆÑ€ÑƒÑ‚Ð¸Ð·Ð°Ñ‚Ð¾Ñ€Ñ‹', '', 1),
(14, 'Ð¢ÐµÐ»ÐµÐ²Ð¸Ð·Ð¾Ñ€Ñ‹', '', 1),
(15, 'ÐšÐ¾Ð¿Ð¸Ñ€Ñ‹', '', 1),
(16, 'ÐœÐµÐ±ÐµÐ»ÑŒ', '', 1),
(17, 'ÐŸÑ€Ð¾Ñ‡ÐµÐµ', '', 1),
(18, 'ÐŸÑ€Ð¾ÐµÐºÑ‚Ð¾Ñ€Ñ‹', '', 1),
(19, 'ÐšÐ°Ñ‚Ñ€Ð¸Ð´Ð¶Ð¸', '', 1),
(20, 'ÐœÑƒÐ»ÑŒÑ‚Ð¸Ð¼ÐµÐ´Ð¸Ð°', '', 1),
(21, 'ÐšÐ¾Ð½Ð´Ð¸Ñ†Ð¸Ð¾Ð½ÐµÑ€Ñ‹', '', 1),
(22, 'Ð¡ÐµÑ‚ÐµÐ²Ð¾Ðµ Ñ…Ñ€Ð°Ð½Ð¸Ð»Ð¸Ñ‰Ðµ', '', 1),
(23, 'Ð’Ð½ÐµÑˆÐ½Ð¸Ðµ HDD', '', 1),
(24, 'ÐŸÐ»Ð°Ð½ÑˆÐµÑ‚Ñ‹', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `group_param`
--

CREATE TABLE IF NOT EXISTS `group_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `group_param`
--

INSERT INTO `group_param` (`id`, `groupid`, `name`, `active`) VALUES
(22, 4, 'OS', 1),
(23, 4, 'RAM', 1),
(24, 4, 'HDD', 1),
(25, 4, 'Ð¦ÐŸ', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `knt`
--

CREATE TABLE IF NOT EXISTS `knt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fullname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ERPCode` int(11) NOT NULL,
  `INN` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `KPP` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `bayer` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `dog` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1008 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mailq`
--

CREATE TABLE IF NOT EXISTS `mailq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `btxt` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=226 ;

-- --------------------------------------------------------

--
-- Структура таблицы `move`
--

CREATE TABLE IF NOT EXISTS `move` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `orgidfrom` int(11) NOT NULL,
  `orgidto` int(11) NOT NULL,
  `placesidfrom` int(11) NOT NULL,
  `placesidto` int(11) NOT NULL,
  `useridfrom` int(11) NOT NULL,
  `useridto` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=779 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `stiker` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `dt`, `title`, `body`, `stiker`) VALUES
(26, '2014-03-04 00:00:00', 'Учет оргтехники в WEB 3.0', '<p>Добро пожаловать!</p>\n<p>Представляю вам демо ПО для учета оргтехники в небольшой организации. Ну и плюс еще несколько "плюшек".</p>\n<p>Вход для "теста: &nbsp;логин <strong>test</strong> пароль <strong>test</strong></p>\n<p>Отредакировать пользователя <strong>test</strong> и название организации.</p>', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `nome`
--

CREATE TABLE IF NOT EXISTS `nome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Структура таблицы `org`
--

CREATE TABLE IF NOT EXISTS `org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `picmap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `org`
--

INSERT INTO `org` (`id`, `name`, `picmap`, `active`) VALUES
(1, 'ÐžÐžÐž "Ð Ð¾Ð³Ð° Ð¸ ÐºÐ¾Ð¿Ñ‹Ñ‚Ð°"', '06716875881465578757.PNG', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `places`
--

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orgid` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Структура таблицы `places_users`
--

CREATE TABLE IF NOT EXISTS `places_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placesid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Структура таблицы `post_users`
--

CREATE TABLE IF NOT EXISTS `post_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL,
  `orgid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `post` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Структура таблицы `repair`
--

CREATE TABLE IF NOT EXISTS `repair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` date NOT NULL,
  `kntid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `cost` float NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `dtend` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `userfrom` int(11) NOT NULL,
  `userto` int(11) NOT NULL,
  `doc` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rss`
--

CREATE TABLE IF NOT EXISTS `rss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avtor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dt` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descc` text COLLATE utf8_unicode_ci NOT NULL,
  `generator` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=484 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `touserid` int(11) NOT NULL,
  `mainuseid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `txt` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `maxdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `randomid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `orgid` int(11) NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mode` int(11) NOT NULL,
  `lastdt` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=408 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `randomid`, `orgid`, `login`, `pass`, `email`, `mode`, `lastdt`, `active`) VALUES
(1, '534742080754244214882660638232114002258853163157700475856647', 1, 'test', 'test', 'donpadlo@gmail.com', 1, '2014-07-11 15:06:40', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_ori`
--

CREATE TABLE IF NOT EXISTS `users_ori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ori_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tabnumber` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `schedule` int(11) NOT NULL,
  `fio` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=256 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_profile`
--

CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usersid` int(11) NOT NULL,
  `fio` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `faza` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `enddate` date NOT NULL,
  `post` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `res1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `res2` int(100) NOT NULL,
  `res3` int(100) NOT NULL,
  `res4` datetime NOT NULL,
  `telephonenumber` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `homephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `jpegphoto` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=390 ;

--
-- Дамп данных таблицы `users_profile`
--

INSERT INTO `users_profile` (`id`, `usersid`, `fio`, `faza`, `code`, `enddate`, `post`, `res1`, `res2`, `res3`, `res4`, `telephonenumber`, `homephone`, `jpegphoto`) VALUES
(2, 1, 'Ð“Ñ€Ð¸Ð±Ð¾Ð² ÐŸÐ°Ð²ÐµÐ» Ð˜Ð³Ð¾Ñ€ÐµÐ²Ð¸Ñ‡', 'Ð Ð°Ð±Ð¾Ñ‚Ð°ÐµÑ‚', '88000280', '0001-01-01', 'ÐÐ°Ñ‡Ð°Ð»ÑŒÐ½Ð¸Ðº Ð¾Ñ‚Ð´ÐµÐ»Ð°', '115', 16, 0, '0000-00-00 00:00:00', '+79657400222', '+60222', '74344702731576526642.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `vendor`
--

INSERT INTO `vendor` (`id`, `name`, `comment`, `active`) VALUES
(1, 'Cisco', '', 1),
(2, 'Panasonic', '', 1),
(3, 'LG', '', 1),
(4, 'Citezen', '', 1),
(6, 'ÐÐµÐ¸Ð·Ð²ÐµÑÑ‚Ð½Ð¾', '', 1),
(8, 'E-machines', '', 1),
(9, 'HP', '', 1),
(39, 'Sony', '', 1),
(11, 'Xerox', '', 1),
(12, 'Acer', '', 1),
(13, 'Ubnt', '', 1),
(17, 'Canon', '', 1),
(16, 'Mustek', '', 1),
(18, 'Genius', '', 1),
(20, 'Epson', '', 1),
(21, 'ViewSonic', '', 1),
(22, 'MGE', '', 1),
(23, 'BENQ', '', 1),
(24, 'PLUS UPS Systems', '', 1),
(28, 'Bay Networks', '', 1),
(26, 'ICON', '', 1),
(29, 'HardLink', '', 1),
(30, 'Accorp', '', 1),
(31, 'Kyosera', '', 1),
(32, 'APC', '', 1),
(34, 'Metrologic', '', 1),
(35, 'Samsung', '', 1),
(36, 'Planet', '', 1),
(37, 'D-link', '', 1),
(38, 'Tandberg', '', 1),
(41, 'Sharp', '', 1),
(42, 'Asus', '', 1),
(43, 'TP-Link', '', 1),
(44, 'DataMax', '', 1),
(45, 'Logitech', '', 1),
(46, 'Philips', '', 1),
(47, 'QUATROCLIMAT', '', 1),
(48, '3 Com', '', 1),
(49, 'Fellowes', '', 1),
(50, 'Toshiba', '', 1),
(51, 'Western Digital', '', 1),
(52, 'FunkWerk', '', 1),
(53, 'Pascard Bell', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
