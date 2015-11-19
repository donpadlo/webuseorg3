<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("smscenter", "СМС-Центр", "Грибов Павел"); 
if ($md->IsActive("smscenter")==1) {
    $this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/email.png> СМС-Центр","СМС-Центр",3,"smscenter","");
     $this->Add("smscenter","Шаблоны СМС для LanBilling",">Шаблоны СМС для LanBilling",3,"smscenter/smstemplates","lanbilling/smstemplates");
     $this->Add("smscenter","Отправка СМС группе абонетов LanBilling","Отправка СМС группе абонетов LanBilling",3,"smscenter/sendgroup","lanbilling/sms/sendgroup");
     $this->Add("smscenter","Отправка СМС по списку","Отправка СМС по списку",3,"smscenter/sendbylist","smscenter/sendbylist");
     $this->Add("smscenter","Статистика по СМС",">Статистика по СМС",3,"smscenter/smsstat","smscenter/smsstat");
     $this->Add("smscenter","Настройка агентов отправки СМС","Настройка агентов отправки СМС",3,"smscenter/smsconfig","smscenter/smsconfig");     
}
unset($md);

