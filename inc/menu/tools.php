<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 


$this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/computer.png> Инструменты","Инструменты",3,"tools","");

$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 
if ($md->IsActive("bprocess")==1) {
    $this->Add("tools","Мои БП","Бизнеспроцессы",3,"tools/mybp","mybp");
}; 

if ($md->IsActive("ical")==1) {
   $this->Add("tools","<img src=controller/client/themes/$cfg->theme/ico/date.png> Мой календарь","Мой календарь",3,"tools/myical","myical");
} 

if ($md->IsActive("tasks")==1) {
    $this->Add("tools","Мои задачи","Мои задачи",3,"tools/mytasks","mytasks");
} 

$md->Register("workmen", "Менеджер по обслуживанию ", "Грибов Павел"); 
if ($md->IsActive("workmen")==1) {
   $this->Add("tools","Менеджер по обслуживанию","Менеджер по обслуживанию",3,"tools/workmen","workmen");
};

   $this->Add("tools","<img src=controller/client/themes/$cfg->theme/ico/report_go.png> Контроль договоров","Контроль договоров",3,"tools/dog_knt","dog_knt");
   $this->Add("tools","<img src=controller/client/themes/$cfg->theme/ico/report_user.png> ТМЦ на моем рабочем месте","ТМЦ на моем рабочем месте",3,"tools/eq_list","eq_list");
   
$md->Register("ping", "Проверка доступности ТМЦ по ping", "Грибов Павел"); 
// если модуль ping активирован, то тогда показываем пункт меню
if ($md->IsActive("ping")==1) {
    $this->Add("tools","Проверка доступности ТМЦ","Проверка доступности ТМЦ",3,"tools/ping","ping");
}; 










unset($md);