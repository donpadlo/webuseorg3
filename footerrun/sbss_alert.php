<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
if ($md->IsActive("lanbilling")==1) {
    $md->Register("sbssallert", "Уведомления SBSS", "Грибов Павел");
    if ($md->IsActive("sbssallert")==1) {
	if (_GET("printable")!="true"){
	    ?>
	     <script type="text/javascript" src="controller/client/js/lanbilling/sbss_alert.js"></script>
	    <?php
	};
    };
};
unset($md);