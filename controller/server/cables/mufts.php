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

$oper= PostDef('oper');
$name= PostDef('name');
$comment= PostDef('comment');
$id= PostDef('id');

$page = GetDef('page');
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 


if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM lib_cable_muft";
        //echo "!$sql!";
	$result = $sqlcn->ExecuteSQL($sql)  or die("Не могу выбрать количество записей!".mysqli_error($sqlcn->idsqlconnection));
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
        //echo "$count!!";
        $responce=new stdClass();
	if( $count >0 ) {
            $total_pages = ceil($count/$limit);
            if ($page > $total_pages) $page=$total_pages;
            $start = $limit*$page - $limit;
            $SQL = "SELECT * FROM lib_cable_muft ORDER BY $sidx $sord LIMIT $start , $limit";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список кабелей!".mysqli_error($sqlcn->idsqlconnection));            
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                    $responce->rows[$i]['id']=$row['id'];
                    $responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['comment']);		
                    $i++;
            };
        };
	echo json_encode($responce);
};
if ($oper=="add"){
  $sql="insert into lib_cable_muft (name,comment) VALUES ('$name','$comment')";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу добавить кабель!".mysqli_error($sqlcn->idsqlconnection));            
};
if ($oper=="edit"){
  $sql="update lib_cable_muft set name='$name',comment='$comment' where id='$id'";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу обновить кабель!".mysqli_error($sqlcn->idsqlconnection));            
};
if ($oper=="del"){
  $sql="delete from lib_cable_muft where id='$id'";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу удалить кабель!".mysqli_error($sqlcn->idsqlconnection));            
};
