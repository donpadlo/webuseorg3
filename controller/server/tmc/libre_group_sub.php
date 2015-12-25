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
$groupid = GetDef('groupid');
if ($groupid=="") {$groupid = PostDef('groupid');};
$id = PostDef('id');
$name= PostDef('name');
// если выбрана группа, то обрабатываем, иначе ничего
//echo "!$groupid!";
if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM group_param");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;
        //echo "!$limit*$page - $limit!";
	$start = $limit*$page - $limit;
	$SQL = "SELECT id,name,active FROM group_param WHERE groupid='$groupid' ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список параметров групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
	    if ($row['active']=="1")
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>",$row['id'],$row['name']);} else
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>",$row['id'],$row['name']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE group_param SET name='$name' WHERE id='$id'";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группе!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	if (($groupid=="") or ($name=="")) {die();};
	$SQL = "INSERT INTO group_param (id,groupid,name,active) VALUES (null,'$groupid','$name',1)";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить параметр группы!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE group_param SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по параметрам группы!".mysqli_error($sqlcn->idsqlconnection));
};
?>