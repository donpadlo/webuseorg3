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
if (isset($_GET["orgid"]))    {$orgid=$_GET['orgid'];} else {$orgid="";};
if (isset($_GET["selpom"]))    {$selpom=$_GET['selpom'];} else {$selpom="";};

if ($selpom!='null') {$spom=" AND equipment.placesid=$selpom";} else {$spom="";};

$SQL = "SELECT equipment.os as os,equipment.mode as eqmode,equipment.datepost as dtpost,equipment.active as active,equipment.photo as photo,equipment.mapmoved as mapmoved,getvendorandgroup.grnomeid,equipment.mapx as mapx,equipment.mapy as mapy,equipment.id AS eqid,equipment.orgid AS eqorgid, org.name AS orgname, getvendorandgroup.vendorname AS vname, 
            getvendorandgroup.groupname AS grnome, places.name AS placesname, users.login AS userslogin, 
            getvendorandgroup.nomename AS nomename, buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os, equipment.mode AS eqmode, equipment.comment AS eqcomment, equipment.active AS eqactive,equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname, group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users ON users.id = equipment.usersid  WHERE equipment.orgid=$orgid and mapyet=1".$spom;


$result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать сформировать список по оргтехнике/помещениям/пользователю!".mysqli_error($sqlcn->idsqlconnection));

$i=0;
while($row = mysqli_fetch_array($result)) {
    $responce->rows[$i]['poz']=$i;       
    $responce->rows[$i]['cell']=array(
		$row['active'],$row['eqid'],$row['placesname'],$row['nomename'],$row['mapx'],$row['mapy'],
		$row['grnome'],$row['vname'],$row['buhname'],$row['sernum'],$row['invnum'],
		$row['shtrihkod'],$row['orgname'],$row['userslogin'],$row['dtpost'],$row['cost'],$row['currentcost'],$row['os'],$row['eqmode'],$row['eqcomment'],$row['eqrepair'],$row['mapmoved'],$row['photo']);
    $i++;
};

echo json_encode($responce);


?>