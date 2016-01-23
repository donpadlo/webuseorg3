<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

$oper= PostDef('oper');
$name= PostDef('name');
$login= PostDef('login');
$params= PostDef('params');
$id= PostDef('id');

$page = GetDef('page');
  if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 

if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM users where active=0";
        //echo "!$sql!";
	$result = $sqlcn->ExecuteSQL($sql)  or die("Не могу выбрать количество записей!".mysqli_error($sqlcn->idsqlconnection));
	$row = mysqli_fetch_array($result);
	$count = $row['count'];
        //echo "$count!!";
        $responce=new stdClass();
	if( $count >0 ) {
            $total_pages = ceil($count/$limit);
            if ($page > $total_pages) $page=$total_pages;
            $start = $limit*$page - $limit;
            $SQL = "SELECT * FROM users where active=0 ORDER BY $sidx $sord LIMIT $start , $limit";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список кабелей!".mysqli_error($sqlcn->idsqlconnection));            
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                    $responce->rows[$i]['id']=$row['id'];
                    $responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['login']);		
                    $i++;
            };
        };
	echo json_encode($responce);
};
if ($oper=="edit"){
  $sql="update lib_cable_spliter set name='$name',exitcount='$exitcount' where id='$id'";  
  $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу обновить сплитер!".mysqli_error($sqlcn->idsqlconnection));            
};
