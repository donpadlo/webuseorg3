<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("../../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../../inc/functions.php");		// загружаем функции
include_once("../../../../../inc/login.php");		// логинимся

include_once("../../../../../class/groups.class.php");

$groups = new PhoneGroups($mysql_host,$mysql_user,$mysql_pass,$mysql_base);

$cat = $_REQUEST["cat"];
$act = $_REQUEST["act"];

switch($cat) {
    case "users":
	switch($act) {
	    case "show":
		//Показать всех пользователей
		$result = $groups->getUsers();
		echo json_encode(array("c"=>count($result),"data"=>$result));
		break;
	    case "getform":
		//Форма добавления/редактирования пользователя
		$result = file_get_contents("user_form.html");
		echo $result;
		break;
	    case "getuser":
		$result = $groups->getUser($_REQUEST["id"]);
		echo json_encode($result);
		break;
	    case "deluser":
                $id=$_REQUEST["id"];
                $sql="delete from sms_users where id=$id";
                $result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу удалить телефон!".mysqli_error($sqlcn->idsqlconnection));
		break;            
	    case "insert":
		//Добавление пользователя
		$mes = "";
		$name = $_REQUEST["name"];
		$phone = $_REQUEST["phone"];
		if (strlen($phone)!=11) $mes = "Неверно указан номер телефона.<br /> ";
		if ($name=="") $mes = "Не указано ФИО. ";
		if ($mes!="") { 
		    echo json_encode(array("error"=>1,"errormes"=>$mes)); 
		    }
		else {
		    //добавляем пользователя в базу  
		    $res = $groups->insertUser($name,$phone);
		    if ($res!=false) {
			echo json_encode(array("error"=>0,"errormes"=>$mes));
			}
			else
			{
			$mes = "Пользователь уже существует. ";
			echo json_encode(array("error"=>1,"errormes"=>$mes));
			}
		    }
		break;
	    case "saveedituser":
		$mes = "";
		$id = $_REQUEST["id"];
		$name = $_REQUEST["name"];
		$phone = $_REQUEST["phone"];
		if (strlen($phone)!=11) $mes = "Неверно указан номер телефона.<br /> ";
		if ($name=="") $mes = "Не указано ФИО. ";
		if ($mes!="") { 
		    echo json_encode(array("error"=>1,"errormes"=>$mes)); 
		    }
		else {
		    //добавляем пользователя в базу  
		    $res = $groups->saveEditUser($id,$name,$phone);
		    echo json_encode(array("error"=>0,"errormes"=>$mes));
		    }
		break;
	}
	break;
    case "groups":
	switch($act) {
	    case "delgroup":
                $id=$_REQUEST["id"];
                $sql="delete from sms_groups where id=$id";
                $result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу удалить группу!".mysqli_error($sqlcn->idsqlconnection));
		break;            
	    case "show":
		//Показать все группы
		$result = $groups->getGroups();
		echo json_encode(array("c"=>count($result),"data"=>$result));
		break;
	    case "getform":
		//Форма добавления/редактирования группы
		$result = file_get_contents("group_form.html");
		echo $result;
		break;
	    case "geteditform":
		//Форма добавления/редактирования группы
		$result = file_get_contents("group_edit_form.html");
		echo $result;
		break;
	    case "insert":
		//Добавление группы
		$mes = "";
		$name = $_REQUEST["name"];
		if (strlen($name)==0) $mes = "Пустое название группы. ";
		if ($mes!="") { 
		    echo json_encode(array("error"=>1,"errormes"=>$mes)); 
		    }
		else {
		    //добавляем пользователя в базу  
		    $res = $groups->insertGroup($name);
		    if ($res!=false) {
			echo json_encode(array("error"=>0,"errormes"=>$mes));
			}
			else
			{
			$mes = "Группа уже существует. ";
			echo json_encode(array("error"=>1,"errormes"=>$mes));
			}
		    }
		break;
	    case "getmembers":
		//Показать всех пользователей в группе
		$result = $groups->getGroupMembers($_REQUEST["id"]);
		echo json_encode(array("c"=>count($result),"data"=>$result));
		break;
	    case "getgroupname":
		$result = $groups->getGroupName($_REQUEST["id"]);
		echo json_encode($result);
		break;
	    case "saveeditgroup":
		$group_id = $_REQUEST["id"];
		$members = $_REQUEST["users"];
		$name = $_REQUEST["name"];
		$result = $groups->saveEditGroup($group_id,$name,$members);
		echo json_encode($result);
		break;
	    
	}
	break;
}

?>