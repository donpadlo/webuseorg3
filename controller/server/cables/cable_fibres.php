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
$number= PostDef('number');
$color1= PostDef('color1');
$color2= PostDef('color2');
$module_id= GetDef('module_id');
$id= PostDef('id');

$page = GetDef('page');
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 


if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM lib_cable_lines where id_calble_module='$module_id'";
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
            $SQL = "SELECT * FROM lib_cable_lines where id_calble_module='$module_id' ORDER BY $sidx $sord LIMIT $start , $limit";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список волокон!".mysqli_error($sqlcn->idsqlconnection));            
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                    $responce->rows[$i]['id']=$row['id'];
                    switch ($row['color1']) {
                        case 1: $row['color1']="<span style='background: #ff0000'><font color='#ff0000'>Красный</font></span>";break;
                        case 2: $row['color1']="<span style='background: #f8c411'><font color='#f8c411'>Оранжевый</font></span>";break;
                        case 3: $row['color1']="<span style='background: #ffff00'><font color='#ffff00'>Желтый</font></span>";break;
                        case 4: $row['color1']="<span style='background: #00ff00'><font color='#00ff00'>Зеленый</font></span>";break;
                        case 5: $row['color1']="<span style='background: #0000ff'><font color='#0000ff'>Синий</font></span>";break;
                        case 6: $row['color1']="<span style='background: #800080'><font color='#800080'>Фиолетовый</font></span>";break;
                        case 7: $row['color1']="<span style='background: #8c1f1f'><font color='#8c1f1f'>Коричневый</font></span>";break;
                        case 8: $row['color1']="<span style='background: #000000'><font color='#000000'>Черный</font></span>";break;
                        case 9: $row['color1']="<span style='background: #ffffff'><font color='#ffffff'>Белый</font></span>";break;
                        case 10: $row['color1']="<span style='background: #c0c0c0'><font color='#c0c0c0'>Серый</font></span>";break;
                        case 11: $row['color1']="<span style='background: #1199f8'><font color='#1199f8'>Бирюзовый</font></span>";break;
                        case 12: $row['color1']="<span style='background: #FF91A4'><font color='#FF91A4'>Розовый</font></span>";break;
                        case 13: $row['color1']="<span style='background: #00FFFF'><font color='#00FFFF'>Салатовый</font></span>";break;
                        case 14: $row['color1']="<span style='background: #808000'><font color='#808000'>Оливковый</font></span>";break;
                        case 15: $row['color1']="<span style='background: #b5b771'><font color='#b5b771'>Бежевый</font></span>";break;
                        case 16: $row['color1']="<span style='background: #d2d4a5'><font color='#d2d4a5'>Натуральный</font></span>";break;
                    };
                    switch ($row['color2']) {
                        case 1: $row['color2']="<span style='background: #ff0000'><font color='#ff0000'>Красный</font></span>";break;
                        case 2: $row['color2']="<span style='background: #f8c411'><font color='#f8c411'>Оранжевый</font></span>";break;
                        case 3: $row['color2']="<span style='background: #ffff00'><font color='#ffff00'>Желтый</font></span>";break;
                        case 4: $row['color2']="<span style='background: #00ff00'><font color='#00ff00'>Зеленый</font></span>";break;
                        case 5: $row['color2']="<span style='background: #0000ff'><font color='#0000ff'>Синий</font></span>";break;
                        case 6: $row['color2']="<span style='background: #800080'><font color='#800080'>Фиолетовый</font></span>";break;
                        case 7: $row['color2']="<span style='background: #8c1f1f'><font color='#8c1f1f'>Коричневый</font></span>";break;
                        case 8: $row['color2']="<span style='background: #000000'><font color='#000000'>Черный</font></span>";break;
                        case 9: $row['color2']="<span style='background: #ffffff'><font color='#ffffff'>Белый</font></span>";break;
                        case 10: $row['color2']="<span style='background: #c0c0c0'><font color='#c0c0c0'>Серый</font></span>";break;
                        case 11: $row['color2']="<span style='background: #1199f8'><font color='#1199f8'>Бирюзовый</font></span>";break;
                        case 12: $row['color2']="<span style='background: #FF91A4'><font color='#FF91A4'>Розовый</font></span>";break;
                        case 13: $row['color2']="<span style='background: #00FFFF'><font color='#00FFFF'>Салатовый</font></span>";break;
                        case 14: $row['color2']="<span style='background: #808000'><font color='#808000'>Оливковый</font></span>";break;
                        case 15: $row['color2']="<span style='background: #b5b771'><font color='#b5b771'>Бежевый</font></span>";break;
                        case 16: $row['color2']="<span style='background: #d2d4a5'><font color='#d2d4a5'>Натуральный</font></span>";break;
                    }
                    
                    $responce->rows[$i]['cell']=array($row['id'],$row['number'],$row['color1'],$row['color2']);		
                    $i++;
            };
        };
	echo json_encode($responce);
};
if ($oper=="add"){
  $sql="insert into lib_cable_lines (id_calble_module,number,color1,color2) VALUES ('$module_id','$number','$color1','$color2')";  
  //echo "$sql\n";
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу добавить волокно!".mysqli_error($sqlcn->idsqlconnection));            
};
if ($oper=="edit"){
  $sql="update lib_cable_lines set number='$number',color1='$color1',color2='$color2' where id='$id'";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу обновить волокно!".mysqli_error($sqlcn->idsqlconnection));            
};
if ($oper=="del"){
  $sql="delete from lib_cable_lines where id='$id'";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу удалить волокно!".mysqli_error($sqlcn->idsqlconnection));            
};
