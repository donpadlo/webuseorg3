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

$astra_id=GetDef('astra_id');
echo '<span class="label label-success">Мониторинг НОС</span>';
echo "<div class='alert alert-success'><ul>";
echo "<li><a onclick='openMonurl($astra_id)'>Посмотреть</a></li>";
echo "</ul></div>";
echo '<span class="label label-success">Внешний мониторинг</span>';
$SQL = "SELECT * FROM astra_mon WHERE astra_id = '$astra_id' and type=1";
$result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));
echo "<div class='alert alert-success'><ul>";
while($row = mysqli_fetch_array($result)) {
    $id=$row["id"];
    $name=$row["name"];
    $url=$row["url"];
    echo "<li><a title='Если пишет ошибку сертификата SSL - перейдите в браузере по ссылке $url и подтвердите исключение безопасности' onclick='openurl(\"$url\")'>$name</a></li>";
};
echo "</ul></div>";
echo '<span class="label label-info">Просмотр логов</span>';
$SQL = "SELECT * FROM astra_mon WHERE astra_id = '$astra_id' and type=2";
$result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));
echo "<div class='alert alert-info'><ul>";
while($row = mysqli_fetch_array($result)) {
    $id=$row["id"];
    $name=$row["name"];
    $url=$row["url"];
    echo "<li><a onclick='openGeturl(\"$url\")'>$name</a></li>";
};

?>
