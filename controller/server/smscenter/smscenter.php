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

//создаю структуру для этго модуля..
$sql="CREATE TABLE IF NOT EXISTS `sms_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `telegram` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8";
$result = $sqlcn->ExecuteSQL($sql);

$sql="ALTER TABLE `sms_users` ADD PRIMARY KEY (`id`);";
$result = $sqlcn->ExecuteSQL($sql);
$sql="ALTER TABLE `sms_users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;";
$result = $sqlcn->ExecuteSQL($sql);

$sql = 'ALTER TABLE `sms_users` ADD `telegram` VARCHAR(20) NOT NULL AFTER `phone`;';
$result = $sqlcn->ExecuteSQL($sql);

$sql='CREATE TABLE IF NOT EXISTS `sms_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8';
$result = $sqlcn->ExecuteSQL($sql);
$sql='ALTER TABLE `sms_groups`
  ADD PRIMARY KEY (`id`);';
$result = $sqlcn->ExecuteSQL($sql);
$sql='ALTER TABLE `sms_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;';
$result = $sqlcn->ExecuteSQL($sql);
$sql="CREATE TABLE IF NOT EXISTS `sms_group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$result = $sqlcn->ExecuteSQL($sql);
////////////////////////////

$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx'); 
$sord = _GET('sord'); 
$oper= _POST('oper');
$id = _POST('id');
$agname=_POST('agname');
$smslogin=_POST('smslogin');
$smspass=_POST('smspass');
$fileagent=_POST('fileagent');
$smsdiff=_POST('smsdiff');
$sel=_POST('sel');
$sender=_POST('sender');

if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM sms_center_config");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT * FROM sms_center_config ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список групп!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['agname'],$row['smslogin'],$row['smspass'],$row['sender'],$row['fileagent'],$row['smsdiff'],$row['sel']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
        if ($sel=='Yes'){
          $sql="update  sms_center_config SET sel='No'";
          $result = $sqlcn->ExecuteSQL($sql) or die("Не могу обновить данные по агенту (1)!".mysqli_error($sqlcn->idsqlconnection));
        };
	$SQL = "UPDATE sms_center_config SET sender='$sender',agname='$agname',smslogin='$smslogin',smspass='$smspass',fileagent='$fileagent',smsdiff='$smsdiff',sel='$sel' WHERE id='$id'";
        //echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по агенту!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
        if ($sel=='Yes'){
          $sql="update  sms_center_config SET sel='No'";
          $result = $sqlcn->ExecuteSQL($sql) or die("Не могу обновить данные по агенту (2)!".mysqli_error($sqlcn->idsqlconnection));
        };    
	$SQL = "INSERT INTO sms_center_config (id,agname,smslogin,smspass,fileagent,smsdiff,sel,sender) VALUES (null,'$agname','$smslogin','$smspass','$fileagent','$smsdiff','$sel','$sender')";        
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить агента!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "delete FROM sms_center_config WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить агента!".mysqli_error($sqlcn->idsqlconnection));
};

?>