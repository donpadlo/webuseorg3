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


if (isset($_GET["page"]))       {$page = $_GET['page'];};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx'];};
if (isset($_GET["sord"]))       {$sord = $_GET['sord'];};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];} else {$oper="";};
if (isset($_GET["sorgider"]))   {$sorgider = $_GET['sorgider'];} else {$cfg->defaultorgid;};
if (isset($_POST["id"]))        {$id = $_POST['id'];};
if (isset($_POST["ip"]))        {$ip = $_POST['ip'];};
if (isset($_POST["name"]))      {$name= $_POST['name'];};
if (isset($_POST["comment"]))   {$comment= $_POST['comment'];};
if (isset($_POST["buhname"]))   {$buhname=$_POST['buhname'];};
if (isset($_POST["sernum"]))    {$sernum=$_POST['sernum'];};
if (isset($_POST["invnum"]))    {$invnum=$_POST['invnum'];};
if (isset($_POST["shtrihkod"])) {$shtrihkod=$_POST['shtrihkod'];};
if (isset($_POST["cost"]))      {$cost=$_POST['cost'];};
if (isset($_POST["currentcost"])) {$currentcost=$_POST['currentcost'];};
if (isset($_POST["os"]))        {$os=$_POST['os'];};
if (isset($_POST["tmcgo"]))        {$tmcgo=$_POST['tmcgo'];};
if (isset($_POST["mode"]))      {$mode=$_POST['mode'];};
if (isset($_POST["mapyet"]))    {$mapyet=$_POST['mapyet'];};
if (isset($_POST["eqmapyet"]))  {$mapyet=$_POST['eqmapyet'];} else {$mapyet="";};

$orgid = $cfg->defaultorgid;

/////////////////////////////
// вычисляем фильтр
/////////////////////////////
$filters="";
if (isset($_GET["filters"])) {$filters = $_GET['filters'];}; // получаем наложенные поисковые фильтры
$flt=json_decode($filters,true);	
	$cnt=count($flt['rules']);
	$where="";
	for ($i=0;$i<$cnt;$i++)
	{
		$field=$flt['rules'][$i]['field'];
		if ($field=='org.name'){$field='org.id';};
		$data=$flt['rules'][$i]['data'];
		if ($data!='-1'){
                    if (($field=='placesid') or ($field=='getvendorandgroup.grnomeid')) {$where=$where."($field = '$data')";} else {$where=$where."($field LIKE '%$data%')";};
                    
                    } else {$where=$where."($field LIKE '%%')";};
		if ($i<($cnt-1)){$where=$where." AND ";};
	};
	if ($where!=""){$where="WHERE ".$where;};	
        if ($where=='') {$where="WHERE equipment.orgid='$sorgider'";} else {$where=$where." AND equipment.orgid='$sorgider'";}
/////////////////////////////
 
 
if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT count(*) as count,equipment.dtendgar, knt.name,getvendorandgroup.grnomeid,equipment.id AS eqid,equipment.orgid AS eqorgid, org.name AS orgname, getvendorandgroup.vendorname AS vname, 
            getvendorandgroup.groupname AS grnome, places.name AS placesname, users.login AS userslogin, 
            getvendorandgroup.nomename AS nomename, buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os, equipment.mode AS eqmode,equipment.mapyet AS eqmapyet,equipment.comment AS eqcomment, equipment.active AS eqactive,equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname, group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users ON users.id = equipment.usersid
	LEFT JOIN knt ON knt.id = equipment.kntid ".$where." ");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;
        
        $responce=new stdClass();
	$start = $limit*$page - $limit;
        if ($start<0){
            
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            echo json_encode($responce);
          die();  
        };
	$SQL = "SELECT equipment.dtendgar,tmcgo, knt.name as kntname,getvendorandgroup.grnomeid,equipment.id AS eqid,equipment.orgid AS eqorgid, org.name AS orgname, getvendorandgroup.vendorname AS vname, 
            getvendorandgroup.groupname AS grnome, places.name AS placesname, users.login AS userslogin, 
            getvendorandgroup.nomename AS nomename, buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os, equipment.mode AS eqmode,equipment.mapyet AS eqmapyet,equipment.comment AS eqcomment, equipment.active AS eqactive,equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname, group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users ON users.id = equipment.usersid
	LEFT JOIN knt ON knt.id = equipment.kntid ".$where." 
	ORDER BY $sidx $sord LIMIT $start , $limit";
	//echo "!$SQL!";die();
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось выбрать список оргтехники!".mysqli_error($sqlcn->idsqlconnection)." sql=".$SQL);        
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['eqid'];
	    if ($row['eqactive']=="1") {$active="<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>";} else
				   {$active="<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>";};
	    if ($row['eqrepair']=="1") {$active=$active."<img src=controller/client/themes/".$cfg->theme."/ico/error.png>";};
                                   
		if ($row['os']==0){$os='No';} else {$os='Yes';};
		if ($row['eqmode']==0){$eqmode='No';} else {$eqmode='Yes';};
                if ($row['eqmapyet']==0){$eqmapyet='No';} else {$eqmapyet='Yes';};
		$dtpost=MySQLDateTimeToDateTime($row['datepost']);
                $dtendgar=MySQLDateToDate($row['dtendgar']);
                if ($row['tmcgo']==0){$row['tmcgo']="No";} else {$row['tmcgo']="Yes";};
		$responce->rows[$i]['cell']=array(
		$active,$row['eqid'],$row['placesname'],$row['nomename'],
		$row['grnome'],$row['tmcgo'],$row['vname'],$row['buhname'],$row['sernum'],$row['invnum'],
		$row['shtrihkod'],$row['orgname'],$row['userslogin'],$dtpost,$row['cost'],$row['currentcost'],$os,$eqmode,$row['eqmapyet'],$row['eqcomment'],$row['eqrepair'],
                $dtendgar,$row['kntname']                    
		);
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	if ($os=='Yes') {$os=1;} else {$os=0;};
        if ($tmcgo=='Yes') {$tmcgo=1;} else {$tmcgo=0;};
	if ($mode=='Yes') {$mode=1;} else {$mode=0;};
        if ($mapyet=='Yes') {$mapyet=1;} else {$mapyet=0;};
        $buhname = mysqli_real_escape_string($sqlcn->idsqlconnection, $buhname);
	$SQL = "UPDATE equipment SET buhname='$buhname',sernum='$sernum',invnum='$invnum',shtrihkod='$shtrihkod',cost='$cost',currentcost='$currentcost',os='$os',mode='$mode',mapyet='$mapyet',comment='$comment',tmcgo='$tmcgo' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог обновить оргтехнику!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO places (id,orgid,name,comment,active) VALUES (null,'$orgid','$name','$comment',1)";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог вставить оргтехнику!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE equipment SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог пометить на удаление оргтехнику!".mysqli_error($sqlcn->idsqlconnection));
};

?>