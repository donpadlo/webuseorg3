<?php
/*
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */

// создаем структуру базы данных
$sql = "CREATE TABLE `arduino_rele_config` ( `id` INT NOT NULL AUTO_INCREMENT , `ip` VARCHAR(255) NOT NULL , `roles` VARCHAR(255) NOT NULL , `comment` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`))";
$result = $sqlcn->ExecuteSQL($sql);

$sql = "ALTER TABLE `arduino_rele_config` CHANGE `comment` `comment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);

$sql = "ALTER TABLE `arduino_rele_config` ADD `foot` TEXT NOT NULL AFTER `comment`;";
$result = $sqlcn->ExecuteSQL($sql);

$sql = "ALTER TABLE `arduino_rele_config` CHANGE `foot` `foot` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);

if (($user->mode == 1) or ($user->TestRoles("1") == true)) {
    ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<table id="list2"></table>
			<div id="pager2"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controller/client/js/arduino_rele/config.js"></script>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}

?>