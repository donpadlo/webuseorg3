<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

if ($user->TestRoles("1,2,3,4,5,6")) {
	$md = new Tmod; // обьявляем переменную для работы с классом модуля
	$md->Register("arduinorele", "Управление реле Arduino", "Грибов Павел");
	if ($md->IsActive("arduinorele") == 1) {
		unset($md);
		$this->Add("main","<i class='fa fa-plug fa-fw'> </i>", "Управление питанием", "Управление питанием блоков реле Arduino", 21, "arduinorele", "",false,true);
		$this->Add("arduinorele","<i class='fa fa-plug fa-fw'> </i>", "Управление", "Управление питанием блоков реле Arduino", 21, "arduinorele/control", "arduinorele/control");
		$this->Add("arduinorele","<i class='fa fa-cog fa-fw'> </i>", "Настройка", "Управление питанием блоков реле Arduino", 21, "arduinorele/control", "arduinorele/config");		
	}
}
