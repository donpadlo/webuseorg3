<?php

/* 
 * (с) 2014 Грибов Павел
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

$selectedkey=$_POST['selectedkey'];
$uploaddir = '../../../files/';

$userfile_name=basename($_FILES['filedata']['name']);
//$sr=$userfile_name;
$orig_file=$_FILES['filedata']['name'];
$len=strlen($userfile_name);
$ext_file=substr($userfile_name,$len-4,$len);
$tmp=GetRandomId(5);
$userfile_name=$tmp.$userfile_name;
$uploadfile = $uploaddir.$userfile_name;

$sr=$_FILES['filedata']['tmp_name'];
$dest=$uploadfile;

$res=move_uploaded_file($sr,$dest);
//echo "$sr,$dest,!$res";
if ($res!=false){
     //echo "$userfile_name";
     //echo "$geteqid!";
        $rs = array("msg" => "$userfile_name");    
        if ($selectedkey!=""){
     	$SQL = "INSERT INTO cloud_files (id,cloud_dirs_id,title,filename,dt,sz) VALUES (null,'$selectedkey','$orig_file','$userfile_name',now(),0)";
	$result =$sqlcn->ExecuteSQL($SQL) or die("Не могу добавитьфайл!".mysqli_error($sqlcn->idsqlconnection));
        };
     } else {        $rs = array("msg" => 'error');    };
echo json_encode($rs);
 ?>

