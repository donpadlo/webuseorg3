<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

if ($user->TestRoles("1,5")==true){
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("astra", "Управление серверами Astra", "Грибов Павел"); 
    if ($md->IsActive("astra")==1) {
    unset($md);
     $this->Add("main","<img src='controller/client/themes/$cfg->theme/ico/film.png'> Astra","Настройка серверов Астра",2,"astra","");    
      $this->Add("astra","Мониторинг","Настройка серверов Астра",2,"astra/mon","astra/mon");    
      $this->Add("astra","Инфоканал","Настройка серверов Астра",2,"astra/pic","astra/pic");    
      $this->Add("astra","Список серверов","Настройка серверов Астра",2,"astra/config","astra/config");    
    };
};