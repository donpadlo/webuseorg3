<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// печатная форма?
$printable = false;
if (isset($_GET["printable"]) == true) $printable = $_GET["printable"];

// есть альтернативный заголовок?
if (isset($alterhead)) {
    include_once ($alterhead);
} else
    include_once ("header.php"); // заголовок страницы или из переменной alterhead или стандарный
                                                                                       
// если не печатная форма, то показываем ВСЁ
if ($printable == false) {
    include_once ("menus.php");	     // главное меню
    include_once ("navbar.php");     // навигация (хлебные крошки)
    include_once ("messagebar.php"); // отображение сообщений пользователю (если есть)
    echo "<div id='ajaxpage'>";
	// загружаю основное тело страницы
	include_once ("controller/client/themes/$cfg->theme/$content_page.php");    
    echo "</div>";
    include_once ("footer.php");    
} else {
    // загружаю основное тело страницы
    include_once ("controller/client/themes/$cfg->theme/$content_page.php");    
};

?>