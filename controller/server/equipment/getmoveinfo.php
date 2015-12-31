<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$page = GetDef('page');
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx', '1');
$sord = GetDef('sord');
$eqid = GetDef('eqid');
$oper = PostDef('oper');
$id = PostDef('id');
$comment = PostDef('comment');

// если не задано ТМЦ по которому показываем перемещения, то тогда просто листаем последние
if ($eqid == '') {
	$where = '';
} else {
	$where = "WHERE move.eqid='$eqid'";
}

if ($oper == '') {
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count, mv.id, mv.eqid, mv.dt,
		mv.orgname1, org.name AS orgname2, mv.place1, places.name AS place2,
		mv.user1, users_profile.fio AS user2,move.comment as comment
		FROM move
			INNER JOIN (
			SELECT move.id, move.eqid, move.dt AS dt, org.name AS orgname1,
			places.name AS place1, users_profile.fio AS user1
			FROM move
			INNER JOIN org ON org.id = orgidfrom
			INNER JOIN places ON places.id = placesidfrom
			INNER JOIN users_profile ON users_profile.usersid = useridfrom
			) AS mv ON move.id = mv.id
			INNER JOIN org ON org.id = move.orgidto
			INNER JOIN places ON places.id = placesidto
			INNER JOIN users_profile ON users_profile.usersid = useridto ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$start = $limit * $page - $limit;
	$SQL = "SELECT mv.id, mv.eqid, nome.name, mv.nomeid,mv.dt, mv.orgname1,
		org.name AS orgname2, mv.place1, places.name AS place2, mv.user1,
		users_profile.fio AS user2,move.comment AS comment
		FROM move
		INNER JOIN (
		SELECT move.id, move.eqid, equipment.nomeid,move.dt AS dt,
		org.name AS orgname1, places.name AS place1, users_profile.fio AS user1
		FROM move
			INNER JOIN org ON org.id = orgidfrom
			INNER JOIN places ON places.id = placesidfrom
			INNER JOIN users_profile ON users_profile.usersid = useridfrom
			INNER JOIN equipment ON equipment.id = eqid
			) AS mv ON move.id = mv.id
			INNER JOIN org ON org.id = move.orgidto
			INNER JOIN places ON places.id = placesidto
			INNER JOIN users_profile ON users_profile.usersid = useridto
			INNER JOIN nome ON nome.id = mv.nomeid ".$where."
			ORDER BY $sidx $sord LIMIT $start, $limit";
	//echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список перемещений!'.mysqli_error($sqlcn->idsqlconnection));
	$responce = new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$responce->rows[$i]['id'] = $row['id'];
		$responce->rows[$i]['cell'] = array($row['id'], $row['dt'],
			$row['orgname1'], $row['place1'], $row['user1'], $row['orgname2'],
			$row['place2'], $row['user2'], $row['name'], $row['comment']);
		$i++;
	}
	header('Content-type: application/json');
	echo json_encode($responce);
}

if ($oper == 'edit') {
	$SQL = "UPDATE move SET comment='$comment' WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не могу обновить комментарий!'.mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'del') {
	$SQL = "DELETE FROM move WHERE id='$id'";
	$sqlcn->ExecuteSQL($SQL)
			or die('Не могу удалить запись о перемещении!'.mysqli_error($sqlcn->idsqlconnection));
}
