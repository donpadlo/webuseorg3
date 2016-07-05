<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if (isset($_GET["page"]))       {$page = $_GET['page'];}    else {$page ="";};
if ($page==0){$page=1;};
if (isset($_GET["rows"]))       {$limit = $_GET['rows'];}   else {$limit ="";};
if (isset($_GET["sidx"]))       {$sidx = $_GET['sidx'];}    else {$sidx ="";};
if (isset($_GET["sord"]))       {$sord = $_GET['sord'];}    else {$sord ="";};
if (isset($_POST["oper"]))      {$oper= $_POST['oper'];}    else {$oper ="";};
if (isset($_POST["id"]))        {$id = $_POST['id'];}       else {$id ="";};
if (isset($_POST["name"]))      {$name= $_POST['name'];}    else {$name ="";};
if (isset($_POST["name_id"]))      {$name_id= $_POST['name_id'];}    else {$name_id ="";};
if (isset($_POST["comment"]))      {$comment= $_POST['comment'];}    else {$comment ="";};

if ($oper=='') {
	//создаем на всякий случай таблицу
	$sql="CREATE TABLE `dop_pol` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `name_id` VARCHAR(100) NOT NULL , `comment` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
	$result = $sqlcn->ExecuteSQL($sql);    
	
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM dop_pol");
	$row = mysqli_fetch_array($result);	
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;
	
	$start = $limit*$page - $limit; 
	$SQL = "SELECT * FROM dop_pol ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список дополнительных полей!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
	    $responce->rows[$i]['id']=$row['id'];	               
	    $responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['name_id'],$row['comment']);
	    $i++;
	}        
	echo json_encode($responce);
};
if ($oper=='edit'){
	$SQL = "UPDATE dop_pol SET name='$name',name_id='$name_id',comment='$comment' WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL($SQL ) or die("Не могу обновить данные дополнительных полей!".mysqli_error($sqlcn->idsqlconnection));	
};
if ($oper=='add'){
	$SQL = "INSERT INTO dop_pol (id,name,name_id,comment) VALUES (null,'$name','$name_id','$comment')";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить  дополнительных полей!".mysqli_error($sqlcn->idsqlconnection));	
};
if ($oper=='del'){
	$SQL = "delete from dop_pol WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL($SQL ) or die("Не могу удалить  дополнительных полей!".mysqli_error($sqlcn->idsqlconnection));	
};

?>