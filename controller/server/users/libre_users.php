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
if (isset($_GET["filters"]))       {$filters = $_GET['filters']; }   else {$filters="";};
if (isset($_POST["orgid"]))       {$orgid = $_POST['orgid']; }   else {$orgid="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};
if (isset($_POST["id"]))        {$id= $_POST['id'];}        else {$id="";};
if (isset($_POST["login"]))     {$login= $_POST['login'];}  else {$login="";};
if (isset($_POST["pass"]))      {$pass= $_POST['pass'];}    else {$pass="";};
if (isset($_POST["email"]))     {$email= $_POST['email'];}  else {$email="";};
if (isset($_POST["mode"]))      {$mode= $_POST['mode'];}    else {$mode="";};

if ($oper=='')
{	
	$flt=json_decode($filters,true);	
	$cnt=count($flt['rules']);
	$where="";
	for ($i=0;$i<$cnt;$i++)
	{
		$field=$flt['rules'][$i]['field'];
		if ($field=='org.id'){$field='org.name';};
		$data=$flt['rules'][$i]['data'];
		$where=$where."($field LIKE '%$data%')";
		if ($i<($cnt-1)){$where=$where." AND ";};
	};
	if ($where!=""){$where="WHERE ".$where;};
	//echo "!$where!";	
	
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count,org.id AS orgid,users.id,users.orgid,users.login,users.pass,users.email,users.mode,users.active,org.name AS orgname FROM users INNER JOIN org ON users.orgid=org.id ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT org.id AS orgid,users.id,users.orgid,users.login,users.pass,users.email,users.mode,users.active,org.name AS orgname FROM users INNER JOIN org ON users.orgid=org.id ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
	//echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
	    if ($row['mode']=='1'){$mode='Да';} else {$mode='Нет';};
	    if ($row['active']=="1")
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>",$row['id'],$row['orgname'],$row['login'],'скрыто',$row['email'],$mode);} else
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>",$row['id'],$row['orgname'],$row['login'],'скрыто',$row['email'],$mode);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit'){
	if ($mode=='Да') {$mode=1;} else {$mode=0;};
	if ($pass!='скрыто'){
	$SQL = "UPDATE users SET mode='$mode',login='$login',pass='$pass',email='$email' WHERE id='$id'";
	} else {
	$SQL = "UPDATE users SET mode='$mode',login='$login',email='$email' WHERE id='$id'";	
	};
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по пользователю!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add'){
	$SQL = "INSERT INTO knt (id,name,comment,active) VALUES (null,'$name','$comment',1)";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить пользователя!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del'){
	$SQL = "UPDATE users SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по пользователю!".mysqli_error($sqlcn->idsqlconnection));
};
?>