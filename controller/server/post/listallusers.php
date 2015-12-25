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


$userid=GetDef('userid');
$addnone=GetDef('addnone');
$orgid=GetDef('orgid');

    $SQL = "SELECT * FROM users WHERE active=1 and orgid='$orgid'  ORDER BY login";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));
    $sts="<select name=suserid id=suserid>";
    if ($addnone=='true'){$sts=$sts."<option value='-1' >нет выбора</option>";};
    while($row = mysqli_fetch_array($result)) {
         $z=$row['id'];
         $sts=$sts."<option value=$z ";
         $zx=new Tusers;
         $zx->GetById($row['id']);
	  if ($userid==$row['id']){$sts=$sts."selected";};
          $l=$row['login'];
	 $sts=$sts.">$zx->fio($l)</option>";
	};
    $sts=$sts.'</select><script> for (var selector in config) {$(selector).chosen(config[selector]);};</script>';   
 echo $sts;    
?>