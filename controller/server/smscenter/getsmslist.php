<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции


$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx'); 
$sord = _GET('sord'); 
$oper= _POST('oper');
$id = _POST('id');
$mobile=_POST('mobile');
$smstxt=_POST('smstxt');
$status=_POST('status');

if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM sms_by_list");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM sms_by_list ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['mobile'],$row['smstxt'],$row['status'],$row['dt']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit'){
	$SQL = "UPDATE sms_by_list SET mobile='$mobile',smstxt='$smstxt',status='$status' WHERE id='$id'";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add'){
	$SQL = "INSERT INTO sms_by_list (mobile,smstxt,status) VALUES ('$mobile','$smstxt','$status')";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить агента!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del'){
	$SQL = "delete FROM sms_by_list WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить!".mysqli_error($sqlcn->idsqlconnection));
};

?>