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

$createdid = GetDef('createdid');
$id = PostDef('id');
if ($createdid!='') {$where="WHERE userid='$createdid'";} else {$where='';};
if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM bp_xml ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM bp_xml  $where ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";            
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать сформировать список задач!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];    
            $dt=MySQLDateTimeToDateTime($row['dt']);
            if ($row['status']=='0') {$status='Создано';};
            if ($row['status']=='1') {$status='В работе';};
            if ($row['status']=='2') {$status='Утверждено';};            
            if ($row['status']=='3') {$status='Отменено';};
            if ($row['status']=='4') {$status='Доработать';};
            $zx=new Tusers;
            $zx->GetById($row['userid']);
            $responce->rows[$i]['cell']=array($row['id'],$dt,$row['title'],$status,$zx->fio,$row['node'],$row['xml']);
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='del'){
        // помечаю БП как "отменен"
	$SQL = "UPDATE bp_xml SET status=3 WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать сформировать список задач!".mysqli_error($sqlcn->idsqlconnection));
};

?>