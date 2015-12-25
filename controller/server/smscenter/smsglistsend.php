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
include_once("../../../class/mod.php");                  // класс работы с модулями
include_once("../../../class/logs.php");                  // класс работы с модулями

// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции
include_once("../../../inc/lbfunc.php");		// загружаем функции

//include_once("../../../class/smstower.class.php");
include_once("../../../autorun/sms.php");		// запускаем сторонние скрипты
//$sms=new SMSTowers;
//$sms->GetLoginPassSMSTowerFromBase();
$sms=new SmsAgent;
$sms->Login();

$blibase = GetDef('blibase');
$sms->sender=GetSMSSender($blibase,$sms->sender);
//echo "$sms->sender\n";

$lg=new Tlog();
$lg->Save(2,"Стартовал групповую отправку СМС");

$sql="select * from smslist where status<>'send'";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать!".mysqli_error($sqlcn->idsqlconnection));   
while($row = mysqli_fetch_array($result)) {
  $id=$row["id"];  
  $mobile=$row["mobile"];  
  $smstxt=$row["smstxt"];  
    $res=$sms->sendSMS($mobile,$smstxt);
    if (is_array($res)==true){        
        //var_dump($res);
        $idmess=$res[0]["id"];
        $res='ok';        
    };
    if ($res=="ok"){
      $res=$sms->getStatus($idmess);
      $cost=$res[0]["smsPrice"];
      $lg->Save('2',$mobile."/".$smstxt."(group)",$cost,$blibase);  
      $res="ok";  
      $sql="update smslist set status='send' where id='$id'";
      $result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу обновить статус!".mysqli_error($sqlcn->idsqlconnection));   
    };
    //sleep(10);
};    

$lg->Save(2,"Закончил групповую отправку СМС");

?>
