<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("lanbilling", "Управление LanBilling", "Грибов Павел"); 
if ($md->IsActive("lanbilling")==1) {

$cfg->quickmenu[]='<a title="Абоненты" href=index.php?content_page=lanbilling/sos><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-users"></i></button></a>';
$cfg->quickmenu[]='<a title="Поступление платежей" href=index.php?content_page=lanbilling/reports/platent><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-money"></i></button></a>';
$cfg->quickmenu[]='<a title="Статистика пользователей" href=index.php?content_page=lanbilling/reports/statusers><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-bar-chart"></i></button></a>';
$cfg->quickmenu[]='<a title="SBSS Я менеджер (решаю задачи)" href=index.php?content_page=lanbilling/sbss/sbss_manager><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-medkit"></i></button></a>';
$cfg->quickmenu[]='<a title="SBSS Я клиент (задаю вопросы)" href=index.php?content_page=lanbilling/sbss/sbss_client><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-ambulance"></i></button></a>';
$cfg->quickmenu[]='<a title="WIKI система" href=https://noc.yarteleservice.ru/wiki/doku.php><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-wikipedia-w"></i></button></a>';
};
unset($mb);
?>