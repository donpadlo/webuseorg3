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
$nomename= PostDef('nomename');
$filters=GetDef("filters");

if ($oper=='')
{
	
	$flt=json_decode($filters,true);	
	$cnt=count($flt['rules']);
	$where="";
	for ($i=0;$i<$cnt;$i++)
	{
		$field=$flt['rules'][$i]['field'];
		if ($field=='nomeid'){$field='nome.id';};
		$data=$flt['rules'][$i]['data'];
		$where=$where."($field LIKE '%$data%')";
		if ($i<($cnt-1)){$where=$where." AND ";};
	};
	if ($where!=""){$where="WHERE ".$where;};
	//echo "!$where!";	
	
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM nome");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT nome.id as nomeid,group_nome.name as groupname,vendor.name as vendorname,nome.name as nomename,nome.active as nomeactive FROM nome INNER JOIN group_nome ON group_nome.id=nome.groupid INNER JOIN vendor ON nome.vendorid=vendor.id ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
	//echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список номенклатуры!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['nomeid'];
	    if ($row['nomeactive']=="1")
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>",$row['nomeid'],$row['groupname'],$row['vendorname'],$row['nomename']);} else
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>",$row['nomeid'],$row['groupname'],$row['vendorname'],$row['nomename']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE nome SET name='$nomename' WHERE id='$id'";	
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по номенклатуре!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO knt (id,name,comment,active) VALUES (null,'$name','$comment',1)";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить пользователя!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE nome SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по номенклатуре!".mysqli_error($sqlcn->idsqlconnection));
};
?>