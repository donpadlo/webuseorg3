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
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};

if (isset($_GET["idknt"]))      {$idknt= $_GET['idknt'];} else {$idknt="";};
if (isset($_POST["id"]))        {$id = $_POST['id'];} else {$id="";};
if (isset($_POST["name"]))      {$name= $_POST['name'];} else {$name="";};
if (isset($_POST["num"]))       {$num= $_POST['num'];} else {$num="";};
if (isset($_POST["datestart"]))      {$datestart= $_POST['datestart'];} else {$datestart="";};
if (isset($_POST["dateend"]))        {$dateend= $_POST['dateend'];} else {$dateend="";};
if (isset($_POST["work"]))           {$work= $_POST['work'];} else {$work="";};
if (isset($_POST["comment"]))        {$comment= $_POST['comment'];} else {$comment="";};

$where=" WHERE kntid='$idknt'";

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM contract".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM contract ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список договоров!".mysqli_error($sqlcn->idsqlconnection));
        
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
            if ($row['work']==0){$row['work']='No';} else {$row['work']='Yes';};
            $dateend=$row['dateend'].' 00:00:00';
            $datestart=$row['datestart'].' 00:00:00';
	    if ($row['active']=="1")                
		{$responce->rows[$i]['cell']=array("<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i>",$row['id'],$row['num'],$row['name'],MySQLDateTimeToDateTime($datestart),MySQLDateTimeToDateTime($dateend),$row['work'],$row['comment']);} else
		{$responce->rows[$i]['cell']=array("<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>",$row['id'],$row['num'],$row['name'],MySQLDateTimeToDateTime($datestart),MySQLDateTimeToDateTime($dateend),$row['work'],$row['comment']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
        if ($work=='Yes'){$work='1';} else {$work='0';};
        $datestart=DateToMySQLDateTime2($datestart);
        $dateend=DateToMySQLDateTime2($dateend);
    	$SQL = "UPDATE contract SET num='$num',name='$name',comment='$comment',datestart='$datestart',dateend='$dateend',work='$work' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по договору!".mysqli_error($sqlcn->idsqlconnection));
        echo "!$SQL!";

};

if ($oper=='add')
{
        if ($work=='Yes'){$work='1';} else {$work='0';};
        $datestart=DateToMySQLDateTime2($datestart);
        $dateend=DateToMySQLDateTime2($dateend);
    	$SQL = "INSERT INTO contract (id,kntid,num,name,comment,datestart,dateend,work,active) VALUES (null,'$idknt','$num','$name','$comment','$datestart','$dateend','$work',1)";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить данные по договору!".mysqli_error($sqlcn->idsqlconnection));
        

};
if ($oper=='del')
{
    	$SQL = "UPDATE contract SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог пометить на удаление договор!".mysqli_error($sqlcn->idsqlconnection));

};


?>
