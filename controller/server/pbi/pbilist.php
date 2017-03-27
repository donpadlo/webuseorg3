<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

$oper= PostDef('oper');

$groupname= PostDef('groupname');
$name= PostDef('name');
$comment= PostDef('comment');
$login= PostDef('login');
$pass= PostDef('pass');
$ip= PostDef('ip');
$forusers= PostDef('forusers');
$id= PostDef('id');

$page = GetDef('page');
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 


if ($oper==''){
	if(!$sidx) $sidx =1;
        $sql="SELECT COUNT(*) AS count FROM pbi";
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
            $SQL = "SELECT * FROM pbi ORDER BY $sidx $sord LIMIT $start , $limit";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список pbi!".mysqli_error($sqlcn->idsqlconnection));            
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            while($row = mysqli_fetch_array($result)) {
		$flu=0;
		if ($user->mode==1){$flu=1;};
		//проверяю, а можно ли показывать сиё абоненту
		    $uarr=  explode(",", $row['forusers']);
		    foreach ($uarr as $uu) {
			if ($user->id==$uu){$flu=1;};
		    };
		//
		    if ($flu==1){
			$responce->rows[$i]['id']=$row['id'];
			$responce->rows[$i]['cell']=array($row['id'],$row['groupname'],$row['name'],$row['comment'],$row['login'],$row['pass'],$row['ip'],$row['forusers']);		
			$i++;
		    };
            };
        };
	echo json_encode($responce);
};
if ($oper=="add"){
    if ($user->mode==1){
	$sql="insert into pbi (id,groupname,name,comment,login,pass,ip,forusers) VALUES (null,'$groupname','$name','$comment','$login','$pass','$ip','$forusers')";    
	$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу добавить pbi!".mysqli_error($sqlcn->idsqlconnection));            
    };
};
if ($oper=="edit"){
    if ($user->mode==1){    
      $sql="update pbi set groupname='$groupname',name='$name',comment='$comment',login='$login',pass='$pass',ip='$ip',forusers='$forusers' where id='$id'";  
      $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу обновить pbi!".mysqli_error($sqlcn->idsqlconnection));            
    };
};
if ($oper=="del"){
    if ($user->mode==1){    
      $sql="delete from pbi where id='$id'";  
      $result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу удалить pbi!".mysqli_error($sqlcn->idsqlconnection));            
    };
};
