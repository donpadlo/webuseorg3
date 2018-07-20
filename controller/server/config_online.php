<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("inc/lbfunc.php"); // загружаем функции

$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx');
$sord = _GET('sord');
$oper = _POST('oper');
$id = _POST('id');
$kname = _POST('kname');
$inn = _POST('inn');

if ($oper == '') {
    if (! $sidx) $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM online_kkm");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    
    if ($count > 0) {
	$total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    
    if ($page > $total_pages) {$page = $total_pages;};
    
    $start = $limit * $page - $limit;
    $SQL = "SELECT * FROM online_kkm ORDER BY $sidx $sord LIMIT $start , $limit";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список online_kkm!" . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['id'];
        $responce->rows[$i]['cell'] = array(
            $row['id'],
            $row['kname'],
            $row['inn'],
        );
        $i ++;
    }
    echo json_encode($responce);
}

if ($oper == 'edit') {
    $SQL = "UPDATE online_kkm SET kname='$kname',inn='$inn' WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные online_kkm!" . mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'add') {
    $SQL = "INSERT INTO online_kkm (id,kname,inn) VALUES (null,'$kname','$inn')";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить online_kkm!" . mysqli_error($sqlcn->idsqlconnection));
}

if ($oper == 'del') {
    $SQL = "delete FROM online_kkm WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу удалить online_kkm!" . mysqli_error($sqlcn->idsqlconnection));
}


?>