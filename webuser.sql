-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 30 2016 г., 12:31
-- Версия сервера: 5.6.31-0ubuntu0.15.10.1
-- Версия PHP: 5.6.11-1ubuntu3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bp_accept`
--

CREATE TABLE IF NOT EXISTS `bp_accept` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=574 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_userlist`
--

CREATE TABLE IF NOT EXISTS `bp_userlist` (
  `id` int(11) NOT NULL,
  `bpid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `dtstart` datetime NOT NULL,
  `comment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dtend` datetime NOT NULL,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2712 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_xml`
--

CREATE TABLE IF NOT EXISTS `bp_xml` (
  `id` int(11) NOT NULL,
  `ERPcode` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bodytxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `node` int(11) NOT NULL,
  `xml` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `step` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_xml_userlist`
--

CREATE TABLE IF NOT EXISTS `bp_xml_userlist` (
  `id` int(11) NOT NULL,
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
  `step` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=778 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `cloud_dirs`
--

CREATE TABLE IF NOT EXISTS `cloud_dirs` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `cloud_files`
--

CREATE TABLE IF NOT EXISTS `cloud_files` (
  `id` int(11) NOT NULL,
  `cloud_dirs_id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `filename` varchar(150) COLLATE utf8_bin NOT NULL,
  `dt` datetime NOT NULL,
  `sz` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL,
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
  `urlsite` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `ad`, `domain1`, `domain2`, `ldap`, `usercanregistrate`, `useraddfromad`, `theme`, `sitename`, `emailadmin`, `smtphost`, `smtpauth`, `smtpport`, `smtpusername`, `smtppass`, `emailreplyto`, `sendemail`, `version`, `urlsite`) VALUES
(1, 0, '', '', '', 1, 1, 'bootstrap', 'Учет ТМЦ в организации', '', '', 0, '25', '', '', '', 0, '3.83', 'http://localhost');

-- --------------------------------------------------------

--
-- Структура таблицы `config_common`
--

CREATE TABLE IF NOT EXISTS `config_common` (
  `id` int(11) NOT NULL,
  `nameparam` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `valueparam` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `config_common`
--

INSERT INTO `config_common` (`id`, `nameparam`, `valueparam`) VALUES
(37, 'smsdiffres', '3'),
(38, 'modulename_cloud', '1'),
(39, 'modulecomment_cloud', 'Хранилище документов'),
(40, 'modulecopy_cloud', 'Грибов Павел'),
(41, 'modulename_devicescontrol', '0'),
(42, 'modulecomment_devicescontrol', 'Управление устройствами'),
(43, 'modulecopy_devicescontrol', 'Грибов Павел'),
(44, 'modulename_cables', '0'),
(45, 'modulecomment_cables', 'Справочник кабелей и муфт'),
(46, 'modulecopy_cables', 'Грибов Павел'),
(47, 'modulename_smscenter', '0'),
(48, 'modulecomment_smscenter', 'СМС-Центр'),
(49, 'modulecopy_smscenter', 'Грибов Павел'),
(50, 'modulename_zabbix-mon', '0'),
(51, 'modulecomment_zabbix-mon', 'Мониторинг dashboard серверов Zabbix'),
(52, 'modulecopy_zabbix-mon', 'Грибов Павел'),
(53, 'modulename_whoonline', '1'),
(54, 'modulecomment_whoonline', 'Кто на сайте?'),
(55, 'modulecopy_whoonline', 'Грибов Павел'),
(56, 'modulename_commits-widget', '1'),
(57, 'modulecomment_commits-widget', 'Виджет разработки на github.com на главной странице'),
(58, 'modulecopy_commits-widget', 'Солодягин Сергей'),
(59, 'modulename_worktime', '0'),
(60, 'modulecomment_worktime', 'Вход и выход работников организации (турникет Орион)'),
(61, 'modulecopy_worktime', 'Грибов Павел'),
(62, 'modulename_ping', '1'),
(63, 'modulecomment_ping', 'Проверка доступности ТМЦ по ping'),
(64, 'modulecopy_ping', 'Грибов Павел'),
(65, 'modulename_astra', '0'),
(66, 'modulecomment_astra', 'Управление серверами Astra'),
(67, 'modulecopy_astra', 'Грибов Павел'),
(68, 'modulename_bprocess', '0'),
(69, 'modulecomment_bprocess', 'Бизнес-процессы'),
(70, 'modulecopy_bprocess', 'Грибов Павел'),
(71, 'modulename_workandplans', '0'),
(72, 'modulecomment_workandplans', 'Оперативная обстановка на заводе'),
(73, 'modulecopy_workandplans', 'Грибов Павел'),
(74, 'modulename_workmen', '1'),
(75, 'modulecomment_workmen', 'Менеджер по обслуживанию '),
(76, 'modulecopy_workmen', 'Грибов Павел'),
(77, 'modulename_news', '1'),
(78, 'modulecomment_news', 'Модуль новостей'),
(79, 'modulecopy_news', 'Грибов Павел'),
(80, 'modulename_stiknews', '1'),
(81, 'modulecomment_stiknews', 'Закрепленные новости'),
(82, 'modulecopy_stiknews', 'Грибов Павел'),
(83, 'modulename_lastmoved', '1'),
(84, 'modulecomment_lastmoved', 'Последние перемещения ТМЦ'),
(85, 'modulecopy_lastmoved', 'Грибов Павел'),
(86, 'modulename_usersfaze', '0'),
(87, 'modulecomment_usersfaze', 'Где сотрудник?'),
(88, 'modulecopy_usersfaze', 'Грибов Павел'),
(89, 'modulename_ical', '0'),
(90, 'modulecomment_ical', 'Календарь'),
(91, 'modulecopy_ical', 'Грибов Павел'),
(92, 'modulename_tasks', '0'),
(93, 'modulecomment_tasks', 'Задачи'),
(94, 'modulecopy_tasks', 'Грибов Павел'),
(95, 'modulename_dop-pol', '0'),
(96, 'modulecomment_dop-pol', 'Справочник дополнительных полей'),
(97, 'modulecopy_dop-pol', 'Грибов Павел'),
(98, 'modulename_scriptalert', '0'),
(99, 'modulecomment_scriptalert', 'Мониторинг выполнения скриптов'),
(100, 'modulecopy_scriptalert', 'Грибов Павел'),
(101, 'modulename_chat', '0'),
(102, 'modulecomment_chat', 'Чат поддержки/Общий чат'),
(103, 'modulecopy_chat', 'Грибов Павел'),
(104, 'modulename_htmlentry', '0'),
(105, 'modulecomment_htmlentry', 'Произвольный html код на странице перед футером'),
(106, 'modulecopy_htmlentry', 'Грибов Павел');

-- --------------------------------------------------------

--
-- Структура таблицы `contract`
--

CREATE TABLE IF NOT EXISTS `contract` (
  `id` int(11) NOT NULL,
  `kntid` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datestart` date NOT NULL,
  `dateend` date NOT NULL,
  `work` int(11) NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `num` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `devgroups`
--

CREATE TABLE IF NOT EXISTS `devgroups` (
  `id` int(11) NOT NULL,
  `dgname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dcomment` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL,
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
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `devnames`
--

CREATE TABLE IF NOT EXISTS `devnames` (
  `id` int(11) NOT NULL,
  `dname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `command` text COLLATE utf8_bin,
  `devid` int(11) DEFAULT NULL,
  `bcolor` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `entropia`
--

CREATE TABLE IF NOT EXISTS `entropia` (
  `cnt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `entropia`
--

INSERT INTO `entropia` (`cnt`) VALUES
(0);

-- --------------------------------------------------------

--
-- Структура таблицы `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(11) NOT NULL,
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
  `tmcgo` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `eq_param`
--

CREATE TABLE IF NOT EXISTS `eq_param` (
  `id` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `paramid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `param` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `exp_log`
--

CREATE TABLE IF NOT EXISTS `exp_log` (
  `id` int(11) NOT NULL,
  `guid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TimeVal` datetime NOT NULL,
  `event` int(11) NOT NULL,
  `hozorgan` int(11) NOT NULL,
  `mode` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=221645 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL,
  `randomid` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bpid` int(11) NOT NULL,
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `files_contract`
--

CREATE TABLE IF NOT EXISTS `files_contract` (
  `id` int(11) NOT NULL,
  `idcontract` int(11) NOT NULL,
  `filename` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userfreandlyfilename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=935 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `group_nome`
--

CREATE TABLE IF NOT EXISTS `group_nome` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `group_nome`
--

INSERT INTO `group_nome` (`id`, `name`, `comment`, `active`) VALUES
(1, 'Мониторы', '', 1),
(2, 'ИБП', '', 1),
(3, 'Роутеры/Маршрутизаторы/Свичи', '', 1),
(4, 'Системные блоки', '', 1),
(5, 'Принтера', '', 1),
(6, 'Столы', '', 1),
(7, 'Стулья', '', 1),
(8, 'Телевизоры', '', 1),
(9, 'Чайники', '', 1),
(10, 'Мышки', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `group_param`
--

CREATE TABLE IF NOT EXISTS `group_param` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

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
-- Структура таблицы `jqcalendar`
--

CREATE TABLE IF NOT EXISTS `jqcalendar` (
  `Id` int(11) NOT NULL,
  `Subject` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `Location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `IsAllDayEvent` smallint(6) NOT NULL,
  `Color` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `RecurringRule` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `uidview` varchar(10) COLLATE utf8_bin NOT NULL,
  `lbid` varchar(12) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `knt`
--

CREATE TABLE IF NOT EXISTS `knt` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fullname` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ERPCode` int(11) NOT NULL,
  `INN` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `KPP` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bayer` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `dog` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `knt`
--

INSERT INTO `knt` (`id`, `name`, `comment`, `active`, `fullname`, `ERPCode`, `INN`, `KPP`, `bayer`, `supplier`, `dog`) VALUES
(1008, 'Основной контрагент', '', 1, '', 0, '352501001', '352501001', 0, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `lib_cable_lines`
--

CREATE TABLE IF NOT EXISTS `lib_cable_lines` (
  `id` int(11) NOT NULL,
  `id_calble_module` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `color1` varchar(100) CHARACTER SET utf8 NOT NULL,
  `color2` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_cable_modules`
--

CREATE TABLE IF NOT EXISTS `lib_cable_modules` (
  `id` int(11) NOT NULL,
  `cable_id` int(11) NOT NULL,
  `number` varchar(11) CHARACTER SET utf8 NOT NULL,
  `color` varchar(100) CHARACTER SET utf8 NOT NULL,
  `color1` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_cable_muft`
--

CREATE TABLE IF NOT EXISTS `lib_cable_muft` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_cable_name_mark`
--

CREATE TABLE IF NOT EXISTS `lib_cable_name_mark` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mark` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_cable_spliter`
--

CREATE TABLE IF NOT EXISTS `lib_cable_spliter` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `exitcount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_lines_in_muft`
--

CREATE TABLE IF NOT EXISTS `lib_lines_in_muft` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор волокна в муфте на карте',
  `mufta_id` int(11) NOT NULL COMMENT 'Идентификатор муфты на карте',
  `obj_edit_id` int(11) NOT NULL COMMENT 'Идентификатор кабеля на карте',
  `lib_line_id` int(11) NOT NULL COMMENT 'ссылка на волокно из справочника',
  `start_id` int(11) NOT NULL COMMENT 'идентификатор стыковки начала волокна',
  `end_id` int(11) NOT NULL COMMENT 'идентификатор конца волокна',
  `type_obj` varchar(20) CHARACTER SET utf8 NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `mailq`
--

CREATE TABLE IF NOT EXISTS `mailq` (
  `id` int(11) NOT NULL,
  `from` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `btxt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL COMMENT 'Уникальный идентификатор',
  `parents` int(11) NOT NULL COMMENT 'Родитель',
  `sort_id` int(11) NOT NULL COMMENT 'Сортировка',
  `name` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT 'Название',
  `comment` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT 'Пояснение',
  `uid` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'некий идентификатор (можно использовать для автосоздания менюшек)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `move`
--

CREATE TABLE IF NOT EXISTS `move` (
  `id` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `orgidfrom` int(11) NOT NULL,
  `orgidto` int(11) NOT NULL,
  `placesidfrom` int(11) NOT NULL,
  `placesidto` int(11) NOT NULL,
  `useridfrom` int(11) NOT NULL,
  `useridto` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=779 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `stiker` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `dt`, `title`, `body`, `stiker`) VALUES
(26, '2017-03-02 00:00:00', 'Учет оргтехники в WEB 3.xx', '<p>Добро пожаловать!</p><p>Представляю вам демо ПО для учета оргтехники в небольшой организации. Ну и плюс еще несколько "плюшек".</p><p>Домашняя страница проекта:&nbsp;<a href="http://xn--90acbu5aj5f.xn--p1ai/?page_id=1202">http://грибовы.рф</a></p><p>Контакты: skype: pvtuning </p>', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `nome`
--

CREATE TABLE IF NOT EXISTS `nome` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `nome`
--

INSERT INTO `nome` (`id`, `groupid`, `vendorid`, `name`, `active`) VALUES
(151, 4, 6, 'Системный блок Лидер-1', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `org`
--

CREATE TABLE IF NOT EXISTS `org` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `picmap` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `org`
--

INSERT INTO `org` (`id`, `name`, `picmap`, `active`) VALUES
(1, 'ООО Рога и Копыта', '06716875881465578757.PNG', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `places`
--

CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL,
  `orgid` int(11) NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `opgroup` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `places`
--

INSERT INTO `places` (`id`, `orgid`, `name`, `comment`, `active`, `opgroup`) VALUES
(46, 1, 'Серверная', '', 1, 'АСУ');

-- --------------------------------------------------------

--
-- Структура таблицы `places_users`
--

CREATE TABLE IF NOT EXISTS `places_users` (
  `id` int(11) NOT NULL,
  `placesid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `places_users`
--

INSERT INTO `places_users` (`id`, `placesid`, `userid`) VALUES
(91, 46, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `post_users`
--

CREATE TABLE IF NOT EXISTS `post_users` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `orgid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `post` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `repair`
--

CREATE TABLE IF NOT EXISTS `repair` (
  `id` int(11) NOT NULL,
  `dt` date NOT NULL,
  `kntid` int(11) NOT NULL,
  `eqid` int(11) NOT NULL,
  `cost` float NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dtend` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `userfrom` int(11) NOT NULL,
  `userto` int(11) NOT NULL,
  `doc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `rss`
--

CREATE TABLE IF NOT EXISTS `rss` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avtor` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dt` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `generator` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=484 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `smslist`
--

CREATE TABLE IF NOT EXISTS `smslist` (
  `id` int(11) NOT NULL,
  `mobile` varchar(20) CHARACTER SET utf8 NOT NULL,
  `smstxt` text CHARACTER SET utf8 NOT NULL,
  `status` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `smsstat`
--

CREATE TABLE IF NOT EXISTS `smsstat` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `countok` int(10) NOT NULL,
  `countfail` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `sms_center_config`
--

CREATE TABLE IF NOT EXISTS `sms_center_config` (
  `id` int(11) NOT NULL,
  `agname` varchar(50) COLLATE utf8_bin NOT NULL,
  `smslogin` varchar(50) COLLATE utf8_bin NOT NULL,
  `smspass` varchar(50) COLLATE utf8_bin NOT NULL,
  `fileagent` varchar(50) COLLATE utf8_bin NOT NULL,
  `smsdiff` varchar(10) COLLATE utf8_bin NOT NULL,
  `sel` varchar(10) COLLATE utf8_bin NOT NULL,
  `sender` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `touserid` int(11) NOT NULL,
  `mainuseid` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `txt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `maxdate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `randomid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `orgid` int(11) NOT NULL,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mode` int(11) NOT NULL,
  `lastdt` datetime NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=408 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `randomid`, `orgid`, `login`, `password`, `salt`, `email`, `mode`, `lastdt`, `active`) VALUES
(1, '534742080754244214882660638232114002258853163157700475856647', 1, 'admin', 'c37738f04d21a5e461f36dbfae696265e3ea63e1', '=Oj(xazq', 'test@gmail.com', 1, '2016-12-30 12:28:44', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `usersroles`
--

CREATE TABLE IF NOT EXISTS `usersroles` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `usersroles`
--

INSERT INTO `usersroles` (`id`, `userid`, `role`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_ori`
--

CREATE TABLE IF NOT EXISTS `users_ori` (
  `id` int(11) NOT NULL,
  `ori_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tabnumber` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `schedule` int(11) NOT NULL,
  `fio` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `users_profile`
--

CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(11) NOT NULL,
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
  `jpegphoto` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=390 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `users_profile`
--

INSERT INTO `users_profile` (`id`, `usersid`, `fio`, `faza`, `code`, `enddate`, `post`, `res1`, `res2`, `res3`, `res4`, `telephonenumber`, `homephone`, `jpegphoto`) VALUES
(2, 1, 'Администратор системы', 'Абырвалг', '88000280', '0001-01-01', 'Начальник', '115', 16, 0, '0000-00-00 00:00:00', '+79657400222', '+60222', 'noimage.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(155) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `vendor`
--

INSERT INTO `vendor` (`id`, `name`, `comment`, `active`) VALUES
(1, 'Cisco', '', 1),
(2, 'Panasonic', '', 1),
(3, 'LG', '', 1),
(4, 'Citezen', '', 1),
(6, 'Сборка', '', 1),
(8, 'E-machines', '', 1),
(9, 'HP', '', 1),
(11, 'Xerox', '', 1),
(12, 'Acer', '', 1),
(13, 'Ubnt', '', 1),
(16, 'Mustek', '', 1),
(17, 'Canon', '', 1),
(18, 'Genius', '', 1),
(20, 'Epson', '', 1),
(21, 'ViewSonic', '', 1),
(22, 'MGE', '', 1),
(23, 'BENQ', '', 1),
(24, 'PLUS UPS Systems', '', 1),
(26, 'ICON', '', 1),
(28, 'Bay Networks', '', 1),
(29, 'HardLink', '', 1),
(30, 'Accorp', '', 1),
(31, 'Kyosera', '', 1),
(32, 'APC', '', 1),
(34, 'Metrologic', '', 1),
(35, 'Samsung', '', 1),
(36, 'Planet', '', 1),
(37, 'D-link', '', 1),
(38, 'Tandberg', '', 1),
(39, 'Sony', '', 1),
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

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bp_accept`
--
ALTER TABLE `bp_accept`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_userlist`
--
ALTER TABLE `bp_userlist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_xml`
--
ALTER TABLE `bp_xml`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_xml_userlist`
--
ALTER TABLE `bp_xml_userlist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cloud_dirs`
--
ALTER TABLE `cloud_dirs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cloud_files`
--
ALTER TABLE `cloud_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `config_common`
--
ALTER TABLE `config_common`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `devgroups`
--
ALTER TABLE `devgroups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `devnames`
--
ALTER TABLE `devnames`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `eq_param`
--
ALTER TABLE `eq_param`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `exp_log`
--
ALTER TABLE `exp_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guid` (`guid`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files_contract`
--
ALTER TABLE `files_contract`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `group_nome`
--
ALTER TABLE `group_nome`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `group_param`
--
ALTER TABLE `group_param`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jqcalendar`
--
ALTER TABLE `jqcalendar`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `knt`
--
ALTER TABLE `knt`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_cable_lines`
--
ALTER TABLE `lib_cable_lines`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_cable_modules`
--
ALTER TABLE `lib_cable_modules`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_cable_muft`
--
ALTER TABLE `lib_cable_muft`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_cable_name_mark`
--
ALTER TABLE `lib_cable_name_mark`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_cable_spliter`
--
ALTER TABLE `lib_cable_spliter`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lib_lines_in_muft`
--
ALTER TABLE `lib_lines_in_muft`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mailq`
--
ALTER TABLE `mailq`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `move`
--
ALTER TABLE `move`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `nome`
--
ALTER TABLE `nome`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `org`
--
ALTER TABLE `org`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `places_users`
--
ALTER TABLE `places_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `post_users`
--
ALTER TABLE `post_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rss`
--
ALTER TABLE `rss`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `smslist`
--
ALTER TABLE `smslist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `smsstat`
--
ALTER TABLE `smsstat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sms_center_config`
--
ALTER TABLE `sms_center_config`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `usersroles`
--
ALTER TABLE `usersroles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_ori`
--
ALTER TABLE `users_ori`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_profile`
--
ALTER TABLE `users_profile`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bp_accept`
--
ALTER TABLE `bp_accept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=574;
--
-- AUTO_INCREMENT для таблицы `bp_userlist`
--
ALTER TABLE `bp_userlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2712;
--
-- AUTO_INCREMENT для таблицы `bp_xml`
--
ALTER TABLE `bp_xml`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=328;
--
-- AUTO_INCREMENT для таблицы `bp_xml_userlist`
--
ALTER TABLE `bp_xml_userlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=778;
--
-- AUTO_INCREMENT для таблицы `cloud_dirs`
--
ALTER TABLE `cloud_dirs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cloud_files`
--
ALTER TABLE `cloud_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `config_common`
--
ALTER TABLE `config_common`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT для таблицы `contract`
--
ALTER TABLE `contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=424;
--
-- AUTO_INCREMENT для таблицы `devgroups`
--
ALTER TABLE `devgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `devnames`
--
ALTER TABLE `devnames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=398;
--
-- AUTO_INCREMENT для таблицы `eq_param`
--
ALTER TABLE `eq_param`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=150;
--
-- AUTO_INCREMENT для таблицы `exp_log`
--
ALTER TABLE `exp_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=221645;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `files_contract`
--
ALTER TABLE `files_contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=935;
--
-- AUTO_INCREMENT для таблицы `group_nome`
--
ALTER TABLE `group_nome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `group_param`
--
ALTER TABLE `group_param`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT для таблицы `jqcalendar`
--
ALTER TABLE `jqcalendar`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `knt`
--
ALTER TABLE `knt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1009;
--
-- AUTO_INCREMENT для таблицы `lib_cable_lines`
--
ALTER TABLE `lib_cable_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `lib_cable_modules`
--
ALTER TABLE `lib_cable_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `lib_cable_muft`
--
ALTER TABLE `lib_cable_muft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `lib_cable_name_mark`
--
ALTER TABLE `lib_cable_name_mark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `lib_cable_spliter`
--
ALTER TABLE `lib_cable_spliter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `lib_lines_in_muft`
--
ALTER TABLE `lib_lines_in_muft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор волокна в муфте на карте';
--
-- AUTO_INCREMENT для таблицы `mailq`
--
ALTER TABLE `mailq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=226;
--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор';
--
-- AUTO_INCREMENT для таблицы `move`
--
ALTER TABLE `move`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=779;
--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT для таблицы `nome`
--
ALTER TABLE `nome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT для таблицы `org`
--
ALTER TABLE `org`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT для таблицы `places_users`
--
ALTER TABLE `places_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT для таблицы `post_users`
--
ALTER TABLE `post_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `repair`
--
ALTER TABLE `repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `rss`
--
ALTER TABLE `rss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=484;
--
-- AUTO_INCREMENT для таблицы `smslist`
--
ALTER TABLE `smslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `smsstat`
--
ALTER TABLE `smsstat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `sms_center_config`
--
ALTER TABLE `sms_center_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=408;
--
-- AUTO_INCREMENT для таблицы `usersroles`
--
ALTER TABLE `usersroles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `users_ori`
--
ALTER TABLE `users_ori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=256;
--
-- AUTO_INCREMENT для таблицы `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=390;
--
-- AUTO_INCREMENT для таблицы `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `users_quick_menu` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(255) NOT NULL , `url` VARCHAR(255) NOT NULL , `userid` INT NOT NULL , PRIMARY KEY (`id`));

ALTER TABLE `users_quick_menu` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `users_quick_menu` CHANGE `url` `url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `users_quick_menu` ADD `ico` VARCHAR(255) NOT NULL AFTER `userid`;

CREATE TABLE `register` ( `id` INT NOT NULL AUTO_INCREMENT , `dt` INT NOT NULL , `eqid` INT NOT NULL , `moveid` INT NOT NULL , `cnt` INT NOT NULL , PRIMARY KEY (`id`));

ALTER TABLE `register` CHANGE `moveid` `moveid` INT(11) NULL;
ALTER TABLE `register` ADD `orgid` INT NOT NULL AFTER `cnt`, ADD `placesid` INT NOT NULL AFTER `orgid`, ADD `usersid` INT NOT NULL AFTER `placesid`;
ALTER TABLE `register` CHANGE `dt` `dt` DATETIME(6) NOT NULL;
