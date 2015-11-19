<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля

$this->Add("main","<img src=controller/client/themes/$cfg->theme/ico/application_view_list.png> Справочники","Справочники",10,"libre","");

$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/devmap.png> Список организаций","Список организаций",10,"libre/org_list","org_list");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/acclist.gif> Пользователи","Пользователи",10,"libre/pipl_list","pipl_list");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/folder_user.png> Сотрудники","Сотрудники",10,"libre/dol_list","dol_list");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/brick.png> Помещения","Помещения",10,"libre/places","places");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/newspaper.png> Контрагенты","Контрагенты",10,"libre/knt_list","knt_list");

$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/lorry.png> Производители","Производители",10,"libre/knt_list","vendors");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/add_1.gif> Группы ТМЦ","Группы ТМЦ",10,"libre/knt_list","tmc_group");
$this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/equipment.png> Номенклатура","Номенклатура",10,"libre/knt_list","nome");

$md->Register("cables", "Справочник кабелей и муфт", "Грибов Павел"); 
if ($md->IsActive("cables")==1) {
    $this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/arrow_divide.png> Типы оптических кабелей","Типы оптических кабелей",10,"libre/cables","cables/cables");
    $this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/arrow_in.png> Муфты","Муфты",10,"libre/muftes","cables/muftes");
    $this->Add("libre","<img src=controller/client/themes/$cfg->theme/ico/arrow_in.png> Спилитера","Спилитера",10,"libre/spliters","cables/spliters");        
}

unset($md);