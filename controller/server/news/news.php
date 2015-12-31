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

$responce=new stdClass();
$page = $_GET['page']; // get the requested page
if ($page==0){$page=1;};
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if (isset($_POST["oper"])) {$oper= $_POST['oper'];} else {$oper="";};
if (isset($_POST["id"])) {$id= $_POST['id'];} else {$id="";};
if (isset($_POST["title"]))  {$title= ClearMySqlString($sqlcn->idsqlconnection,$_POST['title']);} else {$title="";};
if (isset($_POST["stiker"])) {$stiker= $_POST['stiker'];} else {$stiker="";};

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM news");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM news ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список новостей!".mysqli_error($sqlcn->idsqlconnection));

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];	    
	    $responce->rows[$i]['cell']=array($row['id'],$row['dt'],$row['title'],$row['stiker']);		
	    $i++;
	}
	echo json_encode($responce);
};

if ($oper=='edit')
{
	$SQL = "UPDATE news SET title='$title',stiker='$stiker' WHERE id='$id'";
	$result =$sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить заголовок новости!".mysqli_error($sqlcn->idsqlconnection));
};

if ($oper=='del')
{
	$SQL = "DELETE FROM news WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить новость!".mysqli_error($sqlcn->idsqlconnection));
};
