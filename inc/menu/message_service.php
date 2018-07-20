<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф
global $sqlcn;
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("message", "Сервис сообщений", "Грибов Павел"); 
if ($md->IsActive("message")==1) {
 $this->Add("config","<i class='fa fa-comments fa-fw'> </i>","Настройка сообщений","Сервис сообщений",3,"config/message_config","message_config");        
};
unset($md);