<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Temployees {

	var $id; // идентификатор 
	var $usersid; // связь с пользователем
	var $faza; // в какой фазе пользователь (например в отпуске)
	var $code; // связь с ERP
	var $enddate; // дата когда фаза кончится
	var $post; // Должность

	/**
	 * Добавляем профиль работника с текущими данными (все что заполнено)
	 * @global type $sqlcn
	 */

	function Add() {
		global $sqlcn;
		$sqlcn->ExecuteSQL("INSERT INTO users_profile (id, usersid, faza, code, enddate, post)"
						." VALUES (NULL, '$this->usersid', '$this->faza', '$this->code', '$this->enddate', '$this->post')")
				or die('Неверный запрос Temployees.Add: '.mysqli_error($sqlcn->idsqlconnection));
	}

	/**
	 * Обновляем профиль работника с текущими данными (все что заполнено)
	 * @global type $sqlcn
	 */
	function Update() {
		global $sqlcn;
		$sqlcn->ExecuteSQL("UPDATE users_profile"
						." SET fio='$this->fio', faza='$this->faza', code='$this->code', enddate='$this->enddate', post='$this->post'"
						." WHERE code='$this->code'")
				or die('Неверный запрос Temployees.Update: '.mysqli_error($sqlcn->idsqlconnection));
	}

	/**
	 * Обновляем профиль работника с текущими данными (все что заполнено)
	 * @global type $sqlcn
	 */
	function GetByERPCode() {
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL("SELECT * FROM users_profile WHERE code='$this->code'")
				or die('Неверный запрос Temployees.GetByERPCode: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['id'];
			$this->usersid = $myrow['usersid'];
			$this->fio = $myrow['fio'];
			$this->faza = $myrow['faza'];
			$this->enddate = $myrow['enddate'];
			$this->post = $myrow['post'];
		}
	}

	/**
	 * А есть ли такой в базе (проверка по ERPCode. Если есть - возврат 1, иначе 0
	 * @global type $sqlcn
	 * @param type $TERPCode
	 * @return boolean
	 */
	function EmployeesYetByERPCode($TERPCode) {
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL("SELECT * FROM users_profile WHERE code='$TERPCode'")
				or die('Ошибка (EmployeesYetByERPCode): '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			return true;
		}
		return false;
	}

}

?>
