<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$sql = "SELECT nome.id,nome.name,group_nome.name as grname FROM `nome` inner join group_nome on group_nome.id=nome.groupid where nome.active=1 and group_nome.active=1 ORDER BY binary(grname),binary(nome.name)";
$result = $sqlcn->ExecuteSQL($sql) or die('Не могу выбрать список ТМЦ! ' . mysqli_error($sqlcn->idsqlconnection));
$sts = '<select class="chosen-select" multiple name="stmc" id="stmc">';

$flag = 0;
$oldopgroup = '';
while ($row = mysqli_fetch_array($result)) {
    $opgroup = $row['grname'];
    if ($opgroup != $oldopgroup) {
        if ($flag != 0) {
            $sts .= '</optgroup>';
        }
        $sts .= '<optgroup label="' . $opgroup . '">';
        $flag = 1;
    }
    $sts .= '<option value="' . $row['id'] . '"';
    $sts .= '>' . $row['name'] . '</option>';
    $oldopgroup = $opgroup;
}
$sts .= '</optgroup>';
$sts .= '</select>';
echo $sts;
