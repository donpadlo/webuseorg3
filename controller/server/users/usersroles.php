<?php

/* 
 * (с) 2014 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

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
include_once("../../../inc/login.php");		// загружаем функции

if (isset($_GET["page"]))       {$page = $_GET['page'];}    else {$page="";};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];}   else {$limit="";};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx']; }   else {$sidx="";};
if (isset($_GET["sord"]))       {$sord = $_GET['sord']; }   else {$sord="";};
if (isset($_POST["id"]))        {$id= $_POST['id'];}        else {$id="";};
if (isset($_POST["role"]))        {$role= $_POST['role'];}  else {$role="";};
if (isset($_GET["userid"]))       {$userid = $_GET['userid']; }   else {$userid="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};

if ($oper==''){	

	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM usersroles where userid='$userid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM usersroles where userid='$userid' ORDER BY $sidx $sord LIMIT $start , $limit";
	//echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список ролей пользователей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
            if ($row['role']=="1"){$role="Полный доступ";};
            if ($row['role']=="2"){$role="Просмотр финансовых отчетов";};
            if ($row['role']=="3"){$role="Просмотр количественных отчетов";};
            if ($row['role']=="4"){$role="Добавление";};
            if ($row['role']=="5"){$role="Редактирование";};            
            if ($row['role']=="6"){$role="Удаление";};            
            if ($row['role']=="7"){$role="Отправка СМС";};            
            if ($row['role']=="8"){$role="Манипуляции с деньгами";};            
            if ($row['role']=="9"){$role="Редактирование карт";};            
		$responce->rows[$i]['cell']=array($row['id'],$role);
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='add'){	
	$SQL = "INSERT INTO usersroles (userid,role) VALUES ('$userid','$role')";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить роль пользователя!".mysqli_error($sqlcn->idsqlconnection));      
};
if ($oper=='del'){	
	$SQL = "DELETE FROM usersroles where id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить роль пользователя!".mysqli_error($sqlcn->idsqlconnection));      
};

/*
        $sts=$sts."<option value=1>Полный доступ</option>";
        $sts=$sts."<option value=2>Просмотр финансовых отчетов</option>";
        $sts=$sts."<option value=3>Просмотр количественных отчетов</option>";
        $sts=$sts."<option value=4>Добавление</option>";
        $sts=$sts."<option value=5>Редактирование</option>";
*/