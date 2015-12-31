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
$title = PostDef('title');
$cloud_dirs_id = GetDef('cloud_dirs_id');

if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM cloud_files where cloud_dirs_id='$cloud_dirs_id'";
	$result = $sqlcn->ExecuteSQL($sql)  or die("Не могу выбрать количество записей!".mysqli_error($lb->idsqlconnection));
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM cloud_files where cloud_dirs_id='$cloud_dirs_id' ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список файлов!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
        $i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
                $ico="<img src='controller/client/themes/$cfg->theme/ico/page_white_acrobat.png'>";
                if (strpos($row['filename'],'jpeg')!=false){$ico="<img src='controller/client/themes/$cfg->theme/ico/image.png'>";};
                if (strpos($row['filename'],'jpg')!=false){$ico="<img src='controller/client/themes/$cfg->theme/ico/image.png'>";};
                if (strpos($row['filename'],'png')!=false){$ico="<img src='controller/client/themes/$cfg->theme/ico/image.png'>";};
                if (strpos($row['filename'],'xls')!=false){$ico="<img src='controller/client/themes/$cfg->theme/ico/exel.png'>";};
                if (strpos($row['filename'],'doc')!=false){$ico="<img src='controller/client/themes/$cfg->theme/ico/office.png'>";};
                $ico="<a target='_blunk' href='files/".$row['filename']."'>".$ico."</a>";
                $title="<a target='_blunk' href='files/".$row['filename']."'>".$row['title']."</a>";
	    	$responce->rows[$i]['cell']=array($row['id'],$ico,$title,$row['filename'],$row['dt'],$row['sz']);		
                $i++;
	};
	echo json_encode($responce);
};
if ($oper=='edit'){
  if ($user->TestRoles("1,5")==true){
  $sql="update cloud_files set title='$title' where id='$id'"  ;
  $result = $sqlcn->ExecuteSQL($sql)  or die("Не могу выполнить запрос!".mysqli_error($lb->idsqlconnection));
  } else {echo "Для редактирования не хватает прав!";};
};
if ($oper=='del'){
  if ($user->TestRoles("1,6")==true){
  $sql="delete from cloud_files  where id='$id'"  ;
  $result = $sqlcn->ExecuteSQL($sql)  or die("Не могу выполнить запрос!".mysqli_error($lb->idsqlconnection));
  } else {echo "Для удаления не хватает прав!";};
};