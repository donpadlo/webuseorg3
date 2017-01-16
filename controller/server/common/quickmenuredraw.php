<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

$sql="SELECT * FROM users_quick_menu WHERE userid='$user->id'";
$result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос выборки закладок: '.mysqli_error($sqlcn->idsqlconnection));

while ($myrow = mysqli_fetch_array($result)) {
 $id=$myrow["id"];    
 $title=$myrow["title"];    
 $url=$myrow["url"];    
 $ico=$myrow["ico"];    
 echo '<a title="'.$title.'" href="'.$url.'"><button type=\'button\' class=\'btn btn-default navbar-btn \'>'.$ico.'</button></a>';
};


?>
