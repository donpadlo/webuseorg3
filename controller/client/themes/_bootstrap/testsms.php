<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$sms=new SmsAgent;
$sms->Login();
$sms->sender=GetSMSSender(1,$sms->sender);
$sender=$sms->sender;
$bal=$sms->getBalance();

echo "$sender!$bal!";

$res=$sms->sendSMS("89212347594", "test");

var_dump($res);
echo "<br><br>";
    if (is_array($res)==true){        
        //var_dump($res);
        $idmess=$res[0]["id"];
        $res='ok';        
    };
    if ($res=="ok"){
      echo "!idmess:$idmess!";  
      $res=$sms->getStatus($idmess);
      var_dump($res);
      $cost=$res[0]["smsPrice"];
      echo "#cost: $cost #";
      $res="ok";
    };