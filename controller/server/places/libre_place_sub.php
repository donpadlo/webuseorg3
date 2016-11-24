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
include_once("../../../inc/login.php");		// загружаем функции


if (isset($_GET["page"]))       {$page = $_GET['page'];}    else {$page="";};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];}   else {$limit="";};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx']; }   else {$sidx="";};
if (isset($_GET["sord"]))       {$sord = $_GET['sord']; }   else {$sord="";};
if (isset($_GET["orgid"]))       {$orgid = $_GET['orgid']; }   else {$orgid="";};
if (isset($_GET["placesid"]))       {$placesid = $_GET['placesid']; }   else {$placesid="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};

if (isset($_POST["id"]))        {$id = $_POST['id'];}       else {$id="";};
if (isset($_POST["name"]))      {$name= $_POST['name'];}    else {$name="";};
if (isset($_POST["comment"]))      {$comment= $_POST['comment'];}    else {$comment="";};


//echo "!$placesid!";
if ($oper==''){
    if (($user->mode==1) or ($user->TestRoles('1,3'))){    
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM places_users WHERE placesid='$placesid'");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	/*$SQL = "SELECT places_users.id AS plid,placesid,userid,users.login as name FROM places_users INNER JOIN users ON users.id=userid WHERE placesid='$placesid' ORDER BY $sidx $sord LIMIT $start , $limit";*/
	$SQL = "SELECT places_users.id AS plid, placesid, userid, users_profile.fio as name FROM places_users INNER JOIN users_profile ON users_profile.usersid=userid WHERE placesid='$placesid' ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
        //die();
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список помещений/пользователей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['plid'];
	    $responce->rows[$i]['cell']=array($row['plid'],$row['name']);		
	    $i++;
	}
	echo json_encode($responce);
    };
};
if ($oper=='edit'){
    if (($user->mode==1) or ($user->TestRoles('1,5'))){    
	$SQL = "UPDATE places_users SET userid='$name' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по помещениям/пользователям!".mysqli_error($sqlcn->idsqlconnection));
    };
};
if ($oper=='add'){
    if (($user->mode==1) or ($user->TestRoles('1,4'))){        
        if (($placesid=="") or ($name=="")) {die();};
	$SQL = "INSERT INTO places_users (id,placesid,userid) VALUES (null,'$placesid','$name')";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить помещение/пользователя!".mysqli_error($sqlcn->idsqlconnection));
    };

};
if ($oper=='del'){
    if (($user->mode==1) or ($user->TestRoles('1,6'))){    
	$SQL = "DELETE FROM places_users WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить помещение/пользователя!".mysqli_error($sqlcn->idsqlconnection));
    };
};

?>