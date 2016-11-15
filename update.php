<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

define('WUO_ROOT', dirname(__FILE__));
?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Учет ТМЦ в организации и другие плюшки">
	<meta name="author" content="(c) 2011-2015 by Gribov Pavel">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Учет оргтехники в организации</title>
	<meta name="generator" content="yarus">
	<link href="favicon.ico" type="image/ico" rel="icon">
	<link href="favicon.ico" type="image/ico" rel="shortcut icon">
</head>
<body>
<?php
include_once(WUO_ROOT.'/config.php'); // загружаем первоначальные настройки
// загружаем классы
include_once(WUO_ROOT.'/class/sql.php'); // загружаем классы работы с БД
include_once(WUO_ROOT.'/class/config.php'); // загружаем классы настроек
// загружаем все что нужно для работы движка
include_once(WUO_ROOT.'/inc/connect.php'); // соединяемся с БД, получаем $mysql_base_id
include_once(WUO_ROOT.'/inc/config.php'); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once(WUO_ROOT.'/inc/functions.php'); // загружаем функции

/**
 * Обёртка для запроса к базе
 * @global type $sqlcn
 * @param string $log Текст лога
 * @param string $sql Запрос
 * @param string $qnum Номер запроса для лога
 */
function ExecSQL($log, $sql, $qnum) {
	global $sqlcn;
	echo $log.'<br>';
	$sqlcn->ExecuteSQL($sql)
			or die("($qnum)Не удалось обновить БД по причине: ".mysqli_error($sqlcn->idsqlconnection));
	echo '--ok<br>';
}

/**
 * Обёртка для обновления нумерации движка
 * @global type $sqlcn
 * @global type $cfg
 * @param string $vr Версия движка
 * @param string $qnum Номер запроса для лога
 */
function UpdateVer($vr, $qnum) {
	global $sqlcn, $cfg;
	echo "-обновляю нумерацию версии движка до $vr<br>";
	$sqlcn->ExecuteSQL("UPDATE config SET version='$vr'")
			or die("($qnum)Не удалось обновить БД по причине: ".mysqli_error($sqlcn->idsqlconnection));
	echo '--ok<br>';
	$cfg->version = $vr;
}

echo 'Обновление начато.<br>';

// обновляем до 3.01
if (($cfg->version == '1.05') or ($cfg->version == '3.0')) {
	$vr = '3.01';
	$log = '-добавляю в таблице equipment поля kntid (контрагент-поставщик) и dtendgar (дата окончания гарантии на ТМЦ)';
	$sql = 'ALTER TABLE `equipment` ADD `kntid` INT NOT NULL AFTER `mapyet`, ADD `dtendgar` DATE NOT NULL AFTER `kntid`';
	ExecSQL($log, $sql, '1');
	UpdateVer($vr, '2');
}

// обновляем до 3.02
if ($cfg->version == '3.01') {
	$vr = '3.02';
	$log = '-добавляю в таблице tasks поле mainuseid (руководитель исполнителя)';
	$sql = 'ALTER TABLE `tasks` ADD `mainuseid` INT NOT NULL AFTER `touserid`';
	ExecSQL($log, $sql, '3');
	UpdateVer($vr, '4');
}

// обновляем до 3.03
if ($cfg->version == '3.02') {
	$vr = '3.03';
	$log = '-добавляю в таблице repair дополнительные поля';
	$sql = 'ALTER TABLE `repair` ADD `userfrom` INT NOT NULL, ADD `userto` INT NOT NULL, ADD `doc` TEXT NOT NULL';
	ExecSQL($log, $sql, '5');
	$log = '-изменяю в таблице repair тип поля cost на float';
	$sql = 'ALTER TABLE `repair` CHANGE `cost` `cost` FLOAT(11) NOT NULL';
	ExecSQL($log, $sql, '5.1');
	UpdateVer($vr, '6');
}

// обновляем до 3.04
if ($cfg->version == '3.03') {
	$vr = '3.04';
	$log = '-add table devgroups';
	$sql = 'CREATE TABLE devgroups (id INT(11) AUTO_INCREMENT, dgname VARCHAR(255), dcomment VARCHAR(255), PRIMARY KEY(id))';
	ExecSQL($log, $sql, '7');
	$log = '-add table devnames';
	$sql = 'CREATE TABLE devnames (id INT(11) AUTO_INCREMENT, dname VARCHAR(255), command TEXT, PRIMARY KEY(id))';
	ExecSQL($log, $sql, '8');
	$log = '-modify table devnames';
	$sql = 'ALTER TABLE devnames ADD devid INT(11)';
	ExecSQL($log, $sql, '8.1');
	$log = '-добавляю таблицу настройки LanBilling';
	$sql = 'CREATE TABLE lbcfg (
		id INT(11) AUTO_INCREMENT,
		sname VARCHAR(255),
		host VARCHAR(255),
		basename VARCHAR(255),
		username VARCHAR(255),
		pass VARCHAR(255),
		PRIMARY KEY(id)
	)';
	ExecSQL($log, $sql, '8.2');
	$log = '-добавляю таблицу расчетов предварительных платежей LanBilling';
	$sql = 'CREATE TABLE lanbpredplat (
		id INT(11) AUTO_INCREMENT,
		number VARCHAR(50),
		username VARCHAR(50),
		address VARCHAR(250),
		phone VARCHAR(20),
		balance DOUBLE,
		blocked TINYINT,
		am_inet DOUBLE,
		am_tv DOUBLE,
		am_usl DOUBLE,
		recount DOUBLE,
		grp DOUBLE,
		tarifs VARCHAR(255),
		dt DATETIME,
		PRIMARY KEY(id)
	)';
	ExecSQL($log, $sql, '8.3');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD afterrecount DOUBLE';
	ExecSQL($log, $sql, '8.4');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD blibaseid TINYINT';
	ExecSQL($log, $sql, '8.5');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD uid VARCHAR(20)';
	ExecSQL($log, $sql, '8.6');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD vg_id VARCHAR(20)';
	ExecSQL($log, $sql, '8.7');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD login VARCHAR(20)';
	ExecSQL($log, $sql, '8.8');
	$log = '-модифицирую таблицу расчетов предварительных платежей LanBilling';
	$sql = 'ALTER TABLE lanbpredplat ADD agrm_id VARCHAR(20)';
	ExecSQL($log, $sql, '8.9');
	UpdateVer($vr, '9');
}

