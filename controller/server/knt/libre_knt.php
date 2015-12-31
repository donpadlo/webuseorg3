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
if ($page==0){$page=1;};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];}   else {$limit="";};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx']; }   else {$sidx="";};
if (isset($_GET["sord"]))       {$sord = $_GET['sord']; }   else {$sord="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper="";};
if (isset($_POST["id"]))        {$id = $_POST['id'];}       else {$id="";};
if (isset($_POST["name"]))      {$name= $_POST['name'];}    else {$name="";};
if (isset($_POST["INN"]))       {$INN= $_POST['INN'];}      else {$INN="";};
if (isset($_POST["KPP"]))       {$KPP= $_POST['KPP'];}      else {$KPP="";};
if (isset($_POST["bayer"]))     {$bayer= $_POST['bayer'];}  else {$bayer="";};
if (isset($_POST["supplier"]))  {$supplier= $_POST['supplier'];} else {$supplier="";};
if (isset($_POST["ERPCode"]))   {$ERPCode= $_POST['ERPCode'];}   else {$ERPCode="";};
if (isset($_POST["dog"]))       {$dog= $_POST['dog'];}           else {$dog="";};
if (isset($_POST["comment"]))   {$comment= $_POST['comment'];}   else {$comment="";};

if (isset($_GET["filters"]))       {$filters = $_GET['filters'];} else {$filters ="";};

$flt=json_decode($filters,true);	
	$cnt=count($flt['rules']);
	$where="";
	for ($i=0;$i<$cnt;$i++)
	{
		$field=$flt['rules'][$i]['field'];
		$data=$flt['rules'][$i]['data'];
		if ($data!='-1'){
                    $where=$where."($field LIKE '%$data%')";                    
                    } else {$where=$where."($field LIKE '%%')";};
		if ($i<($cnt-1)){$where=$where." AND ";};
	};
	if ($where!=""){$where="WHERE ".$where;};

if ($oper=='')
{
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM knt");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT id,name,INN,KPP,bayer,supplier,dog,ERPCode,comment,active FROM knt ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список контрагентов!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];
            if ($row['bayer']==0){$row['bayer']='No';} else {$row['bayer']='Yes';};
            if ($row['supplier']==0){$row['supplier']='No';} else {$row['supplier']='Yes';};
            if ($row['dog']==0){$row['dog']='No';} else {$row['dog']='Yes';};
	    if ($row['active']=="1")                
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/accept.png>",$row['id'],$row['name'],$row['INN'],$row['KPP'],$row['bayer'],$row['supplier'],$row['dog'],$row['ERPCode'],$row['comment']);} else
		{$responce->rows[$i]['cell']=array("<img src=controller/client/themes/".$cfg->theme."/ico/cancel.png>",$row['id'],$row['name'],$row['INN'],$row['KPP'],$row['bayer'],$row['supplier'],$row['dog'],$row['ERPCode'],$row['comment']);};
	    $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
        if ($bayer=='Yes') {$bayer=1;} else {$bayer=0;};
        if ($supplier=='Yes') {$supplier=1;} else {$supplier=0;};
        if ($dog=='Yes') {$dog=1;} else {$dog=0;};
	$SQL = "UPDATE knt SET name='$name',comment='$comment',INN='$INN',KPP='$KPP',bayer='$bayer',supplier='$supplier',dog='$dog',ERPCode='$ERPCode' WHERE id='$id'";
        echo "!$SQL!";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по контрагенту!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
        if ($bayer=='Yes') {$bayer=1;} else {$bayer=0;};
        if ($supplier=='Yes') {$supplier=1;} else {$supplier=0;};
        if ($dog=='Yes') {$dog=1;} else {$dog=0;};    
	$SQL = "INSERT INTO knt (id,name,INN,KPP,bayer,supplier,dog,ERPCode,comment,active) VALUES (null,'$name','$INN','$KPP','$bayer','$supplier','$dog','$ERPCode','$comment',1)";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить контрагента!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "UPDATE knt SET active=not active WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по контрагенту!".mysqli_error($sqlcn->idsqlconnection));
};

?>