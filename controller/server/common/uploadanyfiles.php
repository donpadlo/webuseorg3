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

$contractid=$_POST['contractid'];
$uploaddir = '../../../files/';

$userfile_name=basename($_FILES['filedata']['name']);
//var_dump($_FILES);
//$sr=$userfile_name;
$orig_file=$_FILES['filedata']['name'];
$len=strlen($userfile_name);
$ext_file=substr($userfile_name,$len-4,$len);
$tmp=GetRandomId(5);
$userfile_name=$tmp.$userfile_name;
$uploadfile = $uploaddir.$userfile_name;

$sr=$_FILES['filedata']['tmp_name'];
$dest=$uploadfile;

$res=@move_uploaded_file($sr,$dest);
//echo "sr=$sr\n,dest=$dest\n,!$res\n";
if ($res!=false){
     //echo "$userfile_name";
     //echo "$geteqid!";
        $rs = array("msg" => "$userfile_name");    
        if ($contractid!=""){
     	$SQL = "INSERT INTO files_contract (id,idcontract,filename,userfreandlyfilename) VALUES (null,'$contractid','$userfile_name','$orig_file')";
	$result =$sqlcn->ExecuteSQL($SQL) or die("Не могу добавитьфайл!".mysqli_error($sqlcn->idsqlconnection));
        };
     } else {        $rs = array("msg" => 'error');    };
echo json_encode($rs);
 ?>

