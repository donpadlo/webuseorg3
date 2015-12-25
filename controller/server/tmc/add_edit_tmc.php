<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

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

$step=GetDef("step");
$id = GetDef('id');
$name= PostDef('name');
$comment= PostDef('comment');

    $groupid=PostDef("groupid");
    if ($groupid==""){$err[]="Не выбрана группа!";};
    $vendorid=PostDef("vendorid");
    if ($vendorid==""){$err[]="Не задан производитель!";};
    $namenome=PostDef("namenome");
    if ($namenome==""){$err[]="Не задано наименование!";};

if (count($err)==0){               
if ($step=='edit'){
        $sql="UPDATE nome SET groupid='$groupid',vendorid='$vendorid',name='$namenome' WHERE id='$id'";                                      
  	$result = $sqlcn->ExecuteSQL($sql);                
  	if ($result==''){die('Не смог обновить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));}        
};
if ($step=='add'){
        $sql="INSERT INTO nome (id,groupid,vendorid,name,active) VALUES (NULL,'$groupid','$vendorid','$namenome','1')";                                      
  	$result = $sqlcn->ExecuteSQL($sql);                
  	if ($result==''){die('Не смог добавить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));}
};
};
echo "ok";
?>