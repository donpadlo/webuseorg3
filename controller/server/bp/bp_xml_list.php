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



if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count,
            bp_xml.userid as crid,bp_xml.id AS id,bp_xml_userlist.id AS uid, bp_xml_userlist.dtstart AS dtstart, bp_xml.title AS title
FROM bp_xml_userlist
INNER JOIN bp_xml ON bp_xml_userlist.bpid = bp_xml.id WHERE 
    bp_xml_userlist.result=0 and bp_xml.status=1 and bp_xml_userlist.userid='$curuserid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT FORMAT((bp_xml_userlist.timer*24-(NOW( ) - bp_xml_userlist.dtstart)/60/60),0) AS ctt,
            bp_xml.userid as crid,bp_xml.id AS bpid,bp_xml_userlist.id AS uid, bp_xml_userlist.dtstart AS dtstart, bp_xml.title AS title
FROM bp_xml_userlist
INNER JOIN bp_xml ON bp_xml_userlist.bpid = bp_xml.id WHERE 
    bp_xml_userlist.result=0 and bp_xml.status=1 and bp_xml_userlist.userid='$curuserid' 
    ORDER BY bp_xml.id DESC, $sidx $sord LIMIT $start , $limit ";
        //echo "!$SQL!";            
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать сформировать список bp_userlist!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['uid'];    
            $un=new Tusers;
            $un->GetById($row['crid']);
            $row['ctt']=round($row['ctt'],0);
            $ttm=$row['ctt'];
            if ($row['ctt']>10){$tt="<span class='badge badge-success'>$ttm</span>";};
            if ($row['ctt']<=10){$tt="<span class='badge badge-warning'>$ttm</span>";};
            if ($row['ctt']<=3){$tt="<span class='badge badge-important'>$ttm</span>";};
            $responce->rows[$i]['cell']=array($row['uid'],$row['bpid'],$row['dtstart'],$row['title'],$un->fio,$tt);
	    $i++;
	}
	echo json_encode($responce);
};

?>