<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("../../../config.php"); // загружаем первоначальные настройки
                                      
// загружаем классы

include_once ("../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../class/config.php"); // загружаем классы настроек
include_once ("../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../class/employees.php"); // загружаем классы работы с профилем пользователя
                                              
// загружаем все что нужно для работы движка

include_once ("../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../inc/functions.php"); // загружаем функции
$responce = new stdClass();
$num = 0;
if (isset($_GET["num"])) {
    $num = $_GET['num'];
}
;
$SQL = "SELECT * FROM news ORDER by dt DESC limit $num,4";
// echo "$SQL";
$result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список новостей!" . mysqli_error($sqlcn->idsqlconnection));
$cnt = 0;
$rz = 0;
while ($row = mysqli_fetch_array($result)) {
    $dt = MySQLDateTimeToDateTimeNoTime($row["dt"]);
    $title = $row["title"];
    echo "<span class='label label-info'>$dt</span><h5>$title</h5>";
    $pieces = explode("<!-- pagebreak -->", $row["body"]);
    echo "<p>$pieces[0]</p>";
    if (isset($pieces[1]) == true) {
        echo "<div align=right><a class='btn btn-primary btn-small' href=?content_page=news_read&id=$row[id]>Читать дальше</a></div>";
    }
    ;
    $rz ++;
}
;
if ($rz == 0) {
    echo "error";
}
;
?>