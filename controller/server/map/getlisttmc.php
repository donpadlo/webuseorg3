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
if (isset($_GET["placesid"]))    {$placesid=$_GET['placesid'];} else {$placesid="";};
if (isset($_GET["addnone"]))    {$addnone=$_GET['addnone'];} else {$addnone="";};

$SQL = "SELECT nome.id as nid,nome.name as nomename,eq.id as eqid FROM nome INNER JOIN (SELECT nomeid,id FROM equipment WHERE placesid='$placesid') as eq ON nome.id=eq.nomeid";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список помещений!".mysqli_error($sqlcn->idsqlconnection));
    $sts="<select name=splacestmc id=splacestmc size=10>";
    if ($addnone=='true'){$sts=$sts."<option value='-1' >нет выбора</option>";};
    while($row = mysqli_fetch_array($result)) {
         $sts=$sts."<option value=$row[eqid] ";
	  if ($placesid==$row['eqid']){$sts=$sts."selected";};
          $nm=$row['nomename'];
	 $sts=$sts.">$nm</option>";
	};
    $sts=$sts."</select>";   
 echo $sts;    


?>

<script type="text/javascript" src="controller/client/js/mapeq.js"></script>