<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
$mode=  _GET("mode");

$sql = "SELECT * FROM users_quick_menu WHERE userid='$user->id'";
$result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос выборки закладок: ' . mysqli_error($sqlcn->idsqlconnection));

while ($myrow = mysqli_fetch_array($result)) {
    $id = $myrow["id"];
    $title = $myrow["title"];
    $url = $myrow["url"];
    $ico = $myrow["ico"];
    if ($mode=="menu") {
	echo "<li><a title='$title' href=\"$url\">$ico $title</a></li>";
    } else {
	echo '<a title="' . $title . '" href="' . $url . '"><button type=\'button\' class=\'btn btn-default navbar-btn btn-sm\'>' . $ico . '</button></a>';
    }    
}

?>
