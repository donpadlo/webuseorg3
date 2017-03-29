<?php

/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx'); 
$sord = _GET('sord'); 
$oper= _POST('oper');
$id = _POST('id');
$title = _POST('title');
$view = _POST('view');
$mail = _POST('mail');
$sms = _POST('sms');
$title = mysqli_real_escape_string($sqlcn->idsqlconnection, $title);

if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM schedule");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM schedule ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['dtstart'],$row['dtend'],$row['title'],$row['sms'],$row['mail'],$row['view'],$row['comment']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit'){
    $sql="update schedule set title='$title',sms=$sms,mail=$mail,view=$view where id=$id";
    //echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу исправить!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='del'){
	$SQL = "delete FROM schedule WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить!".mysqli_error($sqlcn->idsqlconnection));
};

?>