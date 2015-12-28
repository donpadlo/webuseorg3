<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$orgid = $cfg->defaultorgid;
$addnone = GetDef('addnone');

if ($user->TestRoles('1,4,5,6')) {
	$SQL = 'SELECT * FROM group_nome WHERE active=1 ORDER BY binary(name)';
	$result = $sqlcn->ExecuteSQL($SQL)
			or die('Не могу выбрать список групп!'.mysqli_error($sqlcn->idsqlconnection));
	$sts = '<select name="sgroupname" id="sgroupname">';
	if ($addnone == 'true') {
		$sts .= '<option value="-1" >нет выбора</option>';
	}
	while ($row = mysqli_fetch_array($result)) {
		$vln = $row['name'];
		$vlid = $row['id'];
		$sts .= '<option value="'.$vlid.'">'.$vln.'</option>';
	}
	$sts .= '</select>';
	echo $sts;
} else {
	echo 'Не достаточно прав!!!';
}
