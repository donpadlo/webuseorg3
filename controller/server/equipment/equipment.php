<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

if (isset($_GET['page']))       {$page = $_GET['page'];}
if (isset($_GET['rows']))       {$limit = $_GET['rows'];}
if (isset($_GET['sidx']))       {$sidx = $_GET['sidx'];}
if (isset($_GET['sord']))       {$sord = $_GET['sord'];}
$oper = (isset($_POST['oper'])) ? $oper = $_POST['oper'] : '';
$sorgider = (isset($_GET['sorgider'])) ? $_GET['sorgider'] : $cfg->defaultorgid;
if (isset($_POST['id']))        {$id = $_POST['id'];}
if (isset($_POST['ip']))        {$ip = $_POST['ip'];}
if (isset($_POST['name']))      {$name = $_POST['name'];}
if (isset($_POST['comment']))   {$comment = $_POST['comment'];}
if (isset($_POST['buhname']))   {$buhname = $_POST['buhname'];}
if (isset($_POST['sernum']))    {$sernum = $_POST['sernum'];}
if (isset($_POST['invnum']))    {$invnum = $_POST['invnum'];}
if (isset($_POST['shtrihkod'])) {$shtrihkod = $_POST['shtrihkod'];}
if (isset($_POST['cost']))      {$cost = $_POST['cost'];}
if (isset($_POST['currentcost'])) {$currentcost = $_POST['currentcost'];}
if (isset($_POST['os']))        {$os = $_POST['os'];}
if (isset($_POST['tmcgo']))        {$tmcgo = $_POST['tmcgo'];}
if (isset($_POST['mode']))      {$mode = $_POST['mode'];}
if (isset($_POST['mapyet']))    {$mapyet = $_POST['mapyet'];}
$mapyet = (isset($_POST['eqmapyet'])) ? $_POST['eqmapyet'] : '';
$orgid = $cfg->defaultorgid;

/////////////////////////////
// вычисляем фильтр
/////////////////////////////
// получаем наложенные поисковые фильтры
$filters = (isset($_GET['filters'])) ? $_GET['filters'] : '';
$flt = json_decode($filters, true);
$cnt = count($flt['rules']);
$where = '';
for ($i = 0; $i < $cnt; $i++) {
	$field = $flt['rules'][$i]['field'];
	if ($field == 'org.name') {
		$field = 'org.id';
	}
	$data = $flt['rules'][$i]['data'];
	if ($data != '-1') {
		if (($field == 'placesid') or ( $field == 'getvendorandgroup.grnomeid')) {
			$where = $where."($field = '$data')";
		} else {
			$where = $where."($field LIKE '%$data%')";
		}
	} else {
		$where = $where."($field LIKE '%%')";
	}
	if ($i < ($cnt - 1)) {
		$where = $where.' AND ';
	}
}
if ($where == '') {
	$where = "WHERE equipment.orgid='$sorgider'";
} else {
	$where = "WHERE $where AND equipment.orgid='$sorgider'";
}
/////////////////////////////

