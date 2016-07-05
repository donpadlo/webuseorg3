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
    $cfg->quickmenu[]='<div><i class="fa fa-check-square-o"></i> <a href=index.php?content_page=scriptalert_mon>Мониторинг скриптов</a></div>'; 
};
unset($md);
?>