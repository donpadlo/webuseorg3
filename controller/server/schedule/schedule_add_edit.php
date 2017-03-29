<?php

/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$schedule_mode=  _POST("schedule_mode");
$id=  _POST("id");
$schedule_title=  _POST("schedule_title");
$schedule_comment=  _POST("schedule_comment");
$schedule_dtstart=  _POST("schedule_dtstart");
$schedule_dtend=  _POST("schedule_dtend");
$schedule_sms=  _POST("schedule_sms");
$schedule_mail=  _POST("schedule_mail");
$schedule_messaqe=  _POST("schedule_messaqe");

$schedule_title = mysqli_real_escape_string($sqlcn->idsqlconnection, $schedule_title);
$schedule_comment = mysqli_real_escape_string($sqlcn->idsqlconnection, $schedule_comment);

if ($schedule_dtend==""){$schedule_dtend="9999-01-01 23:59:59";};

if ($schedule_messaqe=="true"){$schedule_messaqe=1;} else {$schedule_messaqe=0;};
if ($schedule_mail=="true"){$schedule_mail=1;} else {$schedule_mail=0;};
if ($schedule_sms=="true"){$schedule_sms=1;} else {$schedule_sms=0;};

switch ($schedule_mode) {
	case "add":
	    $sql="insert into schedule (id,dtstart,dtend,title,comment,sms,mail,view) values (null,'$schedule_dtstart','$schedule_dtend','$schedule_title','$schedule_comment',$schedule_sms,$schedule_mail,$schedule_messaqe)";
	    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу создать schedule!".mysqli_error($sqlcn->idsqlconnection));    
	    echo "Успешно сохранено!";
	break;
	case "edit":
	    $sql="update schedule set dtstart='$schedule_dtstart',dtend='$schedule_dtend',title='$schedule_title',comment='$schedule_comment',sms=$schedule_sms,mail=$schedule_mail,view=$schedule_messaqe where id=$id";
	    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу обновить schedule!".mysqli_error($sqlcn->idsqlconnection));    
	    echo "Успешно обновлено!";
	break;
};