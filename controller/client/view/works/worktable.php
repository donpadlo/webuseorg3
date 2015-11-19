<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции

function GetEnterTime($tabnumber){
  global $sqlcn;
  //echo "!$dt!<br>";
  $dt=$_GET["dttt"];
  $str_exp = explode(".", $dt);
  if (count($str_exp)==0){$str_exp = explode("/", $dt);};
  
  $dt=$str_exp[2]."-".$str_exp[1]."-".$str_exp[0];
  $result = $sqlcn->ExecuteSQL("SELECT * FROM  exp_log WHERE hozorgan='$tabnumber' and TimeVal>='$dt 00:00:00' and TimeVal<='$dt 23:59:59' and mode=1 order by TimeVal ASC LIMIT 1");   
  $dt='-';
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                                $dt=$myrow['TimeVal'];
                                $str_exp = explode(" ", $dt);
                                $dt=$str_exp[1];                               
                            }
                } else {die('Неверный запрос GetEnterTime: ' . mysqli_error($sqlcn->idsqlconnection));}
  return $dt;
}
function GetExitTime($tabnumber){
  global $sqlcn;
  $dt=$_GET["dttt"];
  //echo "!$dt!<br>";
  $str_exp = explode(".", $dt);
  if (count($str_exp)==0){$str_exp = explode("/", $dt);};  
  $dt=$str_exp[2]."-".$str_exp[1]."-".$str_exp[0];
  $result = $sqlcn->ExecuteSQL("SELECT * FROM  exp_log WHERE hozorgan='$tabnumber' and TimeVal>='$dt 00:00:00' and TimeVal<='$dt 23:59:59'  and mode=2 order by TimeVal DESC LIMIT 1");   
  $dt='-';
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                                $dt=$myrow['TimeVal'];
                                $str_exp = explode(" ", $dt);
                                $dt=$str_exp[1];
                            }
                } else {die('Неверный запрос GetEnterTime: ' . mysqli_error($sqlcn->idsqlconnection));}
  return $dt;
}

$result = $sqlcn->ExecuteSQL("SELECT * FROM users_ori order by fio");                
if ($result!=''){
    $num=0;
    $u= new Tusers;                            
?>
        <table class="table table-striped" width='100%'>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Фото</th>
                    <th>Должность</th>
                    <th>ФИО</th>
                    <th>Табельный</th>
                    <th>Пришел</th>
                    <th>Ушел</th>
                </tr>
            </thead>  
            <tbody>
<?php    
    while ($myrow = mysqli_fetch_array($result)){                            
                             $fio=$myrow["fio"];$tabnumber=$myrow["tabnumber"];$ori_id=$myrow["ori_id"];                            
                             $u->post="";$u->jpegphoto="noimage.jpg";$u->GetByCode($tabnumber);
                             $ent_time=GetEnterTime($ori_id);$exit_time=GetExitTime($ori_id);   
                             if ($ent_time==$exit_time){$exit_time='-';};
                             if (($ent_time!='-') or ($exit_time!='-')){
                                 $num++;   
                                 echo "            
                                        <tr>
                                            <td>$num</td>
                                            <td><img src='photos/$u->jpegphoto' width='100px' height='100px'></td>                          
                                            <td>$fio</td>
                                            <td>$u->post</td>
                                            <td>$tabnumber</td>                                                
                                            <td>$ent_time</td>
                                            <td>$exit_time</td>
                             </tr>";};
   };?>
        </tbody>             
        </table>
<?php                
 };
?>