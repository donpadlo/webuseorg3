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
$name= PostDef('name');
$comment= PostDef('comment');

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM group_nome");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT id,name,comment,active FROM group_nome ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
	    if ($row['active']=="1")
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>",$row['id'],$row['name'],$row['comment']);} else
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>",$row['id'],$row['name'],$row['comment']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE group_nome SET name='$name',comment='$comment' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группе!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO group_nome (id,name,comment,active) VALUES (null,'$name','$comment',1)";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить группу!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE group_nome SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группе!".mysqli_error($sqlcn->idsqlconnection));
	$SQL = "SELECT * FROM group_nome WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
	while($row = mysqli_fetch_array($result)) {$active=$row['active'];};
	$SQL = "UPDATE group_param SET active='$active' WHERE groupid='$id'";		
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группе!".mysqli_error($sqlcn->idsqlconnection));

	
};

?>