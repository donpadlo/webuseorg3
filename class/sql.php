<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tsql {

	// Идентификатор соединения с БД
	var $idsqlconnection;

	// Соединяемся с БД и выбираем таблицу, получаем $idsqlconnection
	function connect($host, $name, $pass, $base) {
		global $codemysql;
		$this->idsqlconnection = new mysqli($host, $name, $pass, $base);
		if (mysqli_connect_errno()) {
			$serr = mysqli_connect_error();
			echo "Error connect to Mysql or select base: $serr";
			return $serr;
		} else {
			$result = mysqli_query($this->idsqlconnection, "SET NAMES $codemysql");
			$result = mysqli_query($this->idsqlconnection, "SET sql_mode=''");
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
		return mysqli_query($this->idsqlconnection, $sql);
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
		return mysqli_close($this->idsqlconnection);
	}

}
