<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

class Tusers {

	var $id; // идентификатор пользователя
	var $randomid; // случайный идентификатор (время от времени может менятся)
	var $orgid; // принадлежность к организации
	var $login; // логин
	var $password; // хешированный пароль
	var $salt; // соль для хеширования пароля
	var $email; // электронная почта
	var $mode; // 0 - пользователь 1- админ
	var $lastdt; // дата и время последнего посещения
	var $active; // 1-не помечен на удаление
	// далее выдергивается из профиля если оный есть по GetById
	var $fio; // фамилия имя отчество
	var $telephonenumber; // телефонный номер (сотовый)
	var $homephone; // телефонный номер (альтернатива)
	var $jpegphoto; // фотография из папки photos
	var $tab_num; // табельный номер
	var $post; // должность

	/**
	 * Проверяем соответствие роли
	 * 
	 * Роли:  
	 *            1="Полный доступ"
	 *            2="Просмотр финансовых отчетов"
	 *            3="Просмотр"
	 *            4="Добавление"
	 *            5="Редактирование"
	 *            6="Удаление"
	 *            7="Отправка СМС"
	 *            8="Манипуляции с деньгами"    
	 *            9="Редактирование карт"    
	 * 
	 * @global type $sqlcn
	 * @param type $roles
	 * @return boolean
	 */

	function TestRoles($roles) {
		global $sqlcn;
		/*$rol = explode(',', $roles);
		$rz = false;
		foreach ($rol as $key) {
			$sql = "SELECT * FROM usersroles WHERE userid='$this->id' AND role='$key'";
			$result = $sqlcn->ExecuteSQL($sql)
					or die('Неверный запрос Tusers.TestRoles: '.mysqli_error($sqlcn->idsqlconnection));
			while ($myrow = mysqli_fetch_array($result)) {
				$rz = true;
			}
		}
		return $rz;*/
		$sql = "SELECT * FROM usersroles WHERE userid='$this->id' AND role IN ($roles)";
		$result = $sqlcn->ExecuteSQL($sql)
			or die('Неверный запрос Tusers.TestRoles: '.mysqli_error($sqlcn->idsqlconnection));
		return (mysqli_num_rows($result) > 0);
	}

	/**
	 * Обновляем данные о последнем посещении
	 * @global type $sqlcn
	 * @param type $id
	 */
	function UpdateLastdt($id) {
		global $sqlcn;
		$lastdt = date('Y-m-d H:i:s');
		$sqlcn->ExecuteSQL("UPDATE users SET lastdt='$lastdt' WHERE id='$id'")
				or die('Неверный запрос Tusers.UpdateLastdt: '.mysqli_error($sqlcn->idsqlconnection));
	}

	/**
	 * Обновляем данные о текущем пользователе в базу
	 * @global type $sqlcn
	 */
	function Update() {
		global $sqlcn;
		// ToDo $sqlcn->escape() все параметры
		$sqlcn->ExecuteSQL("UPDATE users SET orgid='$this->orgid', login='$this->login',"
						." `password`='$this->password', salt='$this->salt',"
						." email='$this->email', mode='$this->mode',"
						." active='$this->active' WHERE id='$this->id'")
				or die('Неверный запрос Tusers.Update (1): '.mysqli_error($sqlcn->idsqlconnection));
		$sqlcn->ExecuteSQL("UPDATE users_profile SET fio='$this->fio',"
						." telephonenumber='$this->telephonenumber',"
						." homephone='$this->homephone',jpegphoto='$this->jpegphoto',"
						." code='$this->tab_num',post='$this->post'"
						." WHERE usersid='$this->id'")
				or die('Неверный запрос Tusers.Update (2): '.mysqli_error($sqlcn->idsqlconnection));
	}

