</br>
<label>Результат отправки:</label>
</br>
<pre>
<?php
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


$ids = _POST('ids');
$txtsms = _POST('txtsms');
$billingid = _POST('billingid');
$sms->sender=GetSMSSender($billingid,$sms->sender);

$idmass=explode(";",$ids);

for ($i=0;$i<count($idmass);$i++) {
  $idm=$idmass[$i];
  $sql="SELECT su.phone AS phone FROM sms_users su LEFT JOIN sms_group_members sgm ON sgm.user_id = su.id LEFT JOIN sms_groups sg ON sg.id = sgm.group_id where sg.id='$idm'";
  $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать!".mysqli_error($sqlcn->idsqlconnection));   
  while($row = mysqli_fetch_array($result)) {
    $tl=$row["phone"];
    $res=$sms->sendSMS($tl,$txtsms);
    if (is_array($res)==true){        
        //var_dump($res);
        $idmess=$res[0]["id"];
        $res='ok';        
    };
    if ($res=="ok"){
      $res=$sms->getStatus($idmess);
      $cost=$res[0]["smsPrice"];
      //$lg->Save('2',$tl."/".$smstxt."(group)",$cost,"");  
      $res="ok";
    };    
    echo "$tl -$res</br>";  
  };
};

?>
</pre>