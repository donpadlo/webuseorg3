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
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 
$oper= PostDef('oper');
$curuserid = GetDef('curuserid');
$id = PostDef('id');
$orgid=GetDef('orgid');

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM post_users WHERE orgid='$orgid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM post_users WHERE orgid='$orgid' ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";            
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список должностей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];    
            $zx=new Tusers;
            $zx->GetById($row['userid']);            
	    if ($row['active']=="1") {$active="<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>";} else
				   {$active="<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>";};            
            $responce->rows[$i]['cell']=array($active,$row['id'],$row['post'],$zx->fio);
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='add')
{
 $userlogin = $_POST['userlogin'];
 $post = $_POST['post'];
 $SQL = "INSERT INTO post_users (active,orgid,id,userid,post) VALUES (1,'$orgid',null,'$userlogin','$post')";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить должность!".mysqli_error($sqlcn->idsqlconnection));
};

if ($oper=='edit')
{
 $userlogin = $_POST['userlogin'];
 $post = $_POST['post'];
 $SQL = "UPDATE post_users SET userid='$userlogin',post='$post' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить должность!".mysqli_error($sqlcn->idsqlconnection));
};

if ($oper=='del')
{
 $SQL = "UPDATE post_users set active=0 WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить должность!".mysqli_error($sqlcn->idsqlconnection));
};

?>