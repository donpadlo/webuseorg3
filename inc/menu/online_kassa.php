<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф
global $sqlcn;
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("onlinekassa", "Онлайн кассы Атол", "Грибов Павел"); 
if ($md->IsActive("onlinekassa")==1) {
 $this->Add("main","<i class='fa fa-heartbeat fa-fw'> </i>","Онлайн кассы Атол","Настройка и работа с онлайн кассами",4,"online_kassa","");            
 $this->Add("online_kassa","<i class='fa fa-heartbeat fa-fw'> </i>","Настройка касс","Настройка онлайн касс",3,"online_kassa/online_kassa_config","online_kassa");        
 $this->Add("online_kassa","<i class='fa fa-heartbeat fa-fw'> </i>","Очередь чеков","Очередь чеков онлайн касс",3,"online_kassa/online_kassa_qwery","online_kassa_qwery");        
};
unset($md);