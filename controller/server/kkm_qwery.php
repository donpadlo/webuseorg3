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
$kkm=  _GET("kkm");

if ($oper == '') {
    if (! $sidx) $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM online_payments where kassaid=$kkm ");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    
    if ($count > 0) {
	$total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    
    if ($page > $total_pages) {$page = $total_pages;};
    
    $start = $limit * $page - $limit;
    $SQL = "SELECT * FROM online_payments where kassaid=$kkm ORDER BY $sidx $sord LIMIT $start , $limit";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список online_kkm!" . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
	if ($row['status']=="1"){$row['status']="<span class=\"glyphicon glyphicon-ok\"></span>";} else {
	    $row['status']="<span class=\"glyphicon glyphicon-asterisk\"></span>";
	};
        $responce->rows[$i]['id'] = $row['id'];
        $responce->rows[$i]['cell'] = array(
            $row['id'],
            $row['numcheck'],
            $row['docdate'],
	    $row['summdoc'],
            $row['goodsjson'],
            $row['status'],
            $row['dognum'],	    
	    $row['fiscalSign'],		
	    $row['documentNumber']		
        );
        $i ++;
    }
    echo json_encode($responce);
}
if ($oper == 'del') {
    $SQL = "delete FROM online_payments where id=$id";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу удалить чек из online_kkm!" . mysqli_error($sqlcn->idsqlconnection));    
};
?>