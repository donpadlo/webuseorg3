<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("devicescontrol", "Управление устройствами", "Грибов Павел"); 
if ($md->IsActive("devicescontrol")==1) {
unset($md);
  $this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/connect.png> Управление устройствами","Управление устройствами",3,"devicescontrol","");
    $this->Add("devicescontrol","Управление устройствами","Управление устройствами",3,"devicescontrol/deviceslist","devicescontrol/deviceslist");
    $this->Add("devicescontrol","Настройка устройств","Настройка устройств",3,"devicescontrol/devicesconfig","devicescontrol/devicesconfig");
};    