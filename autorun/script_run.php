<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 *
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
if ($md->IsActive("scriptalert")==1) {    
    $cfg->quickmenu[]='<a title="Мониторинг скриптов" href=index.php?content_page=scriptalert_mon><button type=\'button\' class=\'btn btn-default navbar-btn \'><i class="fa fa-check-square-o"></i></button></a>'; 
};
unset($md);
?>