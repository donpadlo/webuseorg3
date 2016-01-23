<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once(WUO_ROOT.'/class/cconfig.php'); // Класс настроек

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
$filters = GetDef('filters');

	$flt = json_decode($filters, true);
	$cnt = count($flt['rules']);
	$where = '';
	for ($i = 0; $i < $cnt; $i++) {
		$field = $flt['rules'][$i]['field'];
		$data = $flt['rules'][$i]['data'];
		$where = $where."($field LIKE '%$data%')";
		if ($i < ($cnt - 1)) {
			$where = $where.' AND ';
		}
	}
	if ($where != '') {
		$where = 'WHERE '.$where;
	}

if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM users $where";
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
            $SQL = "SELECT * FROM users $where ORDER BY $sidx $sord LIMIT $start , $limit";
//            echo "$SQL";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));            
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            $zu=new Tusers();
            $cc=new Tcconfig();
            while($row = mysqli_fetch_array($result)) {
                    $responce->rows[$i]['id']=$row['id'];
                    $zu->GetById($row['id']);
                    $params=$cc->GetByParam("user-chat-sites-".$row['id']);
                    $responce->rows[$i]['cell']=array($row['id'],$zu->fio,$row['login'],$params);		
                    $i++;
            };
            unset($zu);
            unset($cc);
        };
	echo json_encode($responce);
};
if ($oper=="edit"){
    $cc=new Tcconfig();
    $cc->SetByParam("user-chat-sites-".$id,$params);
    unset($cc);    
};
