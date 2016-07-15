<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$page = GetDef('page');
if (empty($page)) {
	$page = 1;
}
$limit = GetDef('rows');
$sidx = GetDef('sidx', '1');
$sord = GetDef('sord');
$oper = PostDef('oper');
$id = PostDef('id');
$title = PostDef('title');
$cloud_dirs_id = GetDef('cloud_dirs_id');

if ($oper == '') {
	// Проверяем может ли пользователь просматривать?
	$user->TestRoles('1,3,4,5,6') or die('Недостаточно прав');

	$sql = "SELECT COUNT(*) AS count FROM cloud_files WHERE cloud_dirs_id='$cloud_dirs_id'";
	$result = $sqlcn->ExecuteSQL($sql)
			or die('Не могу выбрать количество записей! '.mysqli_error($lb->idsqlconnection));
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
	$total_pages = ($count > 0) ? ceil($count / $limit) : 0;
	if ($page > $total_pages) {
		$page = $total_pages;
	}
	$start = $limit * $page - $limit;
	$SQL = "SELECT * FROM cloud_files WHERE cloud_dirs_id='$cloud_dirs_id' ORDER BY $sidx $sord LIMIT $start, $limit";
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список файлов! '.mysqli_error($sqlcn->idsqlconnection));
	$responce = new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while ($row = mysqli_fetch_array($result)) {
		$responce->rows[$i]['id'] = $row['id'];

		switch (pathinfo($row['filename'], PATHINFO_EXTENSION)) {
			case 'jpeg':
			case 'jpg':
			case 'png':
				$ico = '<i class=\"fa fa-file-image-o\" aria-hidden=\"true\"></i>';
				break;
			case 'xls':
			case 'ods':
				$ico = '<i class=\"fa a-file-excel-o\" aria-hidden=\"true\"></i>';
				break;
			case 'doc':
			case 'odt':
				$ico = '<i class=\"fa fa-file-word-o\" aria-hidden=\"true\"></i>';
				break;
			default:
				$ico = '<i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i>';
		}
		//$ico = "<a target='_blank' href='files/".$row['filename']."'>".$ico."</a>";
		$ico = '<a target="_blank" href="index.php?route=/controller/server/cloud/download.php?id='.$row['id'].'">'.$ico.'</a>';
		$title = $row['title'];
		$responce->rows[$i]['cell'] = array($row['id'], $ico, $title, $row['dt'], human_sz($row['sz']));
		$i++;
	}
	jsonExit($responce);
}

if ($oper == 'edit') {
	// Проверяем может ли пользователь редактировать?
	$user->TestRoles('1,5') or die('Для редактирования не хватает прав!');
	$sql = "UPDATE cloud_files SET title='$title' WHERE id='$id'";
	$sqlcn->ExecuteSQL($sql)
			or die('Не могу выполнить запрос! '.mysqli_error($lb->idsqlconnection));
	exit;
}

if ($oper == 'del') {
	$user->TestRoles('1,6') or die('Для удаления не хватает прав!');
	$sql = "DELETE FROM cloud_files WHERE id='$id'";
	$sqlcn->ExecuteSQL($sql)
			or die('Не могу выполнить запрос! '.mysqli_error($lb->idsqlconnection));
	exit;
}
