<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф

include_once('../inc/functions.php'); // Класс работы с БД
$host=_POST("host");
$basename=_POST("basename");
$baseusername=_POST("baseusername");
$passbase=_POST("bassbase");
$orgname=_POST("orgname");
$login=_POST("login");
$pass=_POST("pass");

$idsqlconnection = @new mysqli($host, $baseusername, $passbase, $basename);
if (mysqli_connect_errno()) {
    $serr = mysqli_connect_error();
    echo "<div class='alert alert-danger'>Ошибка БД: $serr</div>";
    die();    
};
$handle = file_get_contents("../webuser.sql", "r");
if ($handle==false){
    echo "<div class='alert alert-danger'>Ошибка открытия файла: webuseorg empty .sql</div>";
    die();        
};
//ну и теперь меняю название организации и логин/пароль пользователя 
$orgname=mysqli_real_escape_string($idsqlconnection,$orgname);
$sql="update org set name='$orgname';";
$handle=$handle.$sql;
$salt = generateSalt();
$password = sha1(sha1($pass).$salt);
$sql="update users set pass='$pass',password='$password',salt='$salt',login='$login' where id=1;";
$handle=$handle.$sql;
$result = mysqli_multi_query($idsqlconnection, $handle);
if ($result == '') {
    $serr = mysqli_error($idsqlconnection);
    echo "<div class='alert alert-danger'>Ошибка БД: $serr</div>";
    die();    
};
echo "ok";
?>
