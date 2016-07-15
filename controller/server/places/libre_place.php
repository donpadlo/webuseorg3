<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
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


if (isset($_GET["page"]))       {$page = $_GET['page'];}    else {$page="";};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];}   else {$limit="";};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx']; }   else {$sidx="";};
if (isset($_GET["sord"]))       {$sord = $_GET['sord']; }   else {$sord="";};
if (isset($_GET["orgid"]))       {$orgid = $_GET['orgid']; }   else {$orgid="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};

if (isset($_POST["id"]))        {$id = $_POST['id'];}       else {$id="";};
if (isset($_POST["name"]))      {$name= $_POST['name'];}    else {$name="";};
if (isset($_POST["comment"]))      {$comment= $_POST['comment'];}    else {$comment="";};
if (isset($_POST["opgroup"]))      {$opgroup= $_POST['opgroup'];}    else {$opgroup="";};

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM places WHERE orgid='$orgid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT id,opgroup,name,comment,active FROM places WHERE orgid='$orgid' ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список помещений!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
	    if ($row['active']=="1")
		{$responce->rows[$i]['cell']=array("<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i>",$row['id'],$row['opgroup'],$row['name'],$row['comment']);} else
		{$responce->rows[$i]['cell']=array("<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>",$row['id'],$row['opgroup'],$row['name'],$row['comment']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE places SET opgroup='$opgroup',name='$name',comment='$comment' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по помещениям!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO places (id,orgid,opgroup,name,comment,active) VALUES (null,'$orgid','$opgroup','$name','$comment',1)";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить помещение!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE places SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по помещению!".mysqli_error($sqlcn->idsqlconnection));
};

?>