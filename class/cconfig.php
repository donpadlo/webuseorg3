<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tcconfig {

	/** Получение значения хранимого параметра по имени параметра
	 * 
	 * @global type $sqlcn
	 * @param type $nameparam - имя параметра
	 * @return type
	 */
	function GetByParam($nameparam) {
		// получаем данные по идентификатору
		global $sqlcn;
		$resz = "";
/*		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='$nameparam'")
				or die('Неверный запрос Tcconfig.GetByParam: '.mysqli_error($sqlcn->idsqlconnection));
		$row = mysqli_fetch_array($result);
		$cnt = count($row);
		// или добавляем настройки или выдаем параметр
		if ($cnt == 0) {
			$sqlcn->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('$nameparam','')");
		}*/
		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='$nameparam'")
				or die('Неверный запрос Tcconfig.GetByParam: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$resz = $myrow['valueparam'];
		}
		return $resz;
	}

	/** Установить значение хранимого параметра
	 * 
	 * @global type $sqlcn
	 * @param type $nameparam - название параметра
	 * @param type $valparam - значение параметра
	 */
	function SetByParam($nameparam, $valparam) {
		// записываем данные по идентификатору
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='$nameparam'")
				or die('Неверный запрос Tcconfig.SetByParam: '.mysqli_error($sqlcn->idsqlconnection));
		$row = mysqli_fetch_array($result);
		$cnt = count($row);
		// или добавляем настройки или выдаем параметр
		if ($cnt == 0) {
			$result = $sqlcn->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('$nameparam','')");
		}
		$result = $sqlcn->ExecuteSQL("UPDATE config_common SET valueparam='$valparam' WHERE nameparam='$nameparam'")
				or die('Неверный запрос Tcconfig.SetByParam: '.mysqli_error($sqlcn->idsqlconnection));
	}

}
