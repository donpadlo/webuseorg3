<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$page = GetDef('page');
if ($page == 0) {
	$page = 1;
}
$limit = GetDef('rows');
$sidx = GetDef('sidx', '1');
$sord = GetDef('sord');
$oper = PostDef('oper');
$sorgider = GetDef('sorgider', $cfg->defaultorgid);
$id = PostDef('id');
$ip = PostDef('ip');
$name = PostDef('name');
$comment = PostDef('comment');
$buhname = PostDef('buhname');
$sernum = PostDef('sernum');
$invnum = PostDef('invnum');
$shtrihkod = PostDef('shtrihkod');
$cost = PostDef('cost');
$currentcost = PostDef('currentcost');
$os = PostDef('os');
$tmcgo = PostDef('tmcgo');
$mode = PostDef('mode');
//$mapyet = PostDef('mapyet');
$mapyet = PostDef('eqmapyet');
$orgid = $cfg->defaultorgid;

/////////////////////////////
// вычисляем фильтр
/////////////////////////////
// получаем наложенные поисковые фильтры
$filters = GetDef('filters');
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
	$sql = "SELECT COUNT(*) as count, equipment.dtendgar,
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
	LEFT JOIN knt ON knt.id = equipment.kntid ".$where." ";        
	$result = $sqlcn->ExecuteSQL($sql);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];        
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$responce = new stdClass();
	$start = $limit * $page - $limit;
	//echo "$limit * $page - $limit\n";
	if ($start < 0) {
		$responce->page = 0;
		$responce->total = 0;
		$responce->records = 0;
		jsonExit($responce);
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
	ORDER BY $sidx $sord LIMIT $start, $limit";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не получилось выбрать список оргтехники!'.
					mysqli_error($sqlcn->idsqlconnection).' sql='.$SQL);
	//echo "$SQL\n";
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
	jsonExit($responce);
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
