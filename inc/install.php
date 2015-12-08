<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики: Грибов Павел, Сергей Солодягин (добавляйте себя если что-то делали)
// http://грибовы.рф

define('ROOT', dirname(__FILE__));
include_once(ROOT.'/functions.php'); // Класс работы с БД
$host = _POST('host');
$basename = _POST('basename');
$baseusername = _POST('baseusername');
$passbase = _POST('bassbase');
$orgname = _POST('orgname');
$login = _POST('login');
$pass = _POST('pass');

$idsqlconnection = @new mysqli($host, $baseusername, $passbase, $basename);
if (mysqli_connect_errno()) {
	$serr = mysqli_connect_error();
	echo "<div class='alert alert-danger'>Ошибка БД: $serr</div>";
	die();
}
$handle = file_get_contents(ROOT.'/../webuser.sql', 'r');
if ($handle == false) {
	echo "<div class='alert alert-danger'>Ошибка открытия файла: webuser.sql</div>";
	die();
}
//ну и теперь меняю название организации и логин/пароль пользователя 
$orgname = mysqli_real_escape_string($idsqlconnection, $orgname);
$sql = "UPDATE org SET name='$orgname';";
$handle = $handle.$sql;
$salt = generateSalt();
$password = sha1(sha1($pass).$salt);
$sql = "UPDATE users SET pass='$pass',password='$password',salt='$salt',login='$login' WHERE id=1;";
$handle = $handle.$sql;
$result = mysqli_multi_query($idsqlconnection, $handle);
if ($result == '') {
	$serr = mysqli_error($idsqlconnection);
	echo "<div class='alert alert-danger'>Ошибка БД: $serr</div>";
	die();
}
echo 'ok';
?>
