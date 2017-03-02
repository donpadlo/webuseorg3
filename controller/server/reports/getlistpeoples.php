<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$orgid = GetDef('orgid', '-1');
$addnone = GetDef('addnone');
$onchange = GetDef('onchange');
$oldopgroup = '';

if ($orgid=="-1"){
  $SQL = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.active=1 ORDER BY users.login";
} else {
  $SQL = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.orgid='$orgid' AND users.active=1 ORDER BY users.login";  
};
//echo "$SQL\n";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));

$sts = '<select class="chosen-select" multiple name="speoples" id="speoples">';
if ($addnone == 'true') {
	$sts .= '<option value="-1" >нет выбора</option>';
};
while ($row = mysqli_fetch_array($result)) {
	$sts .= '<option value="'.$row['id'].'"';
	$sts .= '>'.$row['fio'].'</option>';	
}
$sts .= '</select>';
if ($onchange!=""){
  $sts=$sts.'<script>$("#speoples").change(function() {'.$onchange.';});</script>';  
};
echo $sts;
