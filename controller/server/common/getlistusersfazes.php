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
include_once ("../../../inc/login.php"); // загружаем функции

if (file_exists('../../../import/employees.xml')) {
    $xml = simplexml_load_file('../../../import/employees.xml');
    // echo "<table class='table table-hover'> <thead><tr><th>Фото</th><th>ФИО/Должность</th><th>Фаза</th></tr></thead><tbody>";
    $ts = new Tusers();
    foreach ($xml->employees as $em) {
        if (($em->faza != "Работает") and ($em->faza != "В отпуске по уходу за ребенком")) {
            // var_dump($em);
            $ts->GetByCode($em->code);
            $photo = "client/view/themes/$cfg->theme/noimage.jpg";
            if ($ts->jpegphoto != "") {
                $photo = "photos/$ts->jpegphoto";
            }
            ;
            echo "<div class='row-fluid'>";
            echo "<div class='span2'>
                  <ul class='thumbnails'>
                    <li class='span12'>
                        <a href='#' class='thumbnail'>
                            <img src='$photo' alt=''>
                        </a>
                    </li> 
                </ul>";
            echo "</div>";
            echo "<div class='span8'>";
            echo "<h4>$em->fio</h4>";
            echo "<p class='text-info'>$em->post<br>";
            echo "$em->faza до $em->enddate</p>";
            echo "</div>";
            echo "</div>";
            // echo "<tr><td>
            // <ul class='thumbnails'>
            // <li class='span12'>
            // <a href='#' class='thumbnail'>
            // <img src='$photo' alt=''>
            // </a>
            // </li>
            // </ul>
            // </td><td>$em->fio</br>$em->post</td><td>$em->faza</td></tr>";
        }
        ;
    }
    ;
    
    // echo "</tbody></table>";
}
;
?>