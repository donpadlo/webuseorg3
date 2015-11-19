<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Учет ТМЦ в организации и другие плюшки">
    <meta name="author" content="(c) 2011-2014 by Gribov Pavel">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>    
    <title>Учет оргтехники в организации</title>
    <meta name="generator" content="yarus" />
    <link href="favicon.ico" type="image/ico" rel="icon" />
    <link href="favicon.ico" type="image/ico" rel="shortcut icon" />    
</head>
<body>   

<?php
include_once ("config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("class/sql.php");                  // загружаем классы работы с БД
include_once("class/config.php");		// загружаем классы настроек

// загружаем все что нужно для работы движка

include_once("inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("inc/config.php");                 // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("inc/functions.php");		// загружаем функции

echo "Обновление начато.</br>";

// обновляем до 3.01
if (($cfg->version=="1.05") or ($cfg->version=="3.0")) {
    $vr="3.01";
    echo "-добавляю в таблице equipment поля kntid (контрагент-поставщик) и dtendgar (дата окончания гарантии на ТМЦ)</br>";
    $sql="ALTER TABLE  `equipment` ADD  `kntid` INT NOT NULL AFTER  `mapyet` ,ADD  `dtendgar` DATE NOT NULL AFTER  `kntid`";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(2)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    $cfg->version=$vr;
    echo "--ok</br>";
};

// обновляем до 3.02
if ($cfg->version=="3.01") {
    $vr="3.02";
    echo "-добавляю в таблице tasks поле mainuseid (руководитель исполнителя)</br>";
    $sql="ALTER TABLE  `tasks` ADD  `mainuseid` INT NOT NULL AFTER  `touserid`";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(3)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(4)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.03
if ($cfg->version=="3.02") {
    $vr="3.03";
    echo "-добавляю в таблице repair дополнительные поля</br>";
    $sql="ALTER TABLE  `repair` ADD  `userfrom` INT NOT NULL ,ADD  `userto` INT NOT NULL ,ADD  `doc` TEXT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(5)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-изменяю в таблице repair тип поля cost на float</br>";
    $sql="ALTER TABLE  `repair` CHANGE  `cost`  `cost` FLOAT( 11 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(5.1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(6)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.04
if ($cfg->version=="3.03") {
    $vr="3.04";
    echo "-add table devgroups</br>";
    $sql="create table devgroups (id int(11) AUTO_INCREMENT,dgname varchar(255),dcomment varchar(255),PRIMARY KEY  (id))";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(7)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-add table devnames</br>";
    $sql="create table devnames (id int(11) AUTO_INCREMENT,dname varchar(255),command TEXT,PRIMARY KEY  (id))";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "-modify table devnames</br>";
    $sql="alter table devnames  add devid  int(11)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-добавляю таблицу настройки LanBilling</br>";
    $sql="create table lbcfg (id int(11) AUTO_INCREMENT,sname varchar(255),host varchar(255),basename varchar(255),username varchar(255),pass varchar(255),PRIMARY KEY  (id))";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-добавляю таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="create table lanbpredplat (id int(11) AUTO_INCREMENT,number varchar(50),username varchar(50),address varchar(250),phone varchar(20),balance double,blocked TINYINT,am_inet double,am_tv double,am_usl double,recount double,grp double,tarifs varchar(255),dt datetime,PRIMARY KEY  (id));    ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.2)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add afterrecount  double";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.3)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add blibaseid TINYINT";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.4)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
        echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add uid varchar (20)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.5)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
        echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add vg_id varchar (20)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.6)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
        echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add login varchar (20)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.7)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
        echo "-модифицирую таблицу расчетов предварительных платежей LanBilling</br>";
    $sql="alter table lanbpredplat add agrm_id varchar (20)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(8.8)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(9)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.05
if ($cfg->version=="3.04") {
    $vr="3.05";
    echo "-меняю структуру таблицы lanbpredplat</br>";
    $sql="ALTER TABLE  `lanbpredplat` ADD  `smssend` VARCHAR( 50 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(10)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-добавляю таблицу шаблонов СМС для LanBilling</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbsmstempl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibase` int(11) NOT NULL,
  `typetmp` int(11) NOT NULL,
  `txt` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(10.1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(11)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.06
if ($cfg->version=="3.05") {
    $vr="3.06";
    echo "- добавляем статистику по отправке СМС</br>";
    $sql="CREATE TABLE IF NOT EXISTS `smsstat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL,
  `countok` int(10) NOT NULL,
  `countfail` int(10) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(12)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(13)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.07
if ($cfg->version=="3.06") {
    $vr="3.07";
    echo "- добавляем значение порога отправки СМС </br>";
    $sql="INSERT INTO config_common (id,
nameparam ,
`valueparam`
)
VALUES (
NULL ,  'smsdiffres',  '3')";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(14)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(15)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.08
if ($cfg->version=="3.07") {
    $vr="3.08";
    echo "- добавляем таблицу для ограничения тарифов на шейпере</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbshapbytarifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibase` int(11) NOT NULL,
  `tar_id` int(11) NOT NULL,
  `tarname` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL,
  `fixshape` int(11) NOT NULL,
  `maxspeed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(16)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(17)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.09
if ($cfg->version=="3.08") {
    $vr="3.09";
    echo "- модифицируем ограничения тарифов на шейпере</br>";
    $sql="ALTER TABLE  `lanbshapbytarifs` ADD  `cntusers` INT( 10 ) NOT NULL ,
ADD  `cntblocked` INT( 10 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(18)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "- модифицируем ограничения тарифов на шейпере</br>";
    $sql="ALTER TABLE  `lanbshapbytarifs` ADD  `used` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(18)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(20)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.10
if ($cfg->version=="3.09") {
    $vr="3.10";
    echo "- добавляем таблицу устройств (свичи, коммутаторы и т.п.</br>";
    $sql="CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbase` int(11) NOT NULL,
  `devname` varchar(255) NOT NULL,
  `whereis` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `param_name` varchar(255) NOT NULL,
  `param_value` varchar(255) NOT NULL,
  `cnt` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(21)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(22)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.11
if ($cfg->version=="3.10") {
    $vr="3.11";
    echo "- добавляем таблицу устройств (свичи, коммутаторы и т.п.</br>";
    $sql="ALTER TABLE  `devices` ADD  `devid` INT NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(23)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(24)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.12
if ($cfg->version=="3.11") {
    $vr="3.12";
    echo "- добавляем таблицу устройств (свичи, коммутаторы и т.п.</br>";
    $sql="ALTER TABLE  `devices` CHANGE  `whereis`  `whereis` VARCHAR( 255 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(25)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(26)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.13
if ($cfg->version=="3.12") {
    $vr="3.13";
    echo "- добавляем таблицу устройств (свичи, коммутаторы и т.п.</br>";
    $sql="ALTER TABLE  `devices` ADD  `stamp` TIMESTAMP NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(27)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(28)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.14
if ($cfg->version=="3.13") {
    $vr="3.14";
    echo "- добавляем таблицу банков для биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbbanks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibaseid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `inmail` varchar(150) NOT NULL,
  `outmail` varchar(150) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `dir_name` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(29)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(30)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.15
if ($cfg->version=="3.14") {
    $vr="3.15";
    echo "- добавляем таблицу фильтров-договоров биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbfileterdog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibase` int(11) NOT NULL,
  `filterdog` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(31)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(32)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.16
if ($cfg->version=="3.15") {
    $vr="3.16";
    echo "- изменяю структуру таблицы банков</br>";
    $sql="ALTER TABLE  `lanbbanks` ADD  `codecl` VARCHAR( 100 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(33)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(34)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.17
if ($cfg->version=="3.16") {
    $vr="3.17";
    echo "- добавляю таблицу Логов</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanblog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loglevel` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `txt` text NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(35)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(36)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.18
if ($cfg->version=="3.17") {
    $vr="3.18";
    echo "- добавляю таблицу Логов</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanblog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loglevel` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `txt` text NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(36)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(37)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.19
if ($cfg->version=="3.18") {
    $vr="3.19";
    echo "- добавляю таблицу Логов</br>";
    $sql="ALTER TABLE  `lanbbanks` ADD  `codeusl` VARCHAR( 100 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(38)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(39)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.20
if ($cfg->version=="3.19") {
    $vr="3.20";
    echo "- меняю тип хранения dir_name (банки)</br>";
    $sql="ALTER TABLE  `lanbbanks` CHANGE  `dir_name`  `dir_name` VARCHAR( 100 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(40)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(41)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.21
if ($cfg->version=="3.20") {
    $vr="3.21";
    echo "- добавляю таблицу серверов для настройки шейперов</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbshaperservers` (`id` int(11) NOT NULL AUTO_INCREMENT,`sname` varchar(200) NOT NULL,`comment` varchar(200) NOT NULL,`blibaseid` int(11) NOT NULL,PRIMARY KEY (`id`))";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(42)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- добавляю таблицу настроек серверов для настройки шейперов</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbshconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibaseid` int(11) NOT NULL,
  `idsh` int(11) NOT NULL,
  `option82` varchar(100) NOT NULL,
  `radius` varchar(100) NOT NULL,
  `wcanal` varchar(100) NOT NULL,
  `maxw` varchar(100) NOT NULL,
  `minw` varchar(100) NOT NULL,
  `ferma` varchar(100) NOT NULL,
  `segment_id` varchar(100) NOT NULL,
  `radiusip` varchar(100) NOT NULL,
  `ent` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(42)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(43)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.22
if ($cfg->version=="3.21") {
    $vr="3.22";
    echo "- обновляю таблицу настройки шейперов</br>";
    $sql="ALTER TABLE  `lanbshconfig` ADD  `bhost` VARCHAR( 100 ) NOT NULL AFTER  `ent` ,
ADD  `bname` VARCHAR( 100 ) NOT NULL AFTER  `bhost` ,
ADD  `buser` VARCHAR( 100 ) NOT NULL AFTER  `bname` ,
ADD  `bpass` VARCHAR( 100 ) NOT NULL AFTER  `buser` ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(44)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
       
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(45)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.23
if ($cfg->version=="3.22") {
    $vr="3.23";
    echo "- добавляю таблицу хранения значений шейпера</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_sp_graf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibase` int(11) NOT NULL,
  `ferma` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  `maxw` int(11) NOT NULL,
  `minw` int(11) NOT NULL,
  `wcanal` int(11) NOT NULL,
  `realspeed` int(11) NOT NULL,
  `percent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(44)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
       
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(45)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.24
if ($cfg->version=="3.23") {
    $vr="3.24";
    echo "- изменяю таблицу хранения значений шейпера</br>";
    $sql="ALTER TABLE  `lanb_sp_graf` ADD  `dt` DATETIME NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(44)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
       
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(45)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.25
if ($cfg->version=="3.24") {
    $vr="3.25";
    echo "- изменяю таблицу настроек шейпера</br>";
    $sql="ALTER TABLE  `lanbshconfig` ADD  `maxperc` INT NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(50)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
       
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(51)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.26
if ($cfg->version=="3.25") {
    $vr="3.26";
    echo "- добавляю таблицу хранения прав на доступ к серверам биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_rules_billing_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibaseid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(52)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
       
    echo "- добавляю таблицу хранения прав на доступ к фермам биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_rules_billing_ferma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billingid` int(11) NOT NULL,
  `fermaid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(52_1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- изменяю таблицу хранения прав на доступ к фермам биллинга</br>";
    $sql="ALTER TABLE  `lanb_rules_billing_ferma` ADD  `fermname` VARCHAR( 100 ) NOT NULL AFTER  `userid`";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(52_1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(53)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.27
if ($cfg->version=="3.26") {
    $vr="3.27";
    echo "- добавляю таблицу хранения прав на доступ к устройствам биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_rules_billing_dev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(54)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- изменяю таблицу хранения прав на доступ к устройствам биллинга</br>";
    $sql="ALTER TABLE  `lanb_rules_billing_dev` ADD  `user_id` INT NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(55)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(56)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.28
if ($cfg->version=="3.27") {
    $vr="3.28";
    echo "- добавляю таблицу импорта учеток из 1С</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_import_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numdog_old` varchar(100) NOT NULL,
  `fio` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address_old` varchar(100) NOT NULL,
  `res` varchar(100) NOT NULL,
  `add_amount` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `arhchive` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `region` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `street` int(11) NOT NULL,
  `build` int(11) NOT NULL,
  `settl` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `tar_id` int(11) NOT NULL,
  `usl_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(54)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(56)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.29
if ($cfg->version=="3.28") {
    $vr="3.29";
    echo "- изменяем структуру хранения графика</br>";
    $sql="ALTER TABLE  `lanb_sp_graf` CHANGE  `ferma`  `ferma` VARCHAR( 11 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(54)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(56)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.30
if ($cfg->version=="3.29") {
    $vr="3.30";
    echo "- изменяем структуру хранения таблицы импорта</br>";
    $sql="ALTER TABLE `lanb_import_accounts` ADD `nasp` VARCHAR( 100 ) NOT NULL AFTER `area` ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(57)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(58)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.31
if ($cfg->version=="3.30") {
    $vr="3.31";
    echo "- добавляю таблицу текущих скоростей для пользователей биллинга</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanbsh_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billingid` int(11) NOT NULL,
  `vg_id` int(11) NOT NULL,
  `typesh` varchar(100) NOT NULL,
  `b_speed` int(11) NOT NULL,
  `real_speed` int(11) NOT NULL,
  `pipe` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(57)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(58)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.32
if ($cfg->version=="3.31") {
    $vr="3.32";
    echo "- изменяю таблицу текущих скоростей для пользователей биллинга</br>";
    $sql="ALTER TABLE  `lanbsh_users` CHANGE  `pipe`  `pipe` VARCHAR( 100 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(57)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(58)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.33
if ($cfg->version=="3.32") {
    $vr="3.33";
    echo "- изменяю структуру хранения адресов для импорта</br>";
    $sql="ALTER TABLE  `lanb_import_accounts` CHANGE  `build`  `build` VARCHAR( 11 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(59)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- изменяю структуру хранения адресов для импорта</br>";
    $sql="ALTER TABLE  `lanb_import_accounts` ADD  `nbuild` VARCHAR( 10 ) NOT NULL AFTER  `settl`";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(60)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "- изменяю структуру хранения адресов для импорта</br>";
    $sql="ALTER TABLE  `lanb_import_accounts` ADD  `nkv` VARCHAR( 11 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(61)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(62)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.34
if ($cfg->version=="3.33") {
    $vr="3.34";
    echo "- изменяю структуру хранения номеров договоров для импорта</br>";
    $sql="ALTER TABLE  `lanb_import_accounts` CHANGE  `number`  `number` VARCHAR( 20 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(59)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(62)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.35
if ($cfg->version=="3.34") {
    $vr="3.35";
    echo "- добавляю таблицу для функционала отправки СМС группе абонентов</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_sms_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `login` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `balance` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `swname` varchar(20) NOT NULL,
  `send` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(63)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(64)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.36
if ($cfg->version=="3.35") {
    $vr="3.36";
    echo "- добавляю поле onlytar - для определенного тарифа СТРОГО по тарифу</br>";
    $sql="ALTER TABLE  `lanbshapbytarifs` ADD  `onlytar` INT NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(63)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(64)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.37
if ($cfg->version=="3.36") {
    $vr="3.37";
    echo "- добавляю поле blocked - для таблицы групп СМС</br>";
    $sql="ALTER TABLE  `lanb_sms_group` ADD  `blocked` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(63)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(64)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.38
if ($cfg->version=="3.37") {
    $vr="3.38";
    echo "- добавляю поля child и active - для таблицы групп devices</br>";
    $sql="ALTER TABLE  `devices` ADD  `child` INT NOT NULL ,
ADD  `active` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(65)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(66)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.39
if ($cfg->version=="3.38") {
    $vr="3.39";
    echo "- добавляю таблицу grafsort для построения графа оборудования (временная)</br>";
    $sql="CREATE TABLE IF NOT EXISTS `grafsort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iddev` int(11) NOT NULL,
  `mac` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `port` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(67)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- Добавляю в таблицу lbcfg пол failhost для передачи управления другому IP в случае падения основного канала </br>";
    $sql="ALTER TABLE `lbcfg` ADD `failhost` VARCHAR( 20 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(67)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- Добавляю в таблицу lanbshconfig полe failbhost для передачи управления другому IP в случае падения основного канала </br>";
    $sql="ALTER TABLE `lanbshconfig` ADD `failbhost` VARCHAR( 20 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(67)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(68)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.40
if ($cfg->version=="3.39") {
    $vr="3.40";
    echo "- добавляю поля kofinc & kofdec</br>";
    $sql="ALTER TABLE `lanbshconfig` ADD `kofinc` INT NOT NULL ,
ADD `kofdec` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(70)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";


        echo "- добавляю поля kofmin</br>";
    $sql="ALTER TABLE `lanbshconfig` ADD `kofmin` INT NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(70.1)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(71)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.41
if ($cfg->version=="3.40") {
    $vr="3.41";
    echo "- добавляю поля logme & logkof</br>";
    $sql="ALTER TABLE  `lanbshconfig` ADD  `logkof` INT NOT NULL ,
ADD  `logme` VARCHAR( 10 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(72)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(73)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.42
if ($cfg->version=="3.41") {
    $vr="3.42";
    echo "- добавляю таблицу snmp устройств</br>";
    $sql="CREATE TABLE IF NOT EXISTS `devices_snmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbase` varchar(10) NOT NULL,
  `deviceid` varchar(10) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `mac` varchar(30) NOT NULL,
  `port` varchar(10) NOT NULL,
  `vlan` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(73)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(74)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.43
if ($cfg->version=="3.42") {
    $vr="3.43";
    echo "- добавляю таблицу lanb_mail_get почтовые ящики для получения почты в биллинг</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_mail_get` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blibase` int(11) NOT NULL,
  `popserver` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(75)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(76)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.44
if ($cfg->version=="3.43") {
    $vr="3.44";
    echo "- добавляю таблицу sms_center_config - агенты отправки СМС</br>";
    $sql="CREATE TABLE IF NOT EXISTS `sms_center_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agname` varchar(50) NOT NULL,
  `smslogin` varchar(50) NOT NULL,
  `smspass` varchar(50) NOT NULL,
  `fileagent` varchar(50) NOT NULL,
`smsdiff` VARCHAR( 10 ) NOT NULL ,  
`sel` VARCHAR( 10 ) NOT NULL,
`sender` VARCHAR( 20 ) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(77)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(78)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.45
if ($cfg->version=="3.44") {
    $vr="3.45";
    echo "- изменяю таблицу lanblog новыен поля для расширения логов</br>";
    $sql="ALTER TABLE  `lanblog` ADD  `userid` VARCHAR( 10 ) NOT NULL ,
ADD  `billingid` VARCHAR( 10 ) NOT NULL ,
ADD  `cost` FLOAT( 11 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(79)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(80)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.46
if ($cfg->version=="3.45") {
    $vr="3.46";
    echo "- изменяю таблицу настроек шейперов</br>";
    $sql="ALTER TABLE  `lanbshconfig` ADD  `external_traff_url` VARCHAR( 255 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(81)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(82)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


// обновляем до 3.47
if ($cfg->version=="3.46") {
    $vr="3.47";
    echo "- изменяю таблицу ТМЦ (добавляю поле tmcgo - ТМЦ в пути</br>";
    $sql="ALTER TABLE  `equipment` ADD  `tmcgo` INT NOT NULL DEFAULT  '0' AFTER  `dtendgar`";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(83)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(84)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.48
if ($cfg->version=="3.47") {
    $vr="3.48";
    echo "- добавляют таблицу usersrole - роли пользователей</br>";
    $sql="CREATE TABLE IF NOT EXISTS `usersroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(85)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(86)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.49
if ($cfg->version=="3.48") {
    $vr="3.49";
    echo "- добавляют в таблицу lbcfg информацию о SOAP соеденении</br>";
    $sql="ALTER TABLE  `lbcfg` ADD  `soap_login` VARCHAR( 100 ) NOT NULL ,
ADD  `soap_password` VARCHAR( 100 ) NOT NULL ,
ADD  `soap_file` VARCHAR( 100 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(85)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(86)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.50
if ($cfg->version=="3.49") {
    $vr="3.50";
    echo "- добавляю таблицу jqcalendar для ведения календаря дел</br>";
    $sql="CREATE TABLE `jqcalendar` (
  `Id` int(11) NOT NULL auto_increment,
  `Subject` varchar(1000) character set utf8 default NULL,
  `Location` varchar(200) character set utf8 default NULL,
  `Description` varchar(255) character set utf8 default NULL,
  `StartTime` datetime default NULL,
  `EndTime` datetime default NULL,
  `IsAllDayEvent` smallint(6) NOT NULL,
  `Color` varchar(200) character set utf8 default NULL,
  `RecurringRule` varchar(500) character set utf8 default NULL,
  PRIMARY KEY  (`Id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(87)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(88)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.51
if ($cfg->version=="3.50") {
    $vr="3.51";
    echo "- добавляю в таблицу jqcalendar поле для идентификации пользователя</br>";
    $sql="ALTER TABLE  `jqcalendar` ADD  `uidview` VARCHAR( 10 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(89)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(90)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.52
if ($cfg->version=="3.51") {
    $vr="3.52";
    echo "- добавляю в таблицу jqcalendar поле для идентификации тикета helpdesk</br>";
    $sql="ALTER TABLE  `jqcalendar` ADD  `lbid` VARCHAR( 12 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(91)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

      
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(92)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.53
if ($cfg->version=="3.52") {
    $vr="3.53";
    echo "- добавляю таблицу cloud_dirs - дерево хранения папок</br>";
    $sql="CREATE TABLE IF NOT EXISTS `cloud_dirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(93)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(94)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.54
if ($cfg->version=="3.53") {
    $vr="3.54";
    echo "- добавляю таблицу cloud_files - список файлов в дереве</br>";
    $sql="CREATE TABLE IF NOT EXISTS `cloud_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cloud_dirs_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `dt` datetime NOT NULL,
  `sz` int(12) NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(95)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(96)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.55
if ($cfg->version=="3.54") {
    $vr="3.55";
    echo "- обновляю jqcalendar</br>";
    $sql="ALTER TABLE  `jqcalendar` CHANGE  `lbid`  `lbid` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(95)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(96)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.56
if ($cfg->version=="3.55") {
    $vr="3.56";
    echo "- обновляю news</br>";
    $sql="ALTER TABLE  `news` CHANGE  `stiker`  `stiker` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(95)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(96)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.57
if ($cfg->version=="3.56") {
    $vr="3.57";
    echo "- обновляю lanbshconfig</br>";
    $sql="ALTER TABLE  `lanbshconfig` ADD  `agents` VARCHAR( 100 ) NOT NULL ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(97)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(98)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.58
if ($cfg->version=="3.57") {
    $vr="3.58";
    echo "- добавляю таблицу lanb_dogs</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lanb_dogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billingid` int(11) NOT NULL,
  `agrm_id` int(11) NOT NULL,
  `dtfrom` date NOT NULL,
  `dtto` date NOT NULL,
  `speedup` int(11) NOT NULL,
  `speeddown` int(11) NOT NULL,
  `statusdog` int(11) NOT NULL,
  `vlan` int(11) NOT NULL,
  `net1` BIGINT NOT NULL,
  `mask1` int(11) NOT NULL,
  `net2` BIGINT NOT NULL,
  `mask2` int(11) NOT NULL,
  `comment` TEXT NOT NULL,
  PRIMARY KEY (`id`)
)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(99)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(100)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.59
if ($cfg->version=="3.58") {
    $vr="3.59";
    echo "- обновляю таблицу lanb_dogs</br>";
    $sql="ALTER TABLE  `lanb_dogs` ADD  `server_d` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(101)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
     
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(102)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.60
if ($cfg->version=="3.59") {
    $vr="3.60";
    echo "- обновляю таблицу lanb_dogs</br>";
    $sql="ALTER TABLE  `lanb_dogs` ADD  `vg_id` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(103)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "- обновляю таблицу lanb_dogs</br>";
    $sql="ALTER TABLE  `lanb_dogs` ADD  `canupdate` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(103)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(104)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.61
if ($cfg->version=="3.60") {
    $vr="3.61";
    echo "- обновляю таблицу lanb_dogs</br>";
    $sql="ALTER TABLE  `lanb_dogs` ADD  `nvlan` VARCHAR( 10 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(103)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(104)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.62
if ($cfg->version=="3.61") {
    $vr="3.62";
    echo "- обновляю таблицу places</br>";
    $sql="ALTER TABLE  `places` ADD  `opgroup` VARCHAR( 100 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(103)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(104)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.63
if ($cfg->version=="3.62") {
    $vr="3.63";
    echo "- добавляю таблицу smslist для групповой отправки смс по списку</br>";
    $sql="CREATE TABLE IF NOT EXISTS `smslist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) NOT NULL,
  `smstxt` text NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(105)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(106)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.64
if ($cfg->version=="3.63") {
    $vr="3.64";
    echo "- добавляю таблицу lib_cable_lines для справочника кабельной структуры</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_cable_lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_calble_module` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `color1` varchar(100) NOT NULL,
  `color2` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(107)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    echo "- добавляю таблицу lib_cable_modules для справочника кабельной структуры</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_cable_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cable_id` int(11) NOT NULL,
  `number` varchar(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(108)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";    
echo "- добавляю таблицу lib_cable_name_mark для справочника кабельной структуры</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_cable_name_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mark` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(109)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(110)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";    
    $cfg->version=$vr;    
};

// обновляем до 3.65
if ($cfg->version=="3.64") {
    $vr="3.65";
    echo "- добавляю таблицу entropia для получения уникального id для разных целей</br>";
    $sql="CREATE TABLE IF NOT EXISTS `entropia` (`cnt` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(111)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- добавляю таблицу entropia для получения уникального id для разных целей</br>";
    $sql="INSERT INTO entropia (cnt) VALUES (0)";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(112)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "- добавляю хранимую процедуру GetRandomId для получения идентификатора</br>";
    $sql="CREATE  PROCEDURE `GetRandomId`()
    DETERMINISTIC
BEGIN  
	update entropia set cnt=cnt+1;
    SELECT cnt from entropia;
END ";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(113)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";


    echo "- добавляю таблицу хранения муфт lib_cable_muft</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_cable_muft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(114)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";    
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(115)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.66
if ($cfg->version=="3.65") {
    $vr="3.66";
    echo "- добавляю таблицу справочника сплитеров</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_cable_spliter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `exitcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(111)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    echo "- добавляю таблицу для хранения распайки волокон в муфте</br>";
    $sql="CREATE TABLE IF NOT EXISTS `lib_lines_in_muft` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор волокна в муфте на карте',
  `mufta_id` int(11) NOT NULL COMMENT 'Идентификатор муфты на карте',
  `obj_edit_id` int(11) NOT NULL COMMENT 'Идентификатор кабеля на карте',
  `lib_line_id` int(11) NOT NULL COMMENT 'ссылка на волокно из справочника',
  `start_id` int(11) NOT NULL COMMENT 'идентификатор стыковки начала волокна',
  `end_id` int(11) NOT NULL COMMENT 'идентификатор конца волокна',
  `type_obj` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(111)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";

    
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(115)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.67
if ($cfg->version=="3.66") {
    $vr="3.67";
    echo "- добавляю поле для хранения цвета кнопки устройства</br>";
    $sql="ALTER TABLE  `devnames` ADD  `bcolor` VARCHAR( 50 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(111)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(115)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.68
if ($cfg->version=="3.67") {
    $vr="3.68";
    echo "- добавляю поле комментария к каждому волокну</br>";
    $sql="ALTER TABLE  `lib_lines_in_muft` ADD  `comment` VARCHAR( 255 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(116)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(117)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.69
if ($cfg->version=="3.68") {
    $vr="3.69";
    echo "- добавляю еще один цвет к модулю</br>";
    $sql="ALTER TABLE  `lib_cable_modules` ADD  `color1` VARCHAR( 20 ) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(118)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(119)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};
// обновляем до 3.70
if ($cfg->version=="3.69") {
    $vr="3.70";
    echo "- добавляю поле zindex для обьектов карты</br>";
    $sql="ALTER TABLE  `lanb_maps` ADD  `zindex` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(120)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(121)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.71
if ($cfg->version=="3.70") {
    $vr="3.71";
    echo "- добавляю поле state для фиксации обьектов карты</br>";
    $sql="ALTER TABLE  `lanb_maps_coor` ADD  `state` INT NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(122)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(123)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};

// обновляем до 3.72
if ($cfg->version=="3.71") {
    $vr="3.72";
    echo "- таблицу для создания меню</br>";
    $sql="CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  `parents` int(11) NOT NULL COMMENT 'Родитель',
  `sort_id` int(11) NOT NULL COMMENT 'Сортировка',
  `name` varchar(200) NOT NULL COMMENT 'Название',
  `comment` varchar(200) NOT NULL COMMENT 'Пояснение',
  `uid` varchar(50) NOT NULL COMMENT 'некий идентификатор (можно использовать для автосоздания менюшек)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $result = $sqlcn->ExecuteSQL($sql);                   
    if ($result=='') die('(124)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));    
    echo "--ok</br>";
    
    echo "-обновляю нумерацию версии движка до $vr</br>";
    $sql="UPDATE config SET version='$vr'";
    $result = $sqlcn->ExecuteSQL($sql);               
    if ($result=='') die('(125)Не удалось обновить БД по причине: ' . mysqli_error($sqlcn->idsqlconnection));        
    echo "--ok</br>";
    $cfg->version=$vr;    
};


echo "Обновление закончено.</br>";
echo "Если сообщений об ошибках нет, удалите файл update.php.</br>";
?>
</body>
</html>


