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
include_once("../../../class/cconfig.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции

$zb=new Tsql();
$par=new Tcconfig();
$errarr=array();
$cnt=0;
//проходим все сервера Zabbix
$sql="select * from zabbix_mod_cfg";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список серверов zabbix!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {    
 $idz=$row["id"];  
 $sname=$row["sname"];
 $host=$row["host"];
 $username=$row["username"];
 $pass=$row["pass"];
 $basename=$row["basename"];   
 $zb->connect($host,$username,$pass,$basename);
  //получаем информацию с dashboard
  $sql="SELECT g.groupid,h.host,t.triggerid,g.name group_name,t.priority priority 
  FROM   hosts h,items i,hosts_groups hg,groups g,functions f, triggers t  
  WHERE  h.status = 0 AND h.hostid = i.hostid AND hg.groupid = g.groupid AND hg.hostid = h.hostid AND i.status = 0 AND i.itemid = f.itemid AND t.triggerid = f.triggerid AND t.VALUE = 1 AND t.status = 0
  GROUP  BY t.triggerid,g.name, t.priority";
  $result2 = $zb->ExecuteSQL($sql) or die("Не могу выбрать список dashboard zabbix!".mysqli_error($zb->idsqlconnection));
  while($row2 = mysqli_fetch_array($result2)) {
      $gid=$row2["groupid"];
      $hosterr=$row2["host"];
      $triggerid=$row2["triggerid"];
      $group_name=$row2["group_name"];
      $priority=$row2["priority"];
       $sql="select * from triggers where triggerid=$triggerid";
       //echo "$sql\n";
        $result3 = $zb->ExecuteSQL($sql) or die("Не могу выбрать подробности по триггеру!".mysqli_error($zb->idsqlconnection));
        while($row3 = mysqli_fetch_array($result3)) {
            $description=$row3["description"];
            $lastchange=$row3["lastchange"];
            $comments=$row3["comments"];
            //echo "!!!!";
        };
        //проверяем подписку..
            $cuid=$user->id."_".$idz."_".$gid; //uid текущего события
            if ($par->GetByParam($cuid)!=""){            
                $errarr[$cnt]["sname"]=$sname;         
                $errarr[$cnt]["hosterr"]=$hosterr;         
                $description=str_replace("{HOST.NAME}", $hosterr, $description);
                $errarr[$cnt]["triggerid"]=$triggerid;         
                $errarr[$cnt]["group_name"]=$group_name; 
                $errarr[$cnt]["prinum"]=$priority;    
                switch ($priority) {
                    case 0:$priority="Нет";break;
                    case 1:$priority="Информация";break;
                    case 2:$priority="Предупреждение";break;
                    case 3:$priority="Опасность";break;
                    case 4:$priority="Высокий";break;
                    case 5:$priority="ЧП";break;       
                }
                $errarr[$cnt]["priority"]=$priority;         
                $errarr[$cnt]["description"]=$description;         
                $errarr[$cnt]["comment"]=$comments;         
                $errarr[$cnt]["lastchange"]=round((microtime(true)-$lastchange)/60,0);         
                $cnt++;            
            };
  }; 
};

echo json_encode($errarr);