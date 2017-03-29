<?php

/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
    if ($md->IsActive("schedule")==1) {
	if (_GET("printable")!="true"){
	    ?>
		<script type="text/javascript" src="controller/client/js/schedule_alert.js?random_id=<?php echo GetRandomId(5);?>"></script>
	    <?php
	};
    };
unset($md);