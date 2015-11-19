<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

// запускаем поочередно все скрипты из папки footerrun
$mfiles=GetArrayFilesInDir("footerrun");
foreach ($mfiles as &$fname) {
    include_once("footerrun/$fname");
}
unset($fname);

?>