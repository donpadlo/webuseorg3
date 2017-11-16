<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.
                                               
// get the requested page
$page = GetDef('page');

// get how many rows we want to have into the grid
if (isset($_GET['rows'])) {
    $limit = $_GET['rows'];
} else {
    $rows = '';
}

$sidx = GetDef('sidx', '1'); // get index row - i.e. user click to sort
$sord = GetDef('sord'); // get the direction
$oper = PostDef('oper');
$id = PostDef('id');
$eqid = GetDef('eqid');
$comment = PostDef('comment');
$dt = PostDef('dt', '10.10.2014 00:00:00');
$dtend = PostDef('dtend', '10.10.2014 00:00:00');
$status = PostDef('status', '1');
$doc = PostDef('doc');

// если не задано ТМЦ по которому показываем перемещения, то тогда просто листаем последние
if ($eqid == '') {
    $where = '';
} else {
    $where = "WHERE repair.eqid='$eqid'";
}

if ($oper == '') {
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count, repair.dt, repair.dtend, repair.kntid,
			knt.name, repair.cost, repair.comment, repair.status
			FROM repair INNER JOIN knt ON knt.id=repair.kntid $where");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    $total_pages = ($count > 0) ? ceil($count / $limit) : 0;
    if ($page > $total_pages) {
        $page = $total_pages;
    }
    $start = $limit * $page - $limit;
    $SQL = "SELECT repair.id, repair.userfrom, repair.userto, repair.doc, repair.dt, repair.dtend,
			repair.kntid, knt.name, repair.cost, repair.comment, repair.status
			FROM repair INNER JOIN knt ON knt.id=repair.kntid $where
            ORDER BY $sidx $sord LIMIT $start, $limit";
    $result = $sqlcn->ExecuteSQL($SQL) or die('Не могу выбрать список ремонтов!' . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['id'];
        $dt = MySQLDateToDate($row['dt']);
        $dtend = MySQLDateToDate($row['dtend']);
        if ($row['status'] == '1') {
            $st = 'В сервисе';
        }
        if ($row['status'] == '0') {
            $st = "Работает";
        }
        if ($row["status"] == '2') {
            $st = 'Есть заявка';
        }
        if ($row['status'] == '3') {
            $st = 'Списать';
        }
        $zz = new Tusers();
        if ($row['userto'] != '-1') {
            $zz->GetById($row['userto']);
            $row['userto'] = $zz->fio;
        } else {
            $row['userto'] = 'не задано';
        }
        if ($row['userfrom'] != '-1') {
            $zz->GetById($row['userfrom']);
            $row['userfrom'] = $zz->fio;
        } else {
            $row['userfrom'] = 'не задано';
        }
        
        $responce->rows[$i]['cell'] = array(
            $row['id'],
            $dt,
            $dtend,
            $row['name'],
            $row['cost'],
            $row['comment'],
            $st,
            $row['userfrom'],
            $row['userto'],
            $row['doc']
        );
        $i ++;
    }
    jsonExit($responce);
}

if ($oper == 'edit') {
    $dt = DateToMySQLDateTime2($dt . ' 00:00:00');
    $dtend = DateToMySQLDateTime2($dtend . ' 00:00:00');
    $SQL = "UPDATE repair SET comment='$comment', dt='$dt', dtend='$dtend',
		status='$status', doc='$doc' WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die('Не могу обновить статус ремонта ТМЦ! ' . mysqli_error($sqlcn->idsqlconnection));
    ReUpdateRepairEq();
}

if ($oper == 'del') {
    $SQL = "DELETE FROM repair WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die('Не могу удалить запись о ремонте! ' . mysqli_error($sqlcn->idsqlconnection));
    ReUpdateRepairEq();
}
