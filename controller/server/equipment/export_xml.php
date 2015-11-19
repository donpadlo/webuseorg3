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

//Создает XML-строку и XML-документ при помощи DOM 
$dom = new DomDocument('1.0', 'UTF-8'); 

$orguse = $dom->appendChild($dom->createElement('orguse'));

 $sql="SELECT equipment.id AS eqid, equipment.orgid AS eqorgid, org.name AS orgname, getvendorandgroup.vendorname AS vname, getvendorandgroup.groupname AS grnome, places.name AS placesname, users.login AS userslogin, getvendorandgroup.nomename AS nomenamez, buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os, equipment.mode AS eqmode, equipment.comment AS eqcomment, equipment.active AS eqactive
FROM equipment
INNER JOIN (

SELECT nome.id AS nomeid, vendor.name AS vendorname, group_nome.name AS groupname, nome.name AS nomename
FROM nome
INNER JOIN group_nome ON nome.groupid = group_nome.id
INNER JOIN vendor ON nome.vendorid = vendor.id
) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
INNER JOIN org ON org.id = equipment.orgid
INNER JOIN places ON places.id = equipment.placesid
INNER JOIN users ON users.id = equipment.usersid
WHERE equipment.active =1";
        $result = $sqlcn->ExecuteSQL( $sql ) or die("Не получилось выбрать список оргтехники!".mysqli_error($sqlcn->idsqlconnection));

while($row = mysqli_fetch_array($result)) {               
    $orgtehnika = $orguse->appendChild($dom->createElement('orgtehnika'));
        $orgid=$orgtehnika->appendChild($dom->createElement('orgid'));
         $orgid->appendChild($dom->createTextNode("$row[eqorgid]")); 
        $namehouses=$orgtehnika->appendChild($dom->createElement("namehouses"));
         $namehouses->appendChild($dom->createTextNode("$row[placesname]")); 
        $nomename=$orgtehnika->appendChild($dom->createElement('nomename'));
         $nomename->appendChild($dom->createTextNode("$row[nomenamez]")); 
        $buhname=$orgtehnika->appendChild($dom->createElement('buhname'));
         $buhname->appendChild($dom->createTextNode("$row[buhname]")); 
        $invnum=$orgtehnika->appendChild($dom->createElement('invnum'));
         $invnum->appendChild($dom->createTextNode("$row[invnum]")); 
        $shtrihkod=$orgtehnika->appendChild($dom->createElement('shtrihkod'));
         $shtrihkod->appendChild($dom->createTextNode("$row[shtrihkod]")); 
        $spisano=$orgtehnika->appendChild($dom->createElement('spisano'));
         $spisano->appendChild($dom->createTextNode("$row[eqmode]")); 
        $os=$orgtehnika->appendChild($dom->createElement('os'));
         $os->appendChild($dom->createTextNode("$row[os]")); 
    };
        
$dom->formatOutput = true; // установка атрибута formatOutput

$content = $dom->saveXML(); // передача строки 
if(!$content) exit("Нечего сохранять");

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename=export.xml');
header('Content-Transfer-Encoding: binary');
header('Content-Length: '.strlen($content));
echo $content;


?>