// обновляем до 3.05
if ($cfg->version == '3.04') {
	$vr = '3.05';
	$log = '-меняю структуру таблицы lanbpredplat';
	$sql = 'ALTER TABLE `lanbpredplat` ADD `smssend` VARCHAR(50) NOT NULL';
	ExecSQL($log, $sql, '10');
	$log = '-добавляю таблицу шаблонов СМС для LanBilling';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbsmstempl` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibase` INT(11) NOT NULL,
		`typetmp` INT(11) NOT NULL,
		`txt` TEXT NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
	ExecSQL($log, $sql, '10.1');
	UpdateVer($vr, '11');
}

// обновляем до 3.06
if ($cfg->version == '3.05') {
	$vr = '3.06';
	$log = '- добавляем статистику по отправке СМС';
	$sql = 'CREATE TABLE IF NOT EXISTS `smsstat` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`phone` VARCHAR(20) NOT NULL,
		`countok` INT(10) NOT NULL,
		`countfail` INT(10) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '12');
	UpdateVer($vr, '13');
}

// обновляем до 3.07
if ($cfg->version == '3.06') {
	$vr = '3.07';
	$log = '- добавляем значение порога отправки СМС';
	$sql = "INSERT INTO config_common (id, nameparam, `valueparam`) VALUES (NULL, 'smsdiffres', '3')";
	ExecSQL($log, $sql, '14');
	UpdateVer($vr, '15');
}

// обновляем до 3.08
if ($cfg->version == '3.07') {
	$vr = '3.08';
	$log = '- добавляем таблицу для ограничения тарифов на шейпере';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbshapbytarifs` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibase` INT(11) NOT NULL,
		`tar_id` INT(11) NOT NULL,
		`tarname` VARCHAR(255) NOT NULL,
		`cost` INT(11) NOT NULL,
		`fixshape` INT(11) NOT NULL,
		`maxspeed` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '16');
	UpdateVer($vr, '17');
}

// обновляем до 3.09
if ($cfg->version == '3.08') {
	$vr = '3.09';
	$log = '- модифицируем ограничения тарифов на шейпере';
	$sql = 'ALTER TABLE `lanbshapbytarifs` ADD `cntusers` INT(10) NOT NULL, ADD `cntblocked` INT(10) NOT NULL';
	ExecSQL($log, $sql, '18');
	$log = '- модифицируем ограничения тарифов на шейпере';
	$sql = 'ALTER TABLE `lanbshapbytarifs` ADD `used` INT NOT NULL';
	ExecSQL($log, $sql, '18');
	UpdateVer($vr, '19');
}

// обновляем до 3.10
if ($cfg->version == '3.09') {
	$vr = '3.10';
	$log = '- добавляем таблицу устройств (свичи, коммутаторы и т.п.';
	$sql = 'CREATE TABLE IF NOT EXISTS `devices` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`idbase` INT(11) NOT NULL,
		`devname` VARCHAR(255) NOT NULL,
		`whereis` INT(11) NOT NULL,
		`address` VARCHAR(255) NOT NULL,
		`param_name` VARCHAR(255) NOT NULL,
		`param_value` VARCHAR(255) NOT NULL,
		`cnt` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '21');
	UpdateVer($vr, '22');
}

// обновляем до 3.11
if ($cfg->version == '3.10') {
	$vr = '3.11';
	$log = '- добавляем таблицу устройств (свичи, коммутаторы и т.п.)';
	$sql = 'ALTER TABLE `devices` ADD `devid` INT NOT NULL';
	ExecSQL($log, $sql, '23');
	UpdateVer($vr, '24');
}

// обновляем до 3.12
if ($cfg->version == '3.11') {
	$vr = '3.12';
	$log = '- добавляем таблицу устройств (свичи, коммутаторы и т.п.)';
	$sql = 'ALTER TABLE `devices` CHANGE `whereis` `whereis` VARCHAR(255) NOT NULL';
	ExecSQL($log, $sql, '25');
	UpdateVer($vr, '26');
}

// обновляем до 3.13
if ($cfg->version == '3.12') {
	$vr = '3.13';
	$log = '- добавляем таблицу устройств (свичи, коммутаторы и т.п.)';
	$sql = 'ALTER TABLE `devices` ADD `stamp` TIMESTAMP NOT NULL';
	ExecSQL($log, $sql, '27');
	UpdateVer($vr, '28');
}

