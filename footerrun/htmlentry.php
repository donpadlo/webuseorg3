<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

// сей модуль вставляет в футер некий html текст

include_once("class/cconfig.php");                    // загружаем первоначальные настройки

$buz=new Tcconfig;
$htmlentry=$buz->GetByParam("htmlentry"); //соответствие
unset($buz);
echo "$htmlentry";