<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("bprocess", "Бизнес-процессы", "Грибов Павел"); 

$this->Add("main","<span><i class='fa fa-hashtag fa-fw'> </i>Журналы</span>","Журналы",3,"doc","");
if ($md->IsActive("bprocess")==1) {
  $this->Add("doc","<i class='fa fa-tasks fa-fw'> </i>Бизнес-процессы","Бизнес-процессы",3,"doc/bp","bp");
};  
if ($md->IsActive("news")==1) {
  $this->Add("doc","<span><i class='fa fa-newspaper-o fa-fw'> </i>Новости</span>","Новости",3,"doc/news","news");
};  
  $this->Add("doc","<span><i class='fa fa-empire fa-fw'> </i>Имущество</span>","Имущество",3,"doc/equipment","equipment");

unset($md);

?>