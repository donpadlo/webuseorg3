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
$name = PostDef('name');
$type = PostDef('type');
$url = PostDef('url');
$astra_id=GetDef('astra_id');

if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM astra_mon where astra_id='$astra_id'");
        //echo "SELECT COUNT(*) AS count FROM astra_mon where astra_id='$astra_id'";
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
        if ($count>0){
            $start = $limit*$page - $limit;
            $SQL = "SELECT * FROM astra_mon where astra_id='$astra_id' ORDER BY $sidx $sord LIMIT $start , $limit";
            //echo "$SQL";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                    $responce->rows[$i]['id']=$row['id'];
                    if ($row['type']==1){$row['type']="Мониторинг";};
                    if ($row['type']==2){$row['type']="Логи";};
                    $responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['type'],$row['url']);		
                    $i++;
            };
        } else {
          $responce->page = 1;  
        };
	echo json_encode($responce);
};
if (($oper=='add')){
        if ($type=="Мониторинг"){$type="1";};
        if ($type=="Логи"){$type="2";};
	$SQL = "INSERT INTO astra_mon (id,astra_id,name,type,url) VALUES (null,'$astra_id','$name','$type','$url')";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить страницу!".mysqli_error($sqlcn->idsqlconnection));

};
if (($oper=='edit')){
        if ($type=="Мониторинг"){$type="1";};
        if ($type=="Логи"){$type="2";};
	$SQL = "UPDATE astra_mon SET name='$name',type='$type',url='$url' WHERE id='$id'";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить страницу!".mysqli_error($sqlcn->idsqlconnection));

};

if ($oper=='del'){
	$SQL = "delete FROM astra_mon WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить страницу!".mysqli_error($sqlcn->idsqlconnection));
};

?>