	/**
	 * Получить данные о пользователе по логину
	 * @global type $sqlcn
	 * @param type $login
	 */
	function GetByLogin($login) {
		global $sqlcn;
		$login = $sqlcn->escape($login);
		$result = $sqlcn->ExecuteSQL("SELECT users_profile.*, users.*,
			users.id AS sid FROM users
			INNER JOIN users_profile ON users_profile.usersid = users.id
			WHERE users.login ='$login'")
				or die('Неверный запрос Tusers.GetByLogin: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['sid'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			$this->telephonenumber = $myrow['telephonenumber'];
			$this->jpegphoto = $myrow['jpegphoto'];
			$this->homephone = $myrow['homephone'];
			$this->fio = $myrow['fio'];
			$this->tab_num = $myrow['code'];
			$this->post = $myrow['post'];
			return true;
		}
		return false;
	}

	/**
	 * Получить данные о пользователе по логину/паролю
	 * @global type $sqlcn
	 * @param type $login
	 * @param type $pass
	 */
	function GetByLoginPass($login, $pass) {
		global $sqlcn;
		$login = $sqlcn->escape($login);
		$pass = $sqlcn->escape($pass);
		$result = $sqlcn->ExecuteSQL("SELECT users_profile.*, users.*,
			users.id AS sid FROM users
			INNER JOIN users_profile ON users_profile.usersid=users.id
			WHERE users.login='$login' AND users.`password`=SHA1(CONCAT(SHA1('$pass'), users.salt))")
				or die('Неверный запрос Tusers.GetByLoginPass: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['sid'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			$this->telephonenumber = $myrow['telephonenumber'];
			$this->jpegphoto = $myrow['jpegphoto'];
			$this->homephone = $myrow['homephone'];
			$this->fio = $myrow['fio'];
			$this->tab_num = $myrow['code'];
			$this->post = $myrow['post'];
			return true;
		}
		return false;
	}

	/**
	 * Получить данные о пользователе по идентификатору
	 * @global type $sqlcn
	 * @param type $idz
	 */
	function GetById($idz) {
		global $sqlcn;
		$idz = $sqlcn->escape($idz);
		$result = $sqlcn->ExecuteSQL("SELECT users_profile.*, users.*,
			users.id AS sid FROM users
			INNER JOIN users_profile ON users_profile.usersid=users.id
			WHERE users.id ='$idz'")
				or die('Неверный запрос Tusers.GetById: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['sid'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			$this->telephonenumber = $myrow['telephonenumber'];
			$this->jpegphoto = $myrow['jpegphoto'];
			$this->homephone = $myrow['homephone'];
			$this->fio = $myrow['fio'];
			$this->tab_num = $myrow['code'];
			$this->post = $myrow['post'];
			return true;
		}
		return false;
	}

	/**
	 * Получить данные о пользователе по идентификатору TRUE - нашли, FALSE - не нашли
	 * @global type $sqlcn
	 * @param type $id
	 * @return boolean
	 */
	function GetByRandomId($id) {
		global $sqlcn;
		$id = $sqlcn->escape($id);
		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE randomid='$id'");
		$result = $sqlcn->ExecuteSQL("SELECT users_profile.*, users.*,
			users.id AS sid FROM users
			INNER JOIN users_profile ON users_profile.usersid=users.id
			WHERE users.randomid='$id'")
				or die('Неверный запрос Tusers.GetByRandomId: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['sid'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			$this->telephonenumber = $myrow['telephonenumber'];
			$this->jpegphoto = $myrow['jpegphoto'];
			$this->homephone = $myrow['homephone'];
			$this->fio = $myrow['fio'];
			$this->tab_num = $myrow['code'];
			$this->post = $myrow['post'];
			return true;
		}
		return false;
	}

	/**
	 * Получить данные о пользователе по идентификатору TRUE - нашли, FALSE - не нашли. БЕЗ ПРОФИЛЯ
	 * @global type $sqlcn
	 * @param type $id
	 * @return boolean
	 */
	function GetByRandomIdNoProfile($id) {
		global $sqlcn;
		$id = $sqlcn->escape($id);
		$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE randomid='$id'")
				or die('Неверный запрос Tusers.GetByRandomId: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['id'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			return true;
		}
		return false;
	}

	/**
	 * Добавляем пользователя с текущими данными
	 * @global type $sqlcn4
	 * @param string $randomid
	 * @param string $orgid
	 * @param string $login
	 * @param string $pass Открытый пароль
	 * @param string $email
	 * @param string $mode
	 */
	function Add($randomid, $orgid, $login, $pass, $email, $mode) {
		global $sqlcn;
		$this->randomid = $randomid;
		$this->orgid = $orgid;
		$this->login = $login;
		// Хешируем пароль
		$this->salt = generateSalt();
		$this->password = sha1(sha1($pass).$this->salt);
		$this->email = $email;
		$this->mode = $mode;
		$sql = "INSERT INTO users (id, randomid, orgid, login, password, salt,
			email, mode, lastdt, active) VALUES (NULL, '$this->randomid',"
				." '$this->orgid', '$this->login', "
				." '$this->password', '$this->salt', "
				." '$this->email', '$this->mode', NOW(), 1)";
		$sqlcn->ExecuteSQL($sql)
				or die('Неверный запрос Tusers.Add (1): '.mysqli_error($sqlcn->idsqlconnection));
		$fio = $this->fio;
		$code = $this->tab_num;
		$telephonenumber = $this->telephonenumber;
		$homephone = $this->homephone;
		$jpegphoto = $this->jpegphoto;
		$rid = $this->randomid;
		$post = $this->post;

		$zx = new Tusers;

		if ($zx->GetByRandomIdNoProfile($rid)) {
			// добавляю профиль
			$sql = "INSERT INTO users_profile (id, usersid, fio, code,
				telephonenumber, homephone, jpegphoto, post, faza, enddate,
				res1) VALUES (NULL, '$zx->id', '$fio', '$code',"
					." '$telephonenumber', '$homephone', '$jpegphoto',"
					." '$post', '', NOW(), '')";
			$sqlcn->ExecuteSQL($sql)
					or die('Неверный запрос Tusers.Add(2): '.mysqli_error($sqlcn->idsqlconnection));
		} else {
			die('Не найден пользователь по randomid Tusers.Add');
		}
	}

	/**
	 * Получить данные о пользователе по  коду из профиля FALSE - если ничего не нашли
	 * @global type $sqlcn
	 * @param type $code
	 * @return boolean
	 */
	function GetByCode($code) {
		global $sqlcn;
		$code = $sqlcn->escape($code);
		$result = $sqlcn->ExecuteSQL("SELECT users_profile.*, users.*
			FROM users_profile
			INNER JOIN users ON users_profile.usersid=users.id
			WHERE users_profile.code='$code'")
				or die('Неверный запрос Tusers.GetByCode: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['usersid'];
			$this->randomid = $myrow['randomid'];
			$this->orgid = $myrow['orgid'];
			$this->login = $myrow['login'];
			$this->password = $myrow['password'];
			$this->salt = $myrow['salt'];
			$this->email = $myrow['email'];
			$this->mode = $myrow['mode'];
			$this->lastdt = $myrow['lastdt'];
			$this->active = $myrow['active'];
			$this->telephonenumber = $myrow['telephonenumber'];
			$this->jpegphoto = $myrow['jpegphoto'];
			$this->homephone = $myrow['homephone'];
			$this->fio = $myrow['fio'];
			$this->post = $myrow['post'];
			return true;
		}
		return false;
	}

}