// обновляем до 3.14
if ($cfg->version == '3.13') {
	$vr = '3.14';
	$log = '- добавляем таблицу банков для биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbbanks` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibaseid` INT(11) NOT NULL,
		`name` VARCHAR(200) NOT NULL,
		`inmail` VARCHAR(150) NOT NULL,
		`outmail` VARCHAR(150) NOT NULL,
		`manager_id` INT(11) NOT NULL,
		`dir_name` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '29');
	UpdateVer($vr, '30');
}

// обновляем до 3.15
if ($cfg->version == '3.14') {
	$vr = '3.15';
	$log = '- добавляем таблицу фильтров-договоров биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbfileterdog` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibase` INT(11) NOT NULL,
		`filterdog` VARCHAR(100) NOT NULL,
		`email` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '31');
	UpdateVer($vr, '32');
}

// обновляем до 3.16
if ($cfg->version == '3.15') {
	$vr = '3.16';
	$log = '- изменяю структуру таблицы банков';
	$sql = 'ALTER TABLE `lanbbanks` ADD `codecl` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '33');
	UpdateVer($vr, '34');
}

// обновляем до 3.17
if ($cfg->version == '3.16') {
	$vr = '3.17';
	$log = '- добавляю таблицу Логов';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanblog` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`loglevel` INT(11) NOT NULL,
		`dt` DATETIME NOT NULL,
		`txt` TEXT NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '35');
	UpdateVer($vr, '36');
}

// обновляем до 3.18
if ($cfg->version == '3.17') {
	$vr = '3.18';
	$log = '- добавляю таблицу Логов';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanblog` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`loglevel` INT(11) NOT NULL,
		`dt` DATETIME NOT NULL,
		`txt` TEXT NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '37');
	UpdateVer($vr, '38');
}

// обновляем до 3.19
if ($cfg->version == '3.18') {
	$vr = '3.19';
	$log = '- добавляю таблицу Логов';
	$sql = 'ALTER TABLE `lanbbanks` ADD `codeusl` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '39');
	UpdateVer($vr, '40');
}

// обновляем до 3.20
if ($cfg->version == '3.19') {
	$vr = '3.20';
	$log = '- меняю тип хранения dir_name (банки)';
	$sql = 'ALTER TABLE `lanbbanks` CHANGE `dir_name` `dir_name` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '41');
	UpdateVer($vr, '42');
}

// обновляем до 3.21
if ($cfg->version == '3.20') {
	$vr = '3.21';
	$log = '- добавляю таблицу серверов для настройки шейперов';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbshaperservers` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`sname` VARCHAR(200) NOT NULL,
		`comment` VARCHAR(200) NOT NULL,
		`blibaseid` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '43');
	$log = '- добавляю таблицу настроек серверов для настройки шейперов';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbshconfig` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibaseid` INT(11) NOT NULL,
		`idsh` INT(11) NOT NULL,
		`option82` VARCHAR(100) NOT NULL,
		`radius` VARCHAR(100) NOT NULL,
		`wcanal` VARCHAR(100) NOT NULL,
		`maxw` VARCHAR(100) NOT NULL,
		`minw` VARCHAR(100) NOT NULL,
		`ferma` VARCHAR(100) NOT NULL,
		`segment_id` VARCHAR(100) NOT NULL,
		`radiusip` VARCHAR(100) NOT NULL,
		`ent` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '44');
	UpdateVer($vr, '45');
}

// обновляем до 3.22
if ($cfg->version == '3.21') {
	$vr = '3.22';
	$log = '- обновляю таблицу настройки шейперов';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `bhost` VARCHAR(100) NOT NULL AFTER `ent`,
ADD `bname` VARCHAR(100) NOT NULL AFTER `bhost`,
ADD `buser` VARCHAR(100) NOT NULL AFTER `bname`,
ADD `bpass` VARCHAR(100) NOT NULL AFTER `buser`';
	ExecSQL($log, $sql, '46');
	UpdateVer($vr, '47');
}

// обновляем до 3.23
if ($cfg->version == '3.22') {
	$vr = '3.23';
	$log = '- добавляю таблицу хранения значений шейпера';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_sp_graf` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibase` INT(11) NOT NULL,
		`ferma` INT(11) NOT NULL,
		`server` INT(11) NOT NULL,
		`maxw` INT(11) NOT NULL,
		`minw` INT(11) NOT NULL,
		`wcanal` INT(11) NOT NULL,
		`realspeed` INT(11) NOT NULL,
		`percent` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '48');
	UpdateVer($vr, '49');
}

// обновляем до 3.24
if ($cfg->version == '3.23') {
	$vr = '3.24';
	$log = '- изменяю таблицу хранения значений шейпера';
	$sql = 'ALTER TABLE `lanb_sp_graf` ADD `dt` DATETIME NOT NULL';
	ExecSQL($log, $sql, '50');
	UpdateVer($vr, '51');
}

// обновляем до 3.25
if ($cfg->version == '3.24') {
	$vr = '3.25';
	$log = '- изменяю таблицу настроек шейпера';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `maxperc` INT NOT NULL';
	ExecSQL($log, $sql, '52');
	UpdateVer($vr, '53');
}

// обновляем до 3.26
if ($cfg->version == '3.25') {
	$vr = '3.26';
	$log = '- добавляю таблицу хранения прав на доступ к серверам биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_rules_billing_servers` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibaseid` INT(11) NOT NULL,
		`userid` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '54');
	$log = '- добавляю таблицу хранения прав на доступ к фермам биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_rules_billing_ferma` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`billingid` INT(11) NOT NULL,
		`fermaid` INT(11) NOT NULL,
		`userid` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '55');
	$log = '- изменяю таблицу хранения прав на доступ к фермам биллинга';
	$sql = 'ALTER TABLE `lanb_rules_billing_ferma` ADD `fermname` VARCHAR(100) NOT NULL AFTER `userid`';
	ExecSQL($log, $sql, '56');
	UpdateVer($vr, '57');
}

