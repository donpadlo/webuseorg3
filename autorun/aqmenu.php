<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$cfg->quickmenu[] = '<a title="Главная" href="index.php"><button type=\'button\' class=\'btn btn-default navbar-btn btn-sm\'><i class="fa fa-home"></i></button></a>';
//$cfg->quickmenu[] = '<a title="Справка" href="http://xn--90acbu5aj5f.xn--p1ai/wiki/" target="_blank"><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-question"></i></button></a>';
$cfg->quickmenu[] = '<a><button onclick=\'PrintableView();\' type=\'button\' title=\'Попытаться сформировать печатную форму страницы\' class=\'btn btn-default navbar-btn btn-sm\'><i class="fa fa-print"></i></button></a>';
