<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if ($user->mode == 1) {
//на всякий случай создаем таблицы    
    //таблица чеков
    $sql="CREATE TABLE `online_payments` ( `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор' , `kassaid` INT NOT NULL COMMENT 'ИД кассы' , `numcheck` INT NOT NULL COMMENT 'номер чека' , `docdate` DATETIME NOT NULL COMMENT 'дата и время документа' , `summdoc` FLOAT NOT NULL COMMENT 'сумма документа' , `goodsjson` TEXT NOT NULL COMMENT 'товар в формате json' , `status` INT NOT NULL COMMENT 'текущий статус документа' , PRIMARY KEY (`id`)) ENGINE = InnoDB";
    $result = $sqlcn->ExecuteSQL($sql);
    //список касс
    $sql="CREATE TABLE `online_kkm` ( `id` INT NOT NULL AUTO_INCREMENT , `kname` VARCHAR(50) NOT NULL , `inn` VARCHAR(16) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` CHANGE `kname` `kname` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` ADD `ipaddress` INT NOT NULL AFTER `inn`, ADD `ipport` INT NOT NULL AFTER `ipaddress`, ADD `model` INT NOT NULL AFTER `ipport`, ADD `accesspass` VARCHAR(20) NOT NULL AFTER `model`, ADD `userpass` VARCHAR(20) NOT NULL AFTER `accesspass`, ADD `protocol` INT NOT NULL AFTER `userpass`, ADD `logfilename` VARCHAR(200) NOT NULL AFTER `protocol`, ADD `testmode` TINYINT NOT NULL AFTER `logfilename`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` ADD `libpath` VARCHAR(250) NOT NULL AFTER `testmode`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` ADD `version` INT NOT NULL AFTER `libpath`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` CHANGE `ipaddress` `ipaddress` VARCHAR(20) NOT NULL;";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` ADD `ppath` VARCHAR(255) NOT NULL AFTER `version`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_payments` ADD `dognum` VARCHAR(50) NOT NULL AFTER `status`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_payments` ADD `eorphone` VARCHAR(100) NOT NULL AFTER `dognum`";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_payments` ADD `fiscalSign` VARCHAR(100) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_payments` ADD `documentNumber` VARCHAR(100) NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_payments` ADD `checkdate` DATETIME NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);

    
    $sql="ALTER TABLE `online_kkm` add `kassir` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` add `innk`  VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    $sql="ALTER TABLE `online_kkm` add `eorphone`  VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $result = $sqlcn->ExecuteSQL($sql);
    
//    
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-4 col-md-4 col-sm-4">
		<table id="list2"></table>
		<div id="pager2"></div>				    
		</div>
		<div class="col-xs-8 col-md-8 col-sm-8">
		    <div id="config_online">
			    <div class="alert alert-info">Выберите Кассу для настройки дополнительных параметров</div>
		    </div>		    
		</div>
	</div>
</div>
<script type="text/javascript" src="controller/client/js/online_kkm_config.js"></script>
<?php
}
else {
    echo '<div class="alert alert-error">У вас нет доступа в данный раздел!</div>';    
};
?>