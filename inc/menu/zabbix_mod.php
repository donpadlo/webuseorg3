<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
if ($md->IsActive("zabbix-mon")==1) {
 $this->Add("config","","Настройка серверов Zabbix","Dashboard Zabbix",3,"config/zabbix_mod_config","zabbix_mod_config");        
};
unset($md);