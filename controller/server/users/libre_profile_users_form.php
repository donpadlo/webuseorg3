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
include_once("../../../inc/login.php");		// загружаем функции

$step=_GET("step");
$userid=_GET("userid");
$fio=_POST("fio");
$post=_POST("post");
$photo=_POST("picname");
$code=_POST("code");
$phone1=_POST("phone1");
$phone2=_POST("phone2");
//echo "!$userid!";
$tmpuser=new Tusers();
$tmpuser->GetById($userid);
$tmpuser->fio=$fio;
$tmpuser->jpegphoto=$photo;
//echo "$fio!$userid";
$tmpuser->post=$post;
$tmpuser->tab_num=$code;
$tmpuser->telephonenumber=$phone1;
$tmpuser->homephone=$phone2;
$tmpuser->Update();
unset($tmpuser);

echo "ok";

?>
