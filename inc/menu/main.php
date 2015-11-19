<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/house.png> Главная","Переход на стартовую страницу",0,"/","home");

$this->Add("main","<img src='controller/client/themes/$cfg->theme/ico/wrench_orange.png'> Настройка","Общая настройка системы",20,"config","");
 $this->Add("config", "<img src=controller/client/themes/$cfg->theme/ico/wrench.png> Настройка системы", "Настройка системы", 0, "config/config","config");
 $this->Add("config", "<img src=controller/client/themes/$cfg->theme/ico/disconnect.png> Подключенные модули", "Подключенные модули", 0, "config/modules","modules");
 $this->Add("config", "<img src=controller/client/themes/$cfg->theme/ico/bin.png> Удаление обьектов", "Удаление обьектов", 0, "config/delete","delete");                    
