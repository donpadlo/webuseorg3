<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$orgid = $user->orgid;
$selorgid = GetDef('selorgid');
if ($selorgid != "") {
    $orgid = $selorgid;
}
;
$placesid = GetDef('placesid');
$addnone = GetDef('addnone');
$oldopgroup = '';
if ($user->TestRoles('1,3,4,5,6')) {
    $SQL = "SELECT * FROM places WHERE orgid='$orgid' AND active=1 ORDER BY binary(opgroup), binary(name)";
    $result = $sqlcn->ExecuteSQL($SQL) or die('Не могу выбрать список помещений!' . mysqli_error($sqlcn->idsqlconnection));
    $sts = "<select name='splaces' id='splaces'>\n";
    if ($addnone == 'true') {
        if ($placesid == "") {
            $pl = "selected";
        } else {
            $pl = "";
        }
        ;
        $sts .= "<option value='-1' $pl>нет выбора</option>\n";
    }
    ;
    
    $flag = 0;
    while ($row = mysqli_fetch_array($result)) {
        $vl = $row['id'];
        $opgroup = $row['opgroup'];
        if ($opgroup != $oldopgroup) {
            if ($flag != 0) {
                $sts .= "</optgroup>\n";
            }
            $sts .= "<optgroup label='$opgroup'>\n";
            $flag = 1;
        }
        $sts .= " <option value='$vl'";
        if ($placesid == $row['id']) {
            $sts .= 'selected';
        }
        $nm = $row['name'];
        $sts .= ">$nm</option>\n";
        $oldopgroup = $opgroup;
    }
    $sts .= "</optgroup>\n";
    $sts .= "</select>\n";
    echo $sts;
} else {
    echo 'Не достаточно прав!!!';
}
