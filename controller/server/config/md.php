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
$id = PostDef('id');
$name= PostDef('name');
$active= PostDef('active');

if ($oper=='del')
{
	$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE id='$id'") or die("Не могу выбрать список модулей!".mysqli_error($sqlcn->idsqlconnection));
	while($row = mysqli_fetch_array($result)) {
            $modname=$row['nameparam'];
            $str1 = explode("_", $modname);
            $mc='modulecomment_'.$str1[1];
            $result2 = $sqlcn->ExecuteSQL("DELETE FROM config_common WHERE nameparam='$mc'")  or die("Не могу выбрать список комментарие!".mysqli_error($sqlcn->idsqlconnection));
            $mcopy='modulecopy_'.$str1[1];
            $result2 = $sqlcn->ExecuteSQL("DELETE FROM config_common WHERE nameparam='$mcopy'")  or die("Не могу выбрать список авторов!".mysqli_error($sqlcn->idsqlconnection));
	}
	$result = $sqlcn->ExecuteSQL("DELETE FROM config_common WHERE id='$id'") or die("Не могу выбрать список модулей!".mysqli_error($sqlcn->idsqlconnection));
};    

if ($oper=='edit')
{
	$SQL = "UPDATE config_common SET valueparam='$active' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по модулю!".mysqli_error($sqlcn->idsqlconnection));
};

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM config_common WHERE nameparam LIKE 'modulename_%'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM config_common WHERE nameparam LIKE 'modulename_%' ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список модулей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
            $modname=$row['nameparam'];
            $str1 = explode("_", $modname);
            $mc='modulecomment_'.$str1[1];
            $result2 = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='$mc'")  or die("Не могу выбрать список комментарие!".mysqli_error($sqlcn->idsqlconnection));
            while($row2 = mysqli_fetch_array($result2)) {$mc=$row2['valueparam'];};
            $mcopy='modulecopy_'.$str1[1];
            $result2 = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='$mcopy'")  or die("Не могу выбрать список авторов!".mysqli_error($sqlcn->idsqlconnection));
            while($row2 = mysqli_fetch_array($result2)) {$mcopy=$row2['valueparam'];};            
	    $responce->rows[$i]['id']=$row['id'];	    
		$responce->rows[$i]['cell']=array($row['id'],$str1[1],$mc,$mcopy,$row['valueparam']);
	    $i++;
	}
	echo json_encode($responce);
};

?>
