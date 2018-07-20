<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */
$md = new Tmod(); // обьявляем переменную для работы с классом модуля
if ($md->IsActive("message") == 1) {
    $printable = _GET("printable");
    if ($printable != "true") {
        ?>
	    <!--<link rel="stylesheet" href="controller/client/themes/bootstrap/css/message.css">-->
	    <script type="text/javascript" src="service/message_client.php"></script>
	    <div id='service_message_tab' class='service_message_tab'></div>
	    <div id='message_box' class='message_box'></div>
	<?php
    }
}
unset($md);
?>