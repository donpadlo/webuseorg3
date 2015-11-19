<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// соеденяемся с БД, получаем $mysql_base_id
	

if (isset($_GET["eqid"])) {$id = $_GET['eqid'];} else {$id="";};
if (isset($_POST["oper"])) {$oper = $_POST['oper'];} else {$oper="";};
if (isset($_POST["param"])) {$param = $_POST['param'];} else {$param="";};
if (isset($_POST["id"])) {$paramidid = $_POST['id'];} else {$paramidid="";};
if ($id==""){$id = $_POST['eqid'];};
//echo "!$paramid!";
    $responce=new stdClass();
// получаем группу номенклатуры
    $SQL = "SELECT equipment.id,nome.id as nomeid,nome.groupid AS groupid FROM equipment INNER JOIN nome ON nome.id=equipment.nomeid WHERE (equipment.id='$id') AND (nome.active=1)";
    //echo "!$SQL!";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось найти группу!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
        $groupid=$row["groupid"];
    };
    if ($groupid=="") {echo "Нет параметров у группы!";die();};
    //echo "!$groupid!";
// получаем список параметров группы
    $SQL = "SELECT id,name FROM group_param WHERE (groupid='$groupid') AND (active=1)";
    //echo "!$SQL!";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось найти параметры!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
        $paramid=$row["id"];
        $name=$row["name"];
        //echo "!$paramid!$name!";
            // проверяем, если какогото параметра нет, то добавляем его в основную таблице связанную с оргнехникой
            $SQL = "SELECT id FROM eq_param WHERE (grpid='$groupid') AND (eqid='$id') AND (paramid='$paramid')";
            $res2 = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось выбрать существующие параметры!".mysqli_error($sqlcn->idsqlconnection));
            $cnt=0;
            while($row2 = mysqli_fetch_array($res2)) {$cnt++;};
            // если параметра нет, то добавляем...
            if ($cnt==0){
                $SQL="INSERT INTO eq_param (id,grpid,paramid,eqid) VALUES (NULL,'$groupid','$paramid','$id')";
             	 $rs = $sqlcn->ExecuteSQL($SQL);                
  		 if ($rs==''){die('Не смог добавить параметр!: ' . mysqli_error($sqlcn->idsqlconnection));}

            };
    };

// получаем список параметров конкретной позиции     
    $SQL = "SELECT eq_param.id as pid,group_param.name as pname,eq_param.param as pparam FROM eq_param INNER JOIN group_param ON group_param.id=eq_param.paramid WHERE (eqid='$id')";
   // echo "!$SQL!";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось найти параметры!".mysqli_error($sqlcn->idsqlconnection));
    $i=0;
    while($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id']=$row["pid"];
		$responce->rows[$i]['cell']=array(
		$row["pid"],$row["pname"],$row["pparam"]
		);
	    $i++;
        
    };
    echo json_encode($responce);
// если просто листаем, тогда
if ($oper=='edit')
{
              $sql="UPDATE eq_param SET eq_param.param='$param' WHERE id='$paramidid'";
            //  echo "!$sql!";
  		$result = $sqlcn->ExecuteSQL($sql);                
  		if ($result==''){die('Не смог изменить параметр!: ' . mysqli_error($sqlcn->idsqlconnection));}    
};

if ($oper=='del')
{
              $sql="DELETE FROM eq_param WHERE id='$paramidid'";
              echo "!$sql!";
  		$result =  $sqlcn->ExecuteSQL($sql,$cfg->base_id);                
  		if ($result==''){die('Не смог удалить параметр!: ' . mysqli_error($sqlcn->idsqlconnection));}    
};


?>