// обновляем до 3.27
if ($cfg->version == '3.26') {
	$vr = '3.27';
	$log = '- добавляю таблицу хранения прав на доступ к устройствам биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_rules_billing_dev` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`devid` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '58');
	$log = '- изменяю таблицу хранения прав на доступ к устройствам биллинга';
	$sql = 'ALTER TABLE `lanb_rules_billing_dev` ADD `user_id` INT NOT NULL';
	ExecSQL($log, $sql, '59');
	UpdateVer($vr, '60');
}

// обновляем до 3.28
if ($cfg->version == '3.27') {
	$vr = '3.28';
	$log = '- добавляю таблицу импорта учеток из 1С';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_import_accounts` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`numdog_old` VARCHAR(100) NOT NULL,
		`fio` VARCHAR(100) NOT NULL,
		`phone` VARCHAR(100) NOT NULL,
		`address_old` VARCHAR(100) NOT NULL,
		`res` VARCHAR(100) NOT NULL,
		`add_amount` INT(10) NOT NULL,
		`amount` INT(10) NOT NULL,
		`arhchive` INT(11) NOT NULL,
		`country` INT(11) NOT NULL,
		`region` INT(11) NOT NULL,
		`area` INT(11) NOT NULL,
		`city` INT(11) NOT NULL,
		`street` INT(11) NOT NULL,
		`build` INT(11) NOT NULL,
		`settl` INT(11) NOT NULL,
		`uid` INT(11) NOT NULL,
		`number` INT(11) NOT NULL,
		`tar_id` INT(11) NOT NULL,
		`usl_id` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '61');
	UpdateVer($vr, '62');
}

// обновляем до 3.29
if ($cfg->version == '3.28') {
	$vr = '3.29';
	$log = '- изменяем структуру хранения графика';
	$sql = 'ALTER TABLE `lanb_sp_graf` CHANGE `ferma` `ferma` VARCHAR(11) NOT NULL';
	ExecSQL($log, $sql, '63');
	UpdateVer($vr, '64');
}

// обновляем до 3.30
if ($cfg->version == '3.29') {
	$vr = '3.30';
	$log = '- изменяем структуру хранения таблицы импорта';
	$sql = 'ALTER TABLE `lanb_import_accounts` ADD `nasp` VARCHAR(100) NOT NULL AFTER `area`';
	ExecSQL($log, $sql, '65');
	UpdateVer($vr, '66');
}

// обновляем до 3.31
if ($cfg->version == '3.30') {
	$vr = '3.31';
	$log = '- добавляю таблицу текущих скоростей для пользователей биллинга';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanbsh_users` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`billingid` INT(11) NOT NULL,
		`vg_id` INT(11) NOT NULL,
		`typesh` VARCHAR(100) NOT NULL,
		`b_speed` INT(11) NOT NULL,
		`real_speed` INT(11) NOT NULL,
		`pipe` INT(11) NOT NULL,
		`dt` DATETIME NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '67');
	UpdateVer($vr, '68');
}

// обновляем до 3.32
if ($cfg->version == '3.31') {
	$vr = '3.32';
	$log = '- изменяю таблицу текущих скоростей для пользователей биллинга';
	$sql = 'ALTER TABLE `lanbsh_users` CHANGE `pipe` `pipe` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '69');
	UpdateVer($vr, '70');
}

// обновляем до 3.33
if ($cfg->version == '3.32') {
	$vr = '3.33';
	$log = '- изменяю структуру хранения адресов для импорта';
	$sql = 'ALTER TABLE `lanb_import_accounts` CHANGE `build` `build` VARCHAR(11) NOT NULL';
	ExecSQL($log, $sql, '71');
	$log = '- изменяю структуру хранения адресов для импорта';
	$sql = 'ALTER TABLE `lanb_import_accounts` ADD `nbuild` VARCHAR(10) NOT NULL AFTER `settl`';
	ExecSQL($log, $sql, '72');
	$log = '- изменяю структуру хранения адресов для импорта';
	$sql = 'ALTER TABLE `lanb_import_accounts` ADD `nkv` VARCHAR(11) NOT NULL';
	ExecSQL($log, $sql, '73');
	UpdateVer($vr, '74');
}

// обновляем до 3.34
if ($cfg->version == '3.33') {
	$vr = '3.34';
	$log = '- изменяю структуру хранения номеров договоров для импорта';
	$sql = 'ALTER TABLE `lanb_import_accounts` CHANGE `number` `number` VARCHAR(20) NOT NULL';
	ExecSQL($log, $sql, '75');
	UpdateVer($vr, '76');
}

