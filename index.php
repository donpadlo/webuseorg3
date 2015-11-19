<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

$time_start = microtime(true);                  // засекаем когда начали выполнять скрипт

include_once ("config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("class/sql.php");                  // загружаем классы работы с БД
include_once("class/config.php");		// загружаем классы настроек
include_once("class/users.php");		// загружаем классы работы с пользователями
include_once("class/mod.php");                  // класс работы с модулями
include_once("class/cconfig.php");             // класс работы с пользовательскими настройками
include_once("class/bp.php");                  // класс работы с БП
include_once("class/class.phpmailer.php");	// класс управления почтой
include_once("class/menu.php");                  // класс работы с меню


// загружаем все что нужно для работы движка

include_once("inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id

include_once("inc/config.php");                 // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("inc/functions.php");		// загружаем функции
include_once("inc/login.php");			// проверяем вход пользователя
include_once("inc/autorun.php");		// запускаем сторонние скрипты

// инициализируем заполнение меню
$gmenu=new Tmenu();
$gmenu->GetFromFiles("inc/menu");

// если content_page не задан, то принудительно присваиваем
if (isset($_GET["content_page"])==FALSE){$_GET["content_page"]="home";}

// загружаем и выполняем сначала modules/$content_page.php , затем client/themes/$cfg->theme/$content_page.php
// если таких файлов нет, то принудительно выполняем только client/themes/$cfg->theme/home.php
if (isset($_GET["content_page"])) {
    $content_page=$_GET["content_page"];
    if (is_file("controller/client/themes/$cfg->theme/$content_page.php")==FALSE){$content_page="home";$err[]="Вы попытались открыть раздел которого нет!";};     
    if (is_file("modules/$content_page.php")==FALSE) {include_once("modules/home.php");} else {include_once("modules/$content_page.php");}
    include_once("controller/client/themes/$cfg->theme/index.php");            // загружаем главный файл темы, который разруливает что отображать на экране
}

include_once("inc/footerrun.php");		// запускаем сторонние скрипты

unset($gmenu);
?>