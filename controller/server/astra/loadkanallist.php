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

$id=GetDef("astra_id");
$sql="select INET_NTOA(ip) as ip from astra_servers where id=$id";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));            
$ip="localhost";
while($row = mysqli_fetch_array($result)) {
 $ip=$row["ip"];
};
$url="http://$ip/getlistchanels.php";
$txt = file($url);

foreach ($txt as $stroka){
    $sta=explode(";", $stroka);
    $astra_id=$sta[0];
    $group_id=$sta[1];
    $id=$sta[2];
    $name=$sta[3];
    $cnt=0;
    $sql="select * from astra_chanels where astra_id=$astra_id and chanel_id=$id and group_id=$group_id ";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));            
    while($row = mysqli_fetch_array($result)) {
      $cnt++;  
    };
    if ($cnt==0){
      $sql="insert into astra_chanels (astra_id,chanel_id,group_id,name) values ($astra_id,$id,$group_id,'$name')";  
      $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));            
    };

};