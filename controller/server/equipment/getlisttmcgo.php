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
	$sts = '<select name="tmcgo" id="tmcgo">';
	if ($addnone == 'true') {
		$sts .= '<option value="-1">нет выбора</option>';
	}
	$sts .= '<option value="0">На месте</option>';
	$sts .= '<option value="1">В пути</option>';
	$sts .= '</select>';
	echo $sts;
} else {
	echo 'Не достаточно прав!!!';
}
