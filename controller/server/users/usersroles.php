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
$id = PostDef('id');
$role = PostDef('role');
$userid = GetDef('userid');
$oper = PostDef('oper');

// Роли
$roles = array(
	'1' => 'Полный доступ',
	'2' => 'Просмотр финансовых отчетов',
	'3' => 'Просмотр количественных отчетов',
	'4' => 'Добавление',
	'5' => 'Редактирование',
	'6' => 'Удаление',
	'7' => 'Отправка СМС',
	'8' => 'Манипуляции с деньгами',
	'9' => 'Редактирование карт'
);

if ($oper == '') {
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM usersroles where userid='$userid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$start = $limit * $page - $limit;
	$SQL = "SELECT * FROM usersroles where userid='$userid' ORDER BY $sidx $sord LIMIT $start, $limit";
	//echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список ролей пользователей!'.mysqli_error($sqlcn->idsqlconnection));
	$responce = new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$responce->rows[$i]['id'] = $row['id'];
		$role = $roles[$row['role']];
		$responce->rows[$i]['cell'] = array($row['id'], $role);
		$i++;
	}
	header('Content-type: application/json');
	echo json_encode($responce);
}

if ($oper == 'add') {
	$SQL = "INSERT INTO usersroles (userid,role) VALUES ('$userid','$role')";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу добавить роль пользователя!'.mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'del') {
	$SQL = "DELETE FROM usersroles where id='$id'";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу удалить роль пользователя!'.mysqli_error($sqlcn->idsqlconnection));
}