// обновляем до 3.35
if ($cfg->version == '3.34') {
	$vr = '3.35';
	$log = '- добавляю таблицу для функционала отправки СМС группе абонентов';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_sms_group` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`fio` VARCHAR(100) NOT NULL,
		`number` VARCHAR(20) NOT NULL,
		`login` VARCHAR(20) NOT NULL,
		`address` VARCHAR(200) NOT NULL,
		`balance` INT(11) NOT NULL,
		`credit` INT(11) NOT NULL,
		`mobile` VARCHAR(15) NOT NULL,
		`swname` VARCHAR(20) NOT NULL,
		`send` VARCHAR(20) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '77');
	UpdateVer($vr, '78');
}

// обновляем до 3.36
if ($cfg->version == '3.35') {
	$vr = '3.36';
	$log = '- добавляю поле onlytar - для определенного тарифа СТРОГО по тарифу';
	$sql = 'ALTER TABLE `lanbshapbytarifs` ADD `onlytar` INT NOT NULL';
	ExecSQL($log, $sql, '79');
	UpdateVer($vr, '80');
}

// обновляем до 3.37
if ($cfg->version == '3.36') {
	$vr = '3.37';
	$log = '- добавляю поле blocked - для таблицы групп СМС';
	$sql = 'ALTER TABLE `lanb_sms_group` ADD `blocked` INT NOT NULL';
	ExecSQL($log, $sql, '81');
	UpdateVer($vr, '82');
}

// обновляем до 3.38
if ($cfg->version == '3.37') {
	$vr = '3.38';
	$log = '- добавляю поля child и active - для таблицы групп devices';
	$sql = 'ALTER TABLE `devices` ADD `child` INT NOT NULL, ADD `active` INT NOT NULL';
	ExecSQL($log, $sql, '83');
	UpdateVer($vr, '84');
}

// обновляем до 3.39
if ($cfg->version == '3.38') {
	$vr = '3.39';
	$log = '- добавляю таблицу grafsort для построения графа оборудования (временная)';
	$sql = 'CREATE TABLE IF NOT EXISTS `grafsort` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`iddev` INT(11) NOT NULL,
		`mac` VARCHAR(20) NOT NULL,
		`name` VARCHAR(20) NOT NULL,
		`port` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '85');
	$log = '- Добавляю в таблицу lbcfg пол failhost для передачи управления другому IP в случае падения основного канала';
	$sql = 'ALTER TABLE `lbcfg` ADD `failhost` VARCHAR(20) NOT NULL';
	ExecSQL($log, $sql, '86');
	$log = '- Добавляю в таблицу lanbshconfig полe failbhost для передачи управления другому IP в случае падения основного канала';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `failbhost` VARCHAR(20) NOT NULL';
	ExecSQL($log, $sql, '87');
	UpdateVer($vr, '88');
}

// обновляем до 3.40
if ($cfg->version == '3.39') {
	$vr = '3.40';
	$log = '- добавляю поля kofinc & kofdec';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `kofinc` INT NOT NULL, ADD `kofdec` INT NOT NULL';
	ExecSQL($log, $sql, '89');
	$log = '- добавляю поля kofmin';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `kofmin` INT NOT NULL';
	ExecSQL($log, $sql, '90');
	UpdateVer($vr, '91');	
}

// обновляем до 3.41
if ($cfg->version == '3.40') {
	$vr = '3.41';
	$log = '- добавляю поля logme & logkof';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `logkof` INT NOT NULL, ADD `logme` VARCHAR(10) NOT NULL';
	ExecSQL($log, $sql, '92');
	UpdateVer($vr, '93');
}

// обновляем до 3.42
if ($cfg->version == '3.41') {
	$vr = '3.42';
	$log = '- добавляю таблицу snmp устройств';
	$sql = 'CREATE TABLE IF NOT EXISTS `devices_snmp` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`idbase` VARCHAR(10) NOT NULL,
		`deviceid` VARCHAR(10) NOT NULL,
		`ip` VARCHAR(30) NOT NULL,
		`mac` VARCHAR(30) NOT NULL,
		`port` VARCHAR(10) NOT NULL,
		`vlan` VARCHAR(10) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '94');
	UpdateVer($vr, '95');
}

// обновляем до 3.43
if ($cfg->version == '3.42') {
	$vr = '3.43';
	$log = '- добавляю таблицу lanb_mail_get почтовые ящики для получения почты в биллинг';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_mail_get` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`blibase` INT(11) NOT NULL,
		`popserver` VARCHAR(100) NOT NULL,
		`login` VARCHAR(100) NOT NULL,
		`pass` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '96');
	UpdateVer($vr, '97');
}

// обновляем до 3.44
if ($cfg->version == '3.43') {
	$vr = '3.44';
	$log = '- добавляю таблицу sms_center_config - агенты отправки СМС';
	$sql = 'CREATE TABLE IF NOT EXISTS `sms_center_config` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`agname` VARCHAR(50) NOT NULL,
		`smslogin` VARCHAR(50) NOT NULL,
		`smspass` VARCHAR(50) NOT NULL,
		`fileagent` VARCHAR(50) NOT NULL,
		`smsdiff` VARCHAR(10) NOT NULL ,  
		`sel` VARCHAR(10) NOT NULL,
		`sender` VARCHAR(20) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '98');
	UpdateVer($vr, '99');
}

// обновляем до 3.45
if ($cfg->version == '3.44') {
	$vr = '3.45';
	$log = '- изменяю таблицу lanblog новыен поля для расширения логов';
	$sql = 'ALTER TABLE `lanblog` ADD `userid` VARCHAR(10) NOT NULL,
		ADD `billingid` VARCHAR(10) NOT NULL,
		ADD `cost` FLOAT(11) NOT NULL';
	ExecSQL($log, $sql, '100');
	UpdateVer($vr, '101');
}

