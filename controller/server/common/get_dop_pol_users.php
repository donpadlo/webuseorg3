<?php

$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx');
$sord = _GET('sord');
$oper = _POST('oper');
$id = _POST('id');
$chosenmanager = _GET('chosenmanager');

//актуализирую поля
$sql="SELECT * FROM dop_pol where userid=0";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список дополнительных полей!" . mysqli_error($sqlcn->idsqlconnection));
while ($row = mysqli_fetch_array($result)) {
    $name_id=$row["name_id"];
    $name=$row["name"];
    $comment=$row["comment"];
    $sql="SELECT count(*) as cnt FROM dop_pol where name_id='$name_id' and userid='$chosenmanager'";
    $result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список дополнительных полей!" . mysqli_error($sqlcn->idsqlconnection));  
    while ($row2 = mysqli_fetch_array($result2)) {
	$cnt=$row2["cnt"];
    };
    if ($cnt==0){
	$sql="INSERT INTO dop_pol (id,name,name_id,comment,userid) VALUES (null,'','$name_id','$comment','$chosenmanager')";
	$result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу добавить список дополнительных полей!" . mysqli_error($sqlcn->idsqlconnection));  
    };
};

$name = _POST('name');
$name_id = _POST('name_id');
$comment = _POST('comment');

if ($oper == '') {
    
    if (!$sidx) $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM dop_pol where userid='$chosenmanager'");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];    
    if ($count > 0) {$total_pages = ceil($count / $limit);} else {$total_pages = 0;}
    if ($page > $total_pages)$page = $total_pages;
    
    $start = $limit * $page - $limit;
    $SQL = "SELECT * FROM dop_pol where userid='$chosenmanager' ORDER BY $sidx $sord LIMIT $start , $limit";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список дополнительных полей!" . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['id'];
        $responce->rows[$i]['cell'] = array(
            $row['id'],
            $row['name'],
            $row['name_id'],
            $row['comment']
        );
        $i ++;
    }
    echo json_encode($responce);
    
    
};
if ($oper == 'edit') {
    $SQL = "UPDATE dop_pol SET name='$name' WHERE id='$id'";
    //echo "$SQL";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные дополнительных полей!" . mysqli_error($sqlcn->idsqlconnection));
};