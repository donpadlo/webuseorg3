<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

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


$page = GetDef('page');
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 
$oper= PostDef('oper');
$id = PostDef('id');
$mobile=PostDef('mobile');
$smstxt=PostDef('smstxt');
$status=PostDef('status');

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM smslist");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM smslist ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['mobile'],$row['smstxt'],$row['status']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE smslist SET mobile='$mobile',smstxt='$smstxt',status='$status' WHERE id='$id'";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO smslist (mobile,smstxt,status) VALUES ('$mobile','$smstxt','$status')";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить агента!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "delete FROM smslist WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить!".mysqli_error($sqlcn->idsqlconnection));
};

?>