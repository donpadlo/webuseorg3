<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 


$this->Add("main","<i class='fa fa-cog fa-fw'> </i>Инструменты","Инструменты",3,"tools","");

$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 
if ($md->IsActive("bprocess")==1) {
    $this->Add("tools","<i class='fa fa-tasks fa-fw'> </i>Мои БП","Бизнеспроцессы",3,"tools/mybp","mybp");
}; 

$md->Register("ical", "Календарь", "Грибов Павел"); 
if ($md->IsActive("ical")==1) {
   $this->Add("tools","<i class='fa fa-calendar fa-fw'> </i>Мой календарь","Мой календарь",3,"tools/myical","myical");
} 

$md->Register("tasks", "Задачи", "Грибов Павел"); 
if ($md->IsActive("tasks")==1) {
    $this->Add("tools","<i class='fa fa-tasks fa-fw'> </i>Мои задачи","Мои задачи",3,"tools/mytasks","mytasks");
} 

$md->Register("workmen", "Менеджер по обслуживанию ", "Грибов Павел"); 
if ($md->IsActive("workmen")==1) {
   $this->Add("tools","<i class='fa fa-bug fa-fw'> </i>Менеджер по обслуживанию","Менеджер по обслуживанию",3,"tools/workmen","workmen");
};

   $this->Add("tools","<i class='fa fa-check fa-fw'> </i>Контроль договоров","Контроль договоров",3,"tools/dog_knt","dog_knt");
   $this->Add("tools","<i class='fa fa-clone fa-fw'> </i>ТМЦ на моем рабочем месте","ТМЦ на моем рабочем месте",3,"tools/eq_list","eq_list");
   
$md->Register("ping", "Проверка доступности ТМЦ по ping", "Грибов Павел"); 
// если модуль ping активирован, то тогда показываем пункт меню
if ($md->IsActive("ping")==1) {
    $this->Add("tools","<i class='fa fa-bolt fa-fw'> </i>Проверка доступности ТМЦ","Проверка доступности ТМЦ",3,"tools/ping","ping");
}; 










unset($md);