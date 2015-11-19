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

if (isset($_GET["groupid"]))    {$id=$_GET["groupid"];}             else {$id=1;};
if (isset($_GET["vendorid"]))   {$vendorid=$_GET["vendorid"];}      else {$vendorid=1;};
if (isset($_GET["addnone"]))    {$addnone=$_GET["addnone"];}        else {$addnone="";};

    $SQL = "SELECT vendorid, vendor.name, COUNT( nome.id ) FROM nome INNER JOIN vendor ON vendor.id = vendorid WHERE groupid ='$id' GROUP BY vendorid";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
    $sts="<select class='chosen-select' name=svendid id=svendid>";
    if ($addnone=='true'){$sts=$sts."<option value='-1' >нет выбора</option>";};
    while($row = mysqli_fetch_array($result)) {
         $sts=$sts."<option value=".$row["vendorid"]." ";
	 if ($vendorid==$row["vendorid"]){$sts=$sts."selected";};
	 $sts=$sts.">".$row["name"]."</option>";
	};
    $sts=$sts."</select>
";
 echo $sts;

?>