// обновляем до 3.46
if ($cfg->version == '3.45') {
	$vr = '3.46';
	$log = '- изменяю таблицу настроек шейперов';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `external_traff_url` VARCHAR(255) NOT NULL';
	ExecSQL($log, $sql, '102');
	UpdateVer($vr, '103');
}

// обновляем до 3.47
if ($cfg->version == '3.46') {
	$vr = '3.47';
	$log = '- изменяю таблицу ТМЦ (добавляю поле tmcgo - ТМЦ в пути';
	$sql = "ALTER TABLE `equipment` ADD `tmcgo` INT NOT NULL DEFAULT '0' AFTER `dtendgar`";
	ExecSQL($log, $sql, '104');
	UpdateVer($vr, '105');
}

// обновляем до 3.48
if ($cfg->version == '3.47') {
	$vr = '3.48';
	$log = '- добавляют таблицу usersrole - роли пользователей';
	$sql = 'CREATE TABLE IF NOT EXISTS `usersroles` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`userid` INT(11) NOT NULL,
		`role` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '106');
	UpdateVer($vr, '107');
}

// обновляем до 3.49
if ($cfg->version == '3.48') {
	$vr = '3.49';
	$log = '- добавляют в таблицу lbcfg информацию о SOAP соединении';
	$sql = 'ALTER TABLE `lbcfg` ADD `soap_login` VARCHAR(100) NOT NULL,
		ADD `soap_password` VARCHAR(100) NOT NULL,
		ADD `soap_file` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '108');
	UpdateVer($vr, '109');
}

// обновляем до 3.50
if ($cfg->version == '3.49') {
	$vr = '3.50';
	$log = '- добавляю таблицу jqcalendar для ведения календаря дел';
	$sql = 'CREATE TABLE `jqcalendar` (
		`Id` INT(11) NOT NULL AUTO_INCREMENT,
		`Subject` VARCHAR(1000) CHARACTER SET utf8 DEFAULT NULL,
		`Location` VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL,
		`Description` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL,
		`StartTime` DATETIME DEFAULT NULL,
		`EndTime` DATETIME DEFAULT NULL,
		`IsAllDayEvent` SMALLINT(6) NOT NULL,
		`Color` VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL,
		`RecurringRule` VARCHAR(500) CHARACTER SET utf8 DEFAULT NULL,
		PRIMARY KEY(`Id`)
	)';
	ExecSQL($log, $sql, '110');
	UpdateVer($vr, '111');
}

// обновляем до 3.51
if ($cfg->version == '3.50') {
	$vr = '3.51';
	$log = '- добавляю в таблицу jqcalendar поле для идентификации пользователя';
	$sql = 'ALTER TABLE `jqcalendar` ADD `uidview` VARCHAR(10) NOT NULL';
	ExecSQL($log, $sql, '112');
	UpdateVer($vr, '113');
}

// обновляем до 3.52
if ($cfg->version == '3.51') {
	$vr = '3.52';
	$log = '- добавляю в таблицу jqcalendar поле для идентификации тикета helpdesk';
	$sql = 'ALTER TABLE `jqcalendar` ADD `lbid` VARCHAR(12) NOT NULL';
	ExecSQL($log, $sql, '114');
	UpdateVer($vr, '115');
}

// обновляем до 3.53
if ($cfg->version == '3.52') {
	$vr = '3.53';
	$log = '- добавляю таблицу cloud_dirs - дерево хранения папок';
	$sql = 'CREATE TABLE IF NOT EXISTS `cloud_dirs` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`parent` INT(11) NOT NULL,
		`name` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '116');
	UpdateVer($vr, '117');
}

// обновляем до 3.54
if ($cfg->version == '3.53') {
	$vr = '3.54';
	$log = '- добавляю таблицу cloud_files - список файлов в дереве';
	$sql = 'CREATE TABLE IF NOT EXISTS `cloud_files` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`cloud_dirs_id` INT(11) NOT NULL,
		`title` VARCHAR(150) NOT NULL,
		`filename` VARCHAR(150) NOT NULL,
		`dt` DATETIME NOT NULL,
		`sz` INT(12) NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '118');
	UpdateVer($vr, '119');
}

// обновляем до 3.55
if ($cfg->version == '3.54') {
	$vr = '3.55';
	$log = '- обновляю jqcalendar';
	$sql = 'ALTER TABLE `jqcalendar` CHANGE `lbid` `lbid` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL';
	ExecSQL($log, $sql, '120');
	UpdateVer($vr, '121');
}

// обновляем до 3.56
if ($cfg->version == '3.55') {
	$vr = '3.56';
	$log = '- обновляю news';
	$sql = "ALTER TABLE `news` CHANGE `stiker` `stiker` TINYINT(1) NOT NULL DEFAULT '0'";
	ExecSQL($log, $sql, '122');
	UpdateVer($vr, '123');
}

// обновляем до 3.57
if ($cfg->version == '3.56') {
	$vr = '3.57';
	$log = '- обновляю lanbshconfig';
	$sql = 'ALTER TABLE `lanbshconfig` ADD `agents` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '124');
	UpdateVer($vr, '125');
}

