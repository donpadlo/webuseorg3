<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
$oper = PostDef('oper');
$ip = PostDef('ip');
$roles = PostDef('roles');
$comment = PostDef('comment');
$foot = PostDef('foot');
$id = PostDef('id');

$page = GetDef('page');
if ($page == 0) {
    $page = 1;
}
;
$limit = GetDef('rows');
$sidx = GetDef('sidx');
$sord = GetDef('sord');

if ($oper == '') {
    if (! $sidx)
        $sidx = 1;
    $sql = "SELECT COUNT(*) AS count FROM arduino_rele_config";
    // echo "!$sql!";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать количество записей!" . mysqli_error($sqlcn->idsqlconnection));
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    // echo "$count!!";
    $responce = new stdClass();
    if ($count > 0) {
        $total_pages = ceil($count / $limit);
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        $SQL = "SELECT * FROM arduino_rele_config ORDER BY $sidx $sord LIMIT $start , $limit";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список реле!" . mysqli_error($sqlcn->idsqlconnection));
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['ip'],
                $row['roles'],
                $row['comment'],
                $row['foot']
            );
            $i ++;
        }
        ;
    }
    ;
    echo json_encode($responce);
}
;
if ($oper == "add") {
    $sql = "insert into arduino_rele_config (ip,roles,comment,foot) VALUES ('$ip','$roles','$comment','$foot')";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу добавить реле!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == "edit") {
    $sql = "update arduino_rele_config set roles='$roles',ip='$ip',comment='$comment',foot='$foot' where id='$id'";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу обновить реле!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == "del") {
    $sql = "delete from arduino_rele_config where id='$id'";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу удалить реле!" . mysqli_error($sqlcn->idsqlconnection));
}
;
