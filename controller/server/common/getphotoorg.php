<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
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

$eqid=$_GET['eqid'];

 $SQL = "SELECT * FROM org WHERE id='$eqid'";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список фото!".mysqli_error($sqlcn->idsqlconnection));
    $photo="";
    while($row = mysqli_fetch_array($result)) {
        $photo=$row['picmap'];
	};
  if ($photo!="") {echo "<img src=photos/maps/0-0-0-$photo width=100%>";} else 
  {echo "<img src=images/noimage.jpg width=100%>";};
?>