// обновляем до 3.58
if ($cfg->version == '3.57') {
	$vr = '3.58';
	$log = '- добавляю таблицу lanb_dogs';
	$sql = 'CREATE TABLE IF NOT EXISTS `lanb_dogs` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`billingid` INT(11) NOT NULL,
		`agrm_id` INT(11) NOT NULL,
		`dtfrom` date NOT NULL,
		`dtto` date NOT NULL,
		`speedup` INT(11) NOT NULL,
		`speeddown` INT(11) NOT NULL,
		`statusdog` INT(11) NOT NULL,
		`vlan` INT(11) NOT NULL,
		`net1` BIGINT NOT NULL,
		`mask1` INT(11) NOT NULL,
		`net2` BIGINT NOT NULL,
		`mask2` INT(11) NOT NULL,
		`comment` TEXT NOT NULL,
		PRIMARY KEY(`id`)
	)';
	ExecSQL($log, $sql, '126');
	UpdateVer($vr, '127');
}

// обновляем до 3.59
if ($cfg->version == '3.58') {
	$vr = '3.59';
	$log = '- обновляю таблицу lanb_dogs';
	$sql = 'ALTER TABLE `lanb_dogs` ADD `server_d` INT NOT NULL';
	ExecSQL($log, $sql, '128');
	UpdateVer($vr, '129');
}

// обновляем до 3.60
if ($cfg->version == '3.59') {
	$vr = '3.60';
	$log = '- обновляю таблицу lanb_dogs';
	$sql = 'ALTER TABLE `lanb_dogs` ADD `vg_id` INT NOT NULL';
	ExecSQL($log, $sql, '130');
	$log = '- обновляю таблицу lanb_dogs';
	$sql = 'ALTER TABLE `lanb_dogs` ADD `canupdate` INT NOT NULL';
	ExecSQL($log, $sql, '131');
	UpdateVer($vr, '132');
}

// обновляем до 3.61
if ($cfg->version == '3.60') {
	$vr = '3.61';
	$log = '- обновляю таблицу lanb_dogs';
	$sql = 'ALTER TABLE `lanb_dogs` ADD `nvlan` VARCHAR(10) NOT NULL';
	ExecSQL($log, $sql, '133');
	UpdateVer($vr, '134');
}

// обновляем до 3.62
if ($cfg->version == '3.61') {
	$vr = '3.62';
	$log = '- обновляю таблицу places';
	$sql = 'ALTER TABLE `places` ADD `opgroup` VARCHAR(100) NOT NULL';
	ExecSQL($log, $sql, '135');
	UpdateVer($vr, '136');
}

// обновляем до 3.63
if ($cfg->version == '3.62') {
	$vr = '3.63';
	$log = '- добавляю таблицу smslist для групповой отправки смс по списку';
	$sql = 'CREATE TABLE IF NOT EXISTS `smslist` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`mobile` VARCHAR(20) NOT NULL,
		`smstxt` TEXT NOT NULL,
		`status` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
	ExecSQL($log, $sql, '137');
	UpdateVer($vr, '138');
}

// обновляем до 3.64
if ($cfg->version == '3.63') {
	$vr = '3.64';
	$log = '- добавляю таблицу lib_cable_lines для справочника кабельной структуры';
	$sql = 'CREATE TABLE IF NOT EXISTS `lib_cable_lines` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`id_calble_module` INT(11) NOT NULL,
		`number` INT(11) NOT NULL,
		`color1` VARCHAR(100) NOT NULL,
		`color2` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=13';
	ExecSQL($log, $sql, '139');
	$log = '- добавляю таблицу lib_cable_modules для справочника кабельной структуры';
	$sql = 'CREATE TABLE IF NOT EXISTS `lib_cable_modules` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`cable_id` INT(11) NOT NULL,
		`number` VARCHAR(11) NOT NULL,
		`color` VARCHAR(100) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=13';
	ExecSQL($log, $sql, '140');
	$log = '- добавляю таблицу lib_cable_name_mark для справочника кабельной структуры';
	$sql = 'CREATE TABLE IF NOT EXISTS `lib_cable_name_mark` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(255) NOT NULL,
		`mark` VARCHAR(255) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4';
	ExecSQL($log, $sql, '141');
	UpdateVer($vr, '142');
}

// обновляем до 3.65
if ($cfg->version == '3.64') {
	$vr = '3.65';
	$log = '- добавляю таблицу entropia для получения уникального id для разных целей';
	$sql = 'CREATE TABLE IF NOT EXISTS `entropia` (`cnt` INT(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8';
	ExecSQL($log, $sql, '143');
	$log = '- добавляю таблицу entropia для получения уникального id для разных целей';
	$sql = 'INSERT INTO entropia (cnt) VALUES (0)';
	ExecSQL($log, $sql, '144');
	$log = '- добавляю хранимую процедуру GetRandomId для получения идентификатора';
	$sql = 'CREATE PROCEDURE `GetRandomId`()
	DETERMINISTIC
	BEGIN  
		UPDATE entropia SET cnt=cnt+1;
		SELECT cnt FROM entropia;
	END';
	ExecSQL($log, $sql, '145');
	$log = '- добавляю таблицу хранения муфт lib_cable_muft';
	$sql = 'CREATE TABLE IF NOT EXISTS `lib_cable_muft` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50) NOT NULL,
		`comment` VARCHAR(255) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
	ExecSQL($log, $sql, '146');
	UpdateVer($vr, '147');
}

