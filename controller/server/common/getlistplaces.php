<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$orgid = GetDef('orgid', '1');
$placesid = GetDef('placesid', '1');
$addnone = GetDef('addnone');

$SQL = "SELECT * FROM places WHERE orgid='$orgid' AND active=1 ORDER BY name";
$result = $sqlcn->ExecuteSQL($SQL)
		or die('Не могу выбрать список помещений! '.mysqli_error($sqlcn->idsqlconnection));
$sts = '<select class="chosen-select" name="splaces" id="splaces">';
if ($addnone == 'true') {
	$sts .= '<option value="-1" >нет выбора</option>';
}
while ($row = mysqli_fetch_array($result)) {
	$sts .= '<option value="'.$row['id'].'"';
	if ($placesid == $row['id']) {
		$sts .= ' selected';
	}
	$sts .= '>'.$row['name'].'</option>';
}
$sts .= '</select>';
echo $sts;
