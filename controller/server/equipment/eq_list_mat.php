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


$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx'); 
$sord = _GET('sord'); 
$oper= _POST('oper');
$curuserid = _GET('curuserid');
$id = _POST('id');

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count ,name as grname,res2.* FROM group_nome INNER JOIN (SELECT places.name as plname,res.* FROM places INNER JOIN(
                SELECT name AS namenome,nome.groupid as grpid, eq . *  FROM nome INNER JOIN (
                SELECT equipment.id AS eqid, equipment.placesid AS plid, equipment.nomeid AS nid, equipment.buhname AS bn, equipment.cost AS cs, equipment.currentcost AS curc, equipment.invnum, equipment.sernum, equipment.shtrihkod, equipment.mode, equipment.os FROM equipment 
                WHERE equipment.active =1 and equipment.usersid='$curuserid')
                AS eq ON nome.id = eq.nid)
                AS res ON places.id=res.plid)   AS res2 ON group_nome.id=res2.grpid");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT name as grname,res2.* FROM group_nome INNER JOIN (SELECT places.name as plname,res.* FROM places INNER JOIN(
                SELECT name AS namenome,nome.groupid as grpid, eq . *  FROM nome INNER JOIN (
                SELECT equipment.id AS eqid, equipment.placesid AS plid, equipment.nomeid AS nid, equipment.buhname AS bn, equipment.cost AS cs, equipment.currentcost AS curc, equipment.invnum, equipment.sernum, equipment.shtrihkod, equipment.mode, equipment.os FROM equipment 
                WHERE equipment.active =1 and equipment.usersid='$curuserid')
                AS eq ON nome.id = eq.nid)
                AS res ON places.id=res.plid ORDER BY $sidx $sord LIMIT $start , $limit)   AS res2 ON group_nome.id=res2.grpid";
        //echo "!$SQL!";            
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать сформировать список по оргтехнике/помещениям/пользователю!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['eqid'];    
            $responce->rows[$i]['cell']=array($row['eqid'],$row['plname'],$row['namenome'],$row['grname'],$row['invnum'],$row['sernum'],$row['shtrihkod'],$row['mode'],$row['os'],$row['cs'],$row['curc'],$row['bn']);
	    $i++;
	}
	echo json_encode($responce);
};

?>