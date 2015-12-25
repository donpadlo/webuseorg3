<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$dis = array('.htaccess'); // Запрещённые для загрузки файлы

$selectedkey = $_POST['selectedkey'];
$uploaddir = WUO_ROOT.'/files/';

$userfile_name = basename($_FILES['filedata']['name']);
if (in_array($userfile_name, $dis)) {
	$rs = array('msg' => 'error');	
} else {
	$orig_file = $_FILES['filedata']['name'];
	$len = strlen($userfile_name);
	//$ext_file = substr($userfile_name, $len - 4, $len);
	$userfile_name = GetRandomId(5).$userfile_name;
	$uploadfile = $uploaddir.$userfile_name;

	$sr = $_FILES['filedata']['tmp_name'];
	$dest = $uploadfile;

	$res = move_uploaded_file($sr, $dest);
	if ($res != false) {
		$rs = array('msg' => "$userfile_name");
		if ($selectedkey != '') {
			$SQL = "INSERT INTO cloud_files (id, cloud_dirs_id, title, filename, dt, sz)
				VALUES (null, '$selectedkey', '$orig_file', '$userfile_name', NOW(), 0)";
			$sqlcn->ExecuteSQL($SQL) or
					die('Не могу добавить файл! '.mysqli_error($sqlcn->idsqlconnection));
		}
	} else {
		$rs = array('msg' => 'error');
	}
}

header('Content-type: application/json');
echo json_encode($rs);
