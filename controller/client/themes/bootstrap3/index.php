<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

//печатня форма?
if (isset($_GET["printable"])==true){$printable=$_GET["printable"];} else {$printable=false;};
//есть альтернативный заголовок?
if (isset($alterhead)){include_once($alterhead);} else include_once("header.php");     // заголовок страницы или из переменной alterhead или стандарный

// если не печатная форма, то показываем ВСЁ
if ($printable==false){
    include_once("menus.php");      // главное меню
    include_once("navbar.php");      // главное меню
    include_once("messagebar.php"); // отображение сообщений пользователю (если есть)
};

include_once("controller/client/themes/$cfg->theme/$content_page.php");            

if ($printable==false){
    include_once("footer.php");     // подвал страницы    
};
?>