<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

$user = new Tusers;

// Если есть печеньки,то получаем сессионный идентификатор
if (isset($_COOKIE['user_randomid_w3'])) {
	$user->randomid = $_COOKIE['user_randomid_w3'];
	SetCookie('user_randomid_w3', "$user->randomid", time() + 3600000, '/'); // ну и обновляем заодно время жизни печеньки
} else {
	$user->randomid = '';
}

// если есть кукисы, то заполняем данные по пользователю ГЛОБАЛЬНО в переменную $user
// если кукисов нет, или они не верные,то $user->randomid делаем пустым
if ($user->randomid != '') {
	$user->GetByRandomId($user->randomid);
	// Если пользователя не нашли, то кук взялся непонятно откуда. Трем его!
	if ($user->id == '') {
		SetCookie('user_randomid_w3', '', time() - 3600, '/');
		$user->randomid = '';
	} else {	   // если нашли, то
		$user->UpdateLastdt($user->id); // обновляем дату последнего входа пользователя
	}
}

// обрабатываем попытку войти/зарегистрироваться
if (isset($_GET['login_step'])) {
	if ($_GET['login_step'] == 'logout') { // если выход то стираем кукисы и ГО на главную страницу
		SetCookie('user_randomid_w3', '', time() - 3600, '/');
		$user->randomid = '';
		$user->id = '';
	}
	if ($_GET['login_step'] == 'enter') { // если вход то пытаемся зайти
		$enter_user_login = $_POST['enter_user_login'];
		if ($enter_user_login == '') {
			$err[] = 'Логин не может быть пустым!';
		}
		$enter_user_pass = $_POST['enter_user_pass'];
		if ($enter_user_pass == '') {
			$err[] = 'Пароль не может быть пустым!';
		}
		if (count($err) == 0) { // если буфер ошибок пустой, то ищем пользователя такого
			$user->GetByLoginPass($enter_user_login, $enter_user_pass);
			if ($user->randomid != '') { // если нашли, то ставим печеньки
				SetCookie('user_randomid_w3', "$user->randomid", time() + 3600000, '/');
			} else { // если не нашли в "обычном" списке, проверяем в ADу (если разрешено в настойках)           
				if (($cfg->ad == 1) and (check_LDAP_user(strtolower($enter_user_login),
						$enter_user_pass, $cfg->ldap, $cfg->domain1, $cfg->domain2) == 'true')) {
					$user->GetByLogin($enter_user_login);
					if ($user->randomid != '') {// если нашли, то ставим печеньки
						SetCookie('user_randomid_w3', "$user->randomid", time() + 360000, '/');
					} else {
						$err[] = 'Пользователь с таким логином найден в AD, но не найден в базе!';
					}
				} else {
					$err[] = 'Пользователь с таким логином/паролем не найден!';
					//if ($cfg->usercanregistrate==1){$err[]="Вы можете <a href=?content_page=registration>зарегистрироваться</a>";}
				}
			}
		}
	}
}
