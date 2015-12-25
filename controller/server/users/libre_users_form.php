<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

// Получаем переменные, проверяем на правильность заполнения
$step = GetDef('step');
$orgid = PostDef('orgid');
if ($orgid == '') {
	$err[] = 'Не выбрана организация!';
}
$login = PostDef('login');
if ($login == '') {
	$err[] = 'Не задан логин!';
}
$pass = PostDef('pass');
$email = PostDef('email');
if ($email == '') {
	$err[] = 'Не задан E-mail!';
}
$mode = PostDef('mode');
if ($mode == '') {
	$err[] = 'Не задан режим!';
}
if (!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.[a-zA-Z]{2,4}$/', $email)) {
	$err[] = 'Не верно указан E-mail';
}

if ($step == 'add') {
	if ($pass == '') { // пароль не может быть пустым при добавлении пользователя
		$err[] = 'Не задан пароль!';
	}
	if (DoubleLogin($login) != 0) {
		$err[] = 'Такой логин уже есть в базе!';
	}
	if (DoubleEmail($email) != 0) {
		$err[] = 'Такой E-mail уже есть в базе!';
	}
}

// Закончили всяческие проверки    

// Добавляем пользователя   
if ($step == 'add') {
	if (count($err) == 0) {
		$tmpuser = new Tusers;
		$tmpuser->active = 1;
		$tmpuser->fio = $login;
		$tmpuser->Add(GetRandomId(60), $orgid, $login, $pass, $email, $mode);
	}
}

if ($step == 'edit') {
	if (count($err) == 0) {
		$id = GetDef('id');
		$ps = ($pass != '') ? " password=SHA1(CONCAT(SHA1('$pass'), salt))," : '';
		$sql = "UPDATE users SET orgid='$orgid', login='$login',$ps email='$email', mode='$mode' WHERE id='$id'";
		$sqlcn->ExecuteSQL($sql, $cfg->base_id) or
			die('Не смог изменить пользователя!: '.mysqli_error($sqlcn->idsqlconnection));
	}
}

if (count($err) == 0) {
	echo 'ok';
} else {
	echo '<script>$("#messenger").addClass("alert alert-error");</script>';
	for ($i = 0; $i < count($err); $i++) {
		echo "$err[$i]<br>";
	}
}