if ($oper == '') {
	if (!$sidx) {
		$sidx = 1;
	}
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) as count, equipment.dtendgar,
		knt.name, getvendorandgroup.grnomeid, equipment.id AS eqid,
		equipment.orgid AS eqorgid, org.name AS orgname,
		getvendorandgroup.vendorname AS vname, 
		getvendorandgroup.groupname AS grnome, places.name AS placesname,
		users_profile.fio AS fio, getvendorandgroup.nomename AS nomename,
		buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os,
		equipment.mode AS eqmode, equipment.mapyet AS eqmapyet,
		equipment.comment AS eqcomment, equipment.active AS eqactive,
		equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname,
	group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users_profile ON users_profile.usersid = equipment.usersid
	LEFT JOIN knt ON knt.id = equipment.kntid ".$where." ");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$responce = new stdClass();
	$start = $limit * $page - $limit;
	if ($start < 0) {
		$responce->page = 0;
		$responce->total = 0;
		$responce->records = 0;
		echo json_encode($responce);
		die();
	}
	$SQL = "SELECT equipment.dtendgar,tmcgo, knt.name as kntname,
		getvendorandgroup.grnomeid,equipment.id AS eqid,
		equipment.orgid AS eqorgid, org.name AS orgname,
		getvendorandgroup.vendorname AS vname, 
		getvendorandgroup.groupname AS grnome, places.name AS placesname,
		users_profile.fio AS fio, getvendorandgroup.nomename AS nomename,
		buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os,
		equipment.mode AS eqmode,equipment.mapyet AS eqmapyet,
		equipment.comment AS eqcomment, equipment.active AS eqactive,
		equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname,
	group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users_profile ON users_profile.usersid = equipment.usersid
	LEFT JOIN knt ON knt.id = equipment.kntid ".$where." 
	ORDER BY $sidx $sord LIMIT $start , $limit";
	//echo "!$SQL!";die();
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не получилось выбрать список оргтехники!'.mysqli_error($sqlcn->idsqlconnection).' sql='.$SQL);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$responce->rows[$i]['id'] = $row['eqid'];
		if ($row['eqactive'] == '1') {
			$active = '<img src="controller/client/themes/'.$cfg->theme.'/ico/accept.png">';
		} else {
			$active = '<img src="controller/client/themes/'.$cfg->theme.'/ico/cancel.png">';
		}
		if ($row['eqrepair'] == '1') {
			$active = $active.'<img src="controller/client/themes/'.$cfg->theme.'/ico/error.png">';
		}

		$os = ($row['os'] == 0) ? 'No' : 'Yes';
		$eqmode = ($row['eqmode'] == 0) ? 'No' : 'Yes';
		$eqmapyet = ($row['eqmapyet'] == 0) ? 'No' : 'Yes';

		$dtpost = MySQLDateTimeToDateTime($row['datepost']);
		$dtendgar = MySQLDateToDate($row['dtendgar']);

		$row['tmcgo'] = ($row['tmcgo'] == 0) ? 'No' : 'Yes';

		$responce->rows[$i]['cell'] = array(
			$active, $row['eqid'], $row['placesname'], $row['nomename'],
			$row['grnome'], $row['tmcgo'], $row['vname'], $row['buhname'],
			$row['sernum'], $row['invnum'], $row['shtrihkod'], $row['orgname'],
			$row['fio'], $dtpost, $row['cost'], $row['currentcost'], $os,
			$eqmode, $row['eqmapyet'], $row['eqcomment'], $row['eqrepair'],
			$dtendgar, $row['kntname']
		);
		$i++;
	}
	echo json_encode($responce);
}
if ($oper == 'edit') {
	$os = ($os == 'Yes') ? 1 : 0;
	$tmcgo = ($tmcgo == 'Yes') ? 1 : 0;
	$mode = ($mode == 'Yes') ? 1 : 0;
	$mapyet = ($mapyet == 'Yes') ? 1 : 0;
	$buhname = mysqli_real_escape_string($sqlcn->idsqlconnection, $buhname);
	$SQL = "UPDATE equipment SET buhname='$buhname',sernum='$sernum',"
			." invnum='$invnum',shtrihkod='$shtrihkod',cost='$cost',"
			." currentcost='$currentcost',os='$os',mode='$mode',"
			." mapyet='$mapyet',comment='$comment',tmcgo='$tmcgo' WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не смог обновить оргтехнику!'.mysqli_error($sqlcn->idsqlconnection));
}
if ($oper == 'add') {
	$SQL = "INSERT INTO places (id,orgid,name,comment,active) VALUES (null,'$orgid','$name','$comment',1)";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не смог вставить оргтехнику!'.mysqli_error($sqlcn->idsqlconnection));
}
if ($oper == 'del') {
	$SQL = "UPDATE equipment SET active=not active WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не смог пометить на удаление оргтехнику!'.mysqli_error($sqlcn->idsqlconnection));
}
?>