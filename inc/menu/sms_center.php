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
    $this->Add("main","<i class=\"fa fa-commenting-o\" aria-hidden=\"true\"></i> СМС-Центр","СМС-Центр",3,"smscenter","");
     $this->Add("smscenter","<i class=\"fa fa-comments-o\" aria-hidden=\"true\"></i> Отправка СМС по списку","Отправка СМС по списку",3,"smscenter/sendbylist","smscenter/sendbylist");
     $this->Add("smscenter","<i class=\"fa fa-bar-chart\" aria-hidden=\"true\"></i> Статистика по СМС",">Статистика по СМС",3,"smscenter/smsstat","smscenter/smsstat");
     $this->Add("smscenter","<i class=\"fa fa-cogs\" aria-hidden=\"true\"></i> Настройка агентов отправки СМС","Настройка агентов отправки СМС",3,"smscenter/smsconfig","smscenter/smsconfig");     
     $this->Add("smscenter","<i class=\"fa fa-comments-o\" aria-hidden=\"true\"></i> Отправка СМС группе","Отправка СМС группе",3,"smscenter/smsconfig","smscenter/sendgroupsms");     
     $this->Add("smscenter","<i class=\"fa fa-comments-o\" aria-hidden=\"true\"></i> Управление группами","Управление группами для отправки СМС",3,"smscenter/smsconfig","smscenter/sms");          
    if ($md->IsActive("lanbilling")==1) {
      // Меню для СМС, то что касается LanBilling
	 $this->Add("smscenter","<i class=\"fa fa-comments-o\" aria-hidden=\"true\"></i> Отправка СМС группе абонетов LanBilling","Отправка СМС группе абонетов LanBilling",3,"smscenter/sendgroup","lanbilling/sms/sendgroup");     
	 $this->Add("smscenter","<i class=\"fa fa-code\" aria-hidden=\"true\"></i> Шаблоны СМС для LanBilling",">Шаблоны СМС для LanBilling",3,"smscenter/smstemplates","lanbilling/smstemplates");
	 $this->Add("smscenter","<i class=\"fa fa-bar-chart\" aria-hidden=\"true\"></i> Статистика по СМС",">Статистика по СМС",3,"smscenter/smsstat","smscenter/smsstat");  
      // end     
    };     
};
unset($md);
