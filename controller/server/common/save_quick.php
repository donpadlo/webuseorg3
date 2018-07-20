<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
$title = _POST("title");
$url = mysqli_real_escape_string($sqlcn->idsqlconnection, _POST("url"));
$ico = mysqli_real_escape_string($sqlcn->idsqlconnection, _POST("ico"));

if (($title != "") and ($url != "")) {
    // проверяем, а нет ли уже такой закладки?
    $sql = "SELECT * FROM users_quick_menu WHERE userid='$user->id' and url='$url'";
    $result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос выборки закладок: ' . mysqli_error($sqlcn->idsqlconnection));
    $cnt = 0;
    while ($myrow = mysqli_fetch_array($result)) {
        $cnt ++;
        $id = $myrow["id"];
    }
    ;
    if ($cnt == 0) {
        $sql = "insert into users_quick_menu (title,url,userid,ico) values ('$title','$url','$user->id','$ico')";
        $result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос вставки закладок: ' . mysqli_error($sqlcn->idsqlconnection));
        echo "Закладка добавлена в быстрые ссылки!";
    } else {
        $sql = "delete from users_quick_menu where id='$id'";
        $result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос удаления закладок: ' . mysqli_error($sqlcn->idsqlconnection));
        
        echo "Закладка удалена!";
    }
    ;
} else {
    echo "Что-то пошло не так...";
}
;

?>