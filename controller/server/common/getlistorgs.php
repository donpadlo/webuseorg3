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
include_once("../../../inc/login.php");		// соеденяемся с БД, получаем $mysql_base_id

$orgid = $cfg->defaultorgid;
if (isset($_GET["addnone"])) {$addnone=$_GET['addnone'];};

if ($user->mode=="1")
{
    $SQL = "SELECT * FROM org WHERE active=1 ORDER BY binary(name)";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список организаций!".mysqli_error($sqlcn->idsqlconnection));
    $sts="<select name=sogrsname id=sorgsname>";
    if ($addnone=='true'){$sts=$sts."<option value='-1' >нет выбора</option>";};
    while($row = mysqli_fetch_array($result)) {
         $vln=$row['name'];
         $vlid=$row['id'];
         $sts=$sts."<option value=$vlid>$vln</option>";
	};
    $sts=$sts."</select>";
 echo $sts;
} else {echo "Не достаточно прав!!!";}

?>