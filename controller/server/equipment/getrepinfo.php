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

if (isset($_GET["page"]))       {$page = $_GET['page'];} else {$page="";}; // get the requested page
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];} else {$rows="";}; // get how many rows we want to have into the grid
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx'];} else {$sidx="";}; // get index row - i.e. user click to sort
if (isset($_GET["sord"]))       {$sord = $_GET['sord'];} else {$sord="";}; // get the direction
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];} else {$oper="";};
if (isset($_POST["id"]))        {$id = $_POST['id'];} else {$id="";};
if (isset($_GET["eqid"]))       {$eqid = $_GET['eqid'];} else {$eqid="";};
if (isset($_POST["comment"]))   {$comment= $_POST['comment'];} else {$comment="";};
if (isset($_POST["dt"]))        {$dt= $_POST['dt'];} else {$dt="10.10.2014 00:00:00";};
if (isset($_POST["dtend"]))     {$dtend= $_POST['dtend'];} else {$dtend="10.10.2014 00:00:00";};
if (isset($_POST["status"]))    {$status= $_POST['status'];} else {$status="1";};
if (isset($_POST["doc"]))        {$doc=$_POST["doc"];}   else {$doc="";};	


// если не задано ТМЦ по которому показываем перемещения, то тогда просто листаем последние
if ($eqid==""){
    $where="";
} else {
    $where="WHERE repair.eqid='$eqid'";
};
    

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT count(*)  AS count,repair.dt,repair.dtend,repair.kntid,knt.name,repair.cost,repair.comment,repair.status FROM repair INNER JOIN knt on knt.id=repair.kntid ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
        //echo "!$count!";
	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT repair.id,repair.userfrom,repair.userto,repair.doc,repair.dt,repair.dtend,repair.kntid,knt.name,repair.cost,repair.comment,repair.status FROM repair INNER JOIN knt on knt.id=repair.kntid ".$where."
            ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список ремонтов!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row["id"];	    
            $dt=MySQLDateToDate($row['dt']);
            $dtend=MySQLDateToDate($row['dtend']);
            if ($row["status"]=="1"){$st="В сервисе";};
            if ($row["status"]=="0"){$st="Работает";};
            if ($row["status"]=="2"){$st="Есть заявка";};
            if ($row["status"]=="3"){$st="Списать";};
            $zz=new Tusers();
            if ($row['userto']!="-1"){
                $zz->GetById($row['userto']);
                $row['userto']=$zz->fio;
            } else {$row['userto']='не задано';};           
            if ($row['userfrom']!="-1"){
                $zz->GetById($row['userfrom']);
                $row['userfrom']=$zz->fio;
            } else {$row['userfrom']='не задано';};           
            
            $responce->rows[$i]['cell']=array($row["id"],$dt,$dtend,$row["name"],$row["cost"],$row["comment"],$st,$row['userfrom'],$row['userto'],$row['doc']);
	    $i++;
	}
	echo json_encode($responce);
};

if ($oper=='edit')
{
        $dt=DateToMySQLDateTime2($dt." 00:00:00");
        $dtend=DateToMySQLDateTime2($dtend." 00:00:00");
    	$SQL = "UPDATE repair SET comment='$comment',dt='$dt',dtend='$dtend',status='$status',doc='$doc' WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить статус ремонта ТМЦ!".mysqli_error($sqlcn->idsqlconnection));
        ReUpdateRepairEq();        
};

if ($oper=='del')
{
    	$SQL = "DELETE FROM repair WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить запись о ремонте!".mysqli_error($sqlcn->idsqlconnection));
        ReUpdateRepairEq();
};