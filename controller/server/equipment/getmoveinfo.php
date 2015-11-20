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

// если не задано ТМЦ по которому показываем перемещения, то тогда просто листаем последние
if ($eqid==""){
    $where="";
} else {
    $where="WHERE move.eqid='$eqid'";
};
    

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count,mv.id, mv.eqid, mv.dt, mv.orgname1, org.name AS orgname2, mv.place1, places.name AS place2, mv.user1, users_profile.fio AS user2,move.comment as comment
            FROM move
            INNER JOIN (
            SELECT move.id, move.eqid, move.dt AS dt, org.name AS orgname1, places.name AS place1, users_profile.fio AS user1
            FROM move
            INNER JOIN org ON org.id = orgidfrom
            INNER JOIN places ON places.id = placesidfrom
            INNER JOIN users_profile ON users_profile.usersid = useridfrom
            ) AS mv ON move.id = mv.id
            INNER JOIN org ON org.id = move.orgidto
            INNER JOIN places ON places.id = placesidto
            INNER JOIN users_profile ON users_profile.usersid = useridto ".$where);
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT mv.id, mv.eqid, nome.name,mv.nomeid,mv.dt, mv.orgname1, org.name AS orgname2, mv.place1, places.name AS place2, mv.user1, users_profile.fio AS user2,move.comment as comment
            FROM move
            INNER JOIN (
            SELECT move.id, move.eqid, equipment.nomeid,move.dt AS dt, org.name AS orgname1, places.name AS place1, users_profile.fio AS user1
            FROM move
            INNER JOIN org ON org.id = orgidfrom
            INNER JOIN places ON places.id = placesidfrom
            INNER JOIN users_profile ON users_profile.usersid = useridfrom
            INNER JOIN equipment ON equipment.id = eqid
            ) AS mv ON move.id = mv.id
            INNER JOIN org ON org.id = move.orgidto
            INNER JOIN places ON places.id = placesidto
            INNER JOIN users_profile ON users_profile.usersid = useridto
            INNER JOIN nome ON nome.id = mv.nomeid ".$where."
            ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список перемещений!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row["id"];	    
            $responce->rows[$i]['cell']=array($row["id"],$row["dt"],$row["orgname1"],$row["place1"],$row["user1"],$row["orgname2"],$row["place2"],$row["user2"],$row["name"],$row["comment"]);
	    $i++;
	}
	echo json_encode($responce);
};

if ($oper=='edit')
{
    	$SQL = "UPDATE move SET comment='$comment' WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить комментарий!".mysqli_error($sqlcn->idsqlconnection));
};

if ($oper=='del')
{
    	$SQL = "DELETE FROM move WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить запись о перемещении!".mysqli_error($sqlcn->idsqlconnection));
};