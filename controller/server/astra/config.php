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
if ($page==0){$page=1;};
$limit = GetDef('rows');
$sidx = GetDef('sidx'); 
$sord = GetDef('sord'); 
$oper= PostDef('oper');
$id = PostDef('id');
$sname=PostDef('sname');
$host=PostDef('host');
$path=PostDef('path');
$comment=PostDef('comment');
$ftplogin=PostDef('ftplogin');
$ftppass=PostDef('ftppass');
$monurl=PostDef('monurl');

$sql="CREATE TABLE IF NOT EXISTS `astra_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `path` varchar(100) NOT NULL,
  `ftplogin` varchar(50) NOT NULL,
  `ftppass` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
)";
$result = $sqlcn->ExecuteSQL($sql);
$sql="CREATE TABLE IF NOT EXISTS `astra_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  `astra_id` int(11) NOT NULL COMMENT 'Сервер астры',
  `tbody` text NOT NULL COMMENT 'Текст сообщения',
  `pic_file` varchar(100) NOT NULL COMMENT 'Картинка',
  `muz_file` varchar(100) NOT NULL COMMENT 'Звуковое сопровождение',
  `tframe` int(11) NOT NULL COMMENT 'Время показа кадра',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
$result = $sqlcn->ExecuteSQL($sql);

$result = $sqlcn->ExecuteSQL($sql);
$sql="ALTER TABLE `astra_servers` ADD `monurl` VARCHAR( 255 ) NOT NULL ;";
$result = $sqlcn->ExecuteSQL($sql);


if ($oper==''){
	if(!$sidx) $sidx =1;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM astra_servers");
	$row = mysqli_fetch_array($result);
	$count = $row['count'];

	if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	$SQL = "SELECT monurl,ftplogin,ftppass,id,name,INET_NTOA(ip) as ip,comment,path FROM astra_servers ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список серверов astra!".mysqli_error($sqlcn->idsqlconnection));
        $responce=new stdClass();
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = mysqli_fetch_array($result)) {
                $responce->rows[$i]['id']=$row['id'];
	    	$responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['ip'],$row['comment'],$row['path'],$row['ftplogin'],$row['ftppass'],$row['monurl']);		
                $i++;
	}
	echo json_encode($responce);
};
if ($oper=='edit')
{
	$SQL = "UPDATE astra_servers SET ftplogin='$ftplogin',ftppass='$ftppass',path='$path',comment='$comment',name='$sname',ip=INET_ATON('$host'),monurl='$monurl' WHERE id='$id'";
        echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить данные по группам!".mysqli_error($sqlcn->idsqlconnection));
};
if ($oper=='add')
{
	$SQL = "INSERT INTO astra_servers (ftplogin,ftppass,id,name,ip,comment,path,monurl) VALUES ('$ftplogin','$ftppass',null,'$sname',INET_ATON('$host'),'$comment','$path','$monurl')";        
        echo "!$SQL!";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу добавить astra!".mysqli_error($sqlcn->idsqlconnection));

};
if ($oper=='del')
{
	$SQL = "delete FROM astra_servers WHERE id='$id'";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу удалить astra!".mysqli_error($sqlcn->idsqlconnection));
};

?>