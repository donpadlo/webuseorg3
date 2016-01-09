<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$this->Add("main","<i class='fa fa-home fa-fw'> </i>Главная","Переход на стартовую страницу",0,"/","home");

$this->Add("main","<i class='fa fa-cog fa-fw'> </i>Настройка","Общая настройка системы",20,"config","");
 $this->Add("config", "<i class='fa fa-cog fa-fw'> </i>Настройка системы", "Настройка системы", 0, "config/config","config");
 $this->Add("config", "<i class='fa fa-modx fa-fw'> </i>Подключенные модули", "Подключенные модули", 0, "config/modules","modules");
 $this->Add("config", "<i class='fa fa-trash fa-fw'> </i>Удаление обьектов", "Удаление обьектов", 0, "config/delete","delete");                    
