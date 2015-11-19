<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
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
$responce=new stdClass();
if (isset($_GET["eqid"]))    {$eqid=$_GET['eqid'];} else {$eqid="";};
if (isset($_GET["coor"]))    {$coor=$_GET['coor'];} else {$coor="";};
//print_r($coor);
$x=$coor[0][1];
$y=$coor[0][0];
//echo "$zx";
    $SQL = "UPDATE equipment SET mapx='$x',mapy='$y',mapmoved=0 WHERE id='$eqid'";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу координаты ТМЦ!".mysqli_error($sqlcn->idsqlconnection));
?>