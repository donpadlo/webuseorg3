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

$folderkey=GetDef('folderkey'); 

// Роли:  
//            1="Полный доступ"
//            2="Просмотр финансовых отчетов"
//            3="Просмотр количественных отчетов"
//            4="Добавление"
//            5="Редактирование"
//            6="Удаление"

if ($user->TestRoles("1,6")==true){

$sql="delete from cloud_dirs where id='$folderkey'";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу удалить папку!!".mysqli_error($sqlcn->idsqlconnection));

} else {echo "У вас не хватает прав на удаление!";};