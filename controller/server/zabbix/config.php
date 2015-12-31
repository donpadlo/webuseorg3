<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

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
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 
$oper= PostDef('oper');
$id = PostDef('id');
$sname=PostDef('sname');
$host=PostDef('host');
$basename=PostDef('basename');
$username=PostDef('username');
$pass=PostDef('pass');

if ($oper==''){
    //создадим структуру модуля..
        $sql="CREATE TABLE IF NOT EXISTS `zabbix_mod_cfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sname` varchar(50) NOT NULL,
  `host` varchar(50) NOT NULL,
  `basename` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
        $result = $sqlcn->ExecuteSQL($sql);
        
        
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM zabbix_mod_cfg");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM zabbix_mod_cfg ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['sname'],$row['host'],$row['basename'],$row['username'],$row['pass']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit'){
	$SQL = "UPDATE zabbix_mod_cfg SET sname='$sname',host='$host',basename='$basename',username='$username',pass='$pass'";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группам!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add'){
	$SQL = "INSERT INTO zabbix_mod_cfg (id,sname,host,basename,username,pass) VALUES (null,'$sname','$host','$basename','$username','$pass')";        
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить группу!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del'){
	$SQL = "delete FROM zabbix_mod_cfg WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить группу!".mysqli_error($sqlcn->idsqlconnection));
};

?>