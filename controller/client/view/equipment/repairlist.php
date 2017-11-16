<?php
include_once ("../../../../config.php"); // загружаем первоначальные настройки
                                         
// загружаем классы

include_once ("../../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../../class/config.php"); // загружаем классы настроек
include_once ("../../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../../class/employees.php"); // загружаем классы работы с профилем пользователя
                                                 
// загружаем все что нужно для работы движка

include_once ("../../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../../inc/functions.php"); // загружаем функции
include_once ("../../../../inc/login.php"); // логинимся

if (isset($_GET["id"])) {
    $id = $_GET['id'];
} else {
    $id = "";
}
;

?>
<table id="list_rep"></table>
<div id="pager_rep"></div>
<div id="comment_rep"></div>
<script>repid="<?php echo "$id";?>";</script>
<script type="text/javascript" src="controller/client/js/repair.js"></script>