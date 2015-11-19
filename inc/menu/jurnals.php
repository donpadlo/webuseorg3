<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 

$this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/book_open.png> Журналы","Журналы",3,"doc","");
if ($md->IsActive("bprocess")==1) {
  $this->Add("doc","Бизнес-процессы","Бизнес-процессы",3,"doc/bp","bp");
};  
if ($md->IsActive("news")==1) {
  $this->Add("doc","Новости","Новости",3,"doc/news","news");
};  
  $this->Add("doc","<img src=controller/client/themes/$cfg->theme/ico/monitor_lightning.png> Имущество","Имущество",3,"doc/equipment","equipment");

unset($md);

?>