// обновляем до 3.66
if ($cfg->version == '3.65') {
	$vr = '3.66';
	$log = '- добавляю таблицу справочника сплитеров';
	$sql = 'CREATE TABLE IF NOT EXISTS `lib_cable_spliter` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(100) NOT NULL,
		`exitcount` INT(11) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
	ExecSQL($log, $sql, '148');
	$log = '- добавляю таблицу для хранения распайки волокон в муфте';
	$sql = "CREATE TABLE IF NOT EXISTS `lib_lines_in_muft` (
		`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор волокна в муфте на карте',
		`mufta_id` INT(11) NOT NULL COMMENT 'Идентификатор муфты на карте',
		`obj_edit_id` INT(11) NOT NULL COMMENT 'Идентификатор кабеля на карте',
		`lib_line_id` INT(11) NOT NULL COMMENT 'ссылка на волокно из справочника',
		`start_id` INT(11) NOT NULL COMMENT 'идентификатор стыковки начала волокна',
		`end_id` INT(11) NOT NULL COMMENT 'идентификатор конца волокна',
		`type_obj` VARCHAR(20) NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	ExecSQL($log, $sql, '149');
	UpdateVer($vr, '150');
}

// обновляем до 3.67
if ($cfg->version == '3.66') {
	$vr = '3.67';
	$log = '- добавляю поле для хранения цвета кнопки устройства';
	$sql = 'ALTER TABLE `devnames` ADD `bcolor` VARCHAR(50) NOT NULL';
	ExecSQL($log, $sql, '151');
	UpdateVer($vr, '152');
}

// обновляем до 3.68
if ($cfg->version == '3.67') {
	$vr = '3.68';
	$log = '- добавляю поле комментария к каждому волокну';
	$sql = 'ALTER TABLE `lib_lines_in_muft` ADD `comment` VARCHAR(255) NOT NULL';
	ExecSQL($log, $sql, '153');
	UpdateVer($vr, '154');
}

// обновляем до 3.69
if ($cfg->version == '3.68') {
	$vr = '3.69';
	$log = '- добавляю еще один цвет к модулю';
	$sql = 'ALTER TABLE `lib_cable_modules` ADD `color1` VARCHAR(20) NOT NULL';
	ExecSQL($log, $sql, '155');
	UpdateVer($vr, '156');
}

// обновляем до 3.70
if ($cfg->version == '3.69') {
	$vr = '3.70';
	/* $log = "- добавляю поле zindex для обьектов карты";
	  $sql = 'ALTER TABLE `lanb_maps` ADD `zindex` INT NOT NULL';
	  ExecSQL($log, $sql, '157');
	 */
	UpdateVer($vr, '158');
}

// обновляем до 3.71
if ($cfg->version == '3.70') {
	$vr = '3.71';
	/* $log = '- добавляю поле state для фиксации обьектов карты';
	  $sql = 'ALTER TABLE `lanb_maps_coor` ADD `state` INT NOT NULL';
	  ExecSQL($log, $sql, '159');
	 */
	UpdateVer($vr, '160');
}

// Обновляем до 3.72
if ($cfg->version == '3.71') {
	$vr = '3.72';
	$log = '- добавляю таблицу для создания меню';
	$sql = "CREATE TABLE IF NOT EXISTS `menu` (
		`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
		`parents` INT(11) NOT NULL COMMENT 'Родитель',
		`sort_id` INT(11) NOT NULL COMMENT 'Сортировка',
		`name` VARCHAR(200) NOT NULL COMMENT 'Название',
		`comment` VARCHAR(200) NOT NULL COMMENT 'Пояснение',
		`uid` VARCHAR(50) NOT NULL COMMENT 'некий идентификатор (можно использовать для автосоздания менюшек)',
		PRIMARY KEY(`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
	ExecSQL($log, $sql, '161');
	UpdateVer($vr, '162');
}

// Обновляем до 3.73
if ($cfg->version == '3.72') {
	$vr = '3.73';
	$log = '- добавляю поля для хеширования пароля';
	$sql = 'ALTER TABLE `users` ADD COLUMN `password` CHAR(40) NOT NULL AFTER `pass`, ADD COLUMN `salt` CHAR(10) NOT NULL AFTER `password`';
	ExecSQL($log, $sql, '163');
	$log = '- обновляю соль в таблице пользователей';
	$sql = "UPDATE users SET salt=SUBSTRING(MD5(RAND()), -10) WHERE salt=''";
	ExecSQL($log, $sql, '164.1');
	$log = '- обновляю хеши паролей';	
	$sql = "UPDATE users SET `password`=SHA1(CONCAT(SHA1(pass), salt)) WHERE `password`=''";
	ExecSQL($log, $sql, '164.2');
	UpdateVer($vr, '165');
}

// Обновляем до 3.74
if ($cfg->version == '3.73') {
	$vr = '3.74';
	$log = '- удаляю поле pass из таблицы users';
	$sql = 'ALTER TABLE `users` DROP COLUMN `pass`';
	ExecSQL($log, $sql, '166');
	UpdateVer($vr, '167');
}
// Обновляем до 3.75
if ($cfg->version == '3.74') {
	$vr = '3.75';
	$log = '- расширяю поле valueparam до TEXT в таблице config_common';
	$sql = 'ALTER TABLE `config_common` CHANGE `valueparam` `valueparam` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL';
	ExecSQL($log, $sql, '166');
	UpdateVer($vr, '167');
}

// Обновляем до 3.76
if ($cfg->version == '3.75') {
	$vr = '3.76';
	//переместил создание структуры для sms_center в сам модуль..
	UpdateVer($vr, '168');
}

echo 'Обновление закончено.<br>';
echo 'Если сообщений об ошибках нет, удалите файл update.php.<br>';
?>
</body>
</html>
