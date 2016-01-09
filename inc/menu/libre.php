<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля

$this->Add("main","<i class='fa fa-list-ul fa-fw'> </i>Справочники","Справочники",10,"libre","");

$this->Add("libre","<i class='fa fa-sitemap fa-fw'> </i>Список организаций","Список организаций",10,"libre/org_list","org_list");
$this->Add("libre","<i class='fa fa-users fa-fw'> </i>Пользователи","Пользователи",10,"libre/pipl_list","pipl_list");
$this->Add("libre","<i class='fa fa-user-plus fa-fw'> </i>Сотрудники","Сотрудники",10,"libre/dol_list","dol_list");
$this->Add("libre","<i class='fa fa-location-arrow fa-fw'> </i>Помещения","Помещения",10,"libre/places","places");
$this->Add("libre","<i class='fa fa-cogs fa-fw'> </i>Контрагенты","Контрагенты",10,"libre/knt_list","knt_list");

$this->Add("libre","<i class='fa fa-cubes fa-fw'> </i>Производители","Производители",10,"libre/knt_list","vendors");
$this->Add("libre","<i class='fa fa-object-group fa-fw'> </i>Группы ТМЦ","Группы ТМЦ",10,"libre/knt_list","tmc_group");
$this->Add("libre","<i class='fa fa-empire fa-fw'> </i>Номенклатура","Номенклатура",10,"libre/knt_list","nome");

$md->Register("cables", "Справочник кабелей и муфт", "Грибов Павел"); 
if ($md->IsActive("cables")==1) {
    $this->Add("libre","<i class='fa fa-arrows fa-fw'> </i>Типы оптических кабелей","Типы оптических кабелей",10,"libre/cables","cables/cables");
    $this->Add("libre","<i class='fa fa-archive fa-fw'> </i>Муфты","Муфты",10,"libre/muftes","cables/muftes");
    $this->Add("libre","<i class='fa fa-arrows-h fa-fw'> </i>Сплитера","Сплитера",10,"libre/spliters","cables/spliters");        
}

unset($md);