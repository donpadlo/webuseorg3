<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
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

$page = GetDef('page');
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 
$oper= PostDef('oper');
$id = PostDef('id');

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
		$data=$flt['rules'][$i]['data'];
                if ($field=='groupnomename'){$field='group_nome.id';};                
                if ($field=='idnome'){$field='equipment.id';};                
                if ($field=='nomename'){$field='nome.name';};                
                if ($field=='orgname'){$field='equipment.orgid';};                
  //              echo "!$data!";
		if ($data!='-1'){
                    if (($field=='group_nome.id') or ($field=='equipment.orgid')) {$where=$where."($field = '$data')";} 
                     else {$where=$where."($field LIKE '%$data%')";};                    
                    } else {$where=$where."($field LIKE '%%')";};
		if ($i<($cnt-1)){$where=$where." AND ";};
	};
	if ($where!=""){$where="WHERE ".$where;};	
//echo "#$where#";
/////////////////////////////
 


if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count,equipment.repair,org.name as orgname,equipment.id as idnome,nome.groupid as groupid,nome.name as nomename,users_profile.fio as fio,places.name as placename,
group_nome.name as grname
FROM  `equipment` 
inner join org on equipment.orgid=org.id
inner join nome on equipment.nomeid=nome.id
inner join users_profile on equipment.usersid=users_profile.usersid
inner join places on equipment.placesid=places.id
inner join group_nome on group_nome.id=groupid ".$where." ");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT equipment.orgid,equipment.invnum as invnum,equipment.id as id,equipment.repair,org.name as orgname,equipment.id as idnome,nome.groupid as groupid,nome.name as nomename,users_profile.fio as fio,places.name as placename,
group_nome.name as groupnomename,group_nome.id as grid
FROM  `equipment` 
inner join org on equipment.orgid=org.id
inner join nome on equipment.nomeid=nome.id
inner join users_profile on equipment.usersid=users_profile.usersid
inner join places on equipment.placesid=places.id
inner join group_nome on group_nome.id=groupid ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список производителей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];            
            //id	repair	orgname	idnome	groupid	nomename	fio	placename	grname            
            if ($row['repair']==0){$st="Работает";};
            if ($row['repair']==1){$st="В сервисе";};
            if ($row['repair']==2){$st="Есть заявка";};
            if ($row['repair']==3){$st="Списать";};
              $eqid=$row['id'];            
              $sql="SELECT count(id) as cntmonth from repair where dt>DATE_ADD(now(), INTERVAL -31 DAY) and eqid='$eqid'";
              $result2 = $sqlcn->ExecuteSQL($sql);
              while($row2 = mysqli_fetch_array($result2)) {$cnm=$row2['cntmonth'];};
              $sql="SELECT count(id) as cntyear from repair where dt>DATE_ADD(now(), INTERVAL -365 DAY) and eqid='$eqid'";
              $result2 = $sqlcn->ExecuteSQL($sql);
              while($row2 = mysqli_fetch_array($result2)) {$cny=$row2['cntyear'];};              
            $responce->rows[$i]['cell']=array($st,$row['orgname'],$row['placename'],$row['groupnomename'],$row['id'],$row['invnum'],$row['nomename'],$row['fio'],$cnm,$cny);
	    $i++;
	}
	echo json_encode($responce);
};

?>