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

if (isset($_GET["orgid"]))      {$orgid=$_GET["orgid"];}        else {$orgid=1;};
if (isset($_GET["userid"]))     {$userid=$_GET["userid"];}      else {$userid=1;};
if (isset($_GET["addnone"]))    {$addnone=$_GET["addnone"];}    else {$addnone="";};
if (isset($_GET["dopname"]))    {$dopname=$_GET["dopname"];}    else {$dopname="";};
if (isset($_GET["chosen"]))    {$chosen=$_GET["chosen"];}    else {$chosen="false";};

    $SQL = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.orgid='$orgid' AND users.active=1 ORDER BY users.login";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));
    $sts="<select class='chosen-select' name=suserid".$dopname." id=suserid".$dopname.">";
    if ($addnone=='true'){$sts=$sts."<option value='-1' >нет выбора</option>";};
    while($row = mysqli_fetch_array($result)) {
         $sts=$sts."<option value=".$row["id"]." ";
	  if ($userid==$row["id"]){$sts=$sts."selected";};
	 $sts=$sts.">".$row["fio"]." (".$row["login"].')</option>';
	};
    $sts=$sts.'</select>';   
    if ($chosen=="true"){
        $sts=$sts.' '
                . '<script>'
                . ' for (var selector in config) {$(selector).chosen(config[selector]);'
                . '</script>';
    };   
 echo $sts;    


?>