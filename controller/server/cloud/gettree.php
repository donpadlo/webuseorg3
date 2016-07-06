<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

// Выполняем только при наличии у пользователя соответствующей роли
// http://грибовы.рф/wiki/doku.php/основы:доступ:роли
$user->TestRoles('1,3,4,5,6') or die('Недостаточно прав');

$foldername = GetDef('foldername');

function GetTree($key) {
	global $sqlcn;
	$sql = "SELECT * FROM cloud_dirs WHERE parent = $key";
	$result = $sqlcn->ExecuteSQL($sql)
			or die('Не могу прочитать папку! '.mysqli_error($sqlcn->idsqlconnection));
	$cnt = mysqli_num_rows($result);
	if ($cnt != 0) {
		$pz = 0;
		while ($row = mysqli_fetch_array($result)) {
			$name = $row['name'];
			$key = $row['id'];
			echo '{';
			echo "\"title\":\"$name\",\"isFolder\":true,\"key\":\"$key\",\"children\":[";
			GetTree($key);
			echo ']}';
			$pz++;
			if ($pz < $cnt) {
				echo ',';
			}
		}
	}
}

// читаю корневые папки
$sql = 'SELECT * FROM cloud_dirs WHERE parent = 0';
$result = $sqlcn->ExecuteSQL($sql)
		or die('Не могу прочитать папку! '.mysqli_error($sqlcn->idsqlconnection));
$cnt = mysqli_num_rows($result);
echo '[';
$pz = 0;
while ($row = mysqli_fetch_array($result)) {
	$name = $row['name'];
	$key = $row['id'];
	echo '{';
	echo "\"title\":\"$name\",\"isFolder\":true,\"key\":\"$key\",\"children\":[";
	GetTree($key);
	echo ']}';
	$pz++;
	if ($pz < $cnt) {
		echo ',';
	}
}
echo ']';
