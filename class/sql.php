<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

class Tsql {

	var $idsqlconnection; // Идентификатор соединения с БД
	var $query_result; // Результат запроса
	var $num_queries = 0; // Количество запросов

	/**
	 * Соединяемся с БД и выбираем таблицу, получаем $idsqlconnection
	 * @global type $codemysql
	 * @param type $host
	 * @param type $name
	 * @param type $pass
	 * @param type $base
	 * @return type
	 */
	function connect($host, $name, $pass, $base) {
		global $codemysql;
		$this->idsqlconnection = new mysqli($host, $name, $pass, $base);
		if (mysqli_connect_errno()) {
			$serr = mysqli_connect_error();
			echo "Error connect to Mysql or select base: $serr";
			return $serr;
		} else {
			mysqli_query($this->idsqlconnection, "SET NAMES $codemysql");
			mysqli_query($this->idsqlconnection, "SET sql_mode=''");
			mysqli_set_charset($this->idsqlconnection, "$codemysql");
		}
	}

	function ExecuteSQL($sql) {
		//echo "$sql<br>";
		//$result = mysqli_query($this->idsqlconnection, $sql);
		//if ($result == '') {
		//	echo mysqli_connect_error();
		//}
		//return $result;
		$this->query_result = mysqli_query($this->idsqlconnection, $sql);
		if ($this->query_result) {
			++$this->num_queries;
			return $this->query_result;
		} else {
			return false;
		}
	}

	function get_num_queries() {
		return $this->num_queries;
	}

	function escape($str) {
		return mysqli_real_escape_string($this->idsqlconnection, $str);
	}

	function start_transaction() {
		return mysqli_query($this->idsqlconnection, 'START TRANSACTION');
		//return mysqli_begin_transaction($this->idsqlconnection);
	}

	function commit() {
		return mysqli_query($this->idsqlconnection, 'COMMIT');
		//return mysqli_commit($this->idsqlconnection);
	}

	function rollback() {
		return mysqli_query($this->idsqlconnection, 'ROLLBACK');
		//return mysqli_rollback($this->idsqlconnection);
	}

	function close() {
		if ($this->idsqlconnection) {
			if ($this->query_result) {
				mysqli_free_result($this->query_result);
			}
			return mysqli_close($this->idsqlconnection);
		} else {
			return false;
		}
	}

}
