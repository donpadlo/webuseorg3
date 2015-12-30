<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции


$blibase=PostDef('blibase');
$pdata=PostDef('pdata');

$arr=  explode("\n", $pdata);
//var_dump($arr);
foreach ($arr as $value) {
    $st=$value;
    $arr2=  explode(";", $st);
    $mobile="";
    $smstxt="";
    if (isset($arr2[0])) $mobile=$arr2[0];
    if (isset($arr2[1])) $smstxt=$arr2[1];
    if ($smstxt!=""){
        if ($mobile[0]=="8"){$mobile[0]="7";};
        $SQL = "INSERT INTO sms_by_list (mobile,smstxt,status) VALUES ('$mobile','$smstxt','')";        
        $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить текст СМС!".mysqli_error($sqlcn->idsqlconnection));
    };

}

?>