<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

/*
 * Запускаем поочередно все скрипты из папки autorun
 */
$mfiles = GetArrayFilesInDir('autorun');
foreach ($mfiles as &$fname) {
	include_once("autorun/$fname");
}
unset($fname);
?>