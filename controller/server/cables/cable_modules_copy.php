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

$module_id= GetDef('module_id');

$sql="select * from lib_cable_modules where id='$module_id'";
echo "$sql\n";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список модулей!".mysqli_error($sqlcn->idsqlconnection));            
while($row = mysqli_fetch_array($result)) {
 $cable_id=$row["cable_id"];
 $number=$row["number"];
 $color=$row["color"];
};
$number=$number."(copy)";
$sql="insert into lib_cable_modules (cable_id,number,color) values ('$cable_id','$number','$color')";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу вставить копию модуля!".mysqli_error($sqlcn->idsqlconnection));            
$new_module_id=mysqli_insert_id($sqlcn->idsqlconnection);

$sql="SELECT * FROM lib_cable_lines where id_calble_module='$module_id'";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список волокон!".mysqli_error($sqlcn->idsqlconnection));            
while($row = mysqli_fetch_array($result)) {
   $number=$row["number"]; 
   $color1=$row["color1"]; 
   $color2=$row["color2"]; 
    $sql="insert into lib_cable_lines (id_calble_module,number,color1,color2) values ('$new_module_id','$number','$color1','$color2')";
    $result2 = $sqlcn->ExecuteSQL( $sql ) or die("Не могу вставить копию волокна!".mysqli_error($sqlcn->idsqlconnection));             
};
