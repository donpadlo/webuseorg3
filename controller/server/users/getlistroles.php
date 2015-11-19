<?php

/* 
 * (с) 2014 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

   $sts="<select name=rolesusers id=rolesusers>";
        $sts=$sts."<option value=1>Полный доступ</option>";
        $sts=$sts."<option value=2>Просмотр финансовых отчетов</option>";
        $sts=$sts."<option value=3>Просмотр количественных отчетов</option>";
        $sts=$sts."<option value=4>Добавление</option>";
        $sts=$sts."<option value=5>Редактирование</option>";
        $sts=$sts."<option value=6>Удаление</option>";
        $sts=$sts."<option value=7>Отправка СМС</option>";
        $sts=$sts."<option value=8>Манипуляции с деньгами</option>";
        $sts=$sts."<option value=9>Редактирование карт</option>";
   $sts=$sts.'</select>';   
 echo $sts;    

 ?>