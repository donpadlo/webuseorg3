<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

/* 

Класс - прокладка для smstower, обеспечивающий функционал:

sms=new SmsAgent
sms->sender='bla-bla'
sms->login='bla-bla'
sms->password='bla-bla'
sms->smsdiffres='bla-bla'
sms->agentname='bla-bla'
sms->login(login,pass,sender)
sms->GetBalanse();
sms->sendsms(phone,txt) 
sms->getStatus(id)       
 
*/
class SmsAgent {
    
var $last_id = 0;
var $login = "";
var $password = "";
var $sender = "";
var $smsdiffres=0;
var $agentname = "SMSTower";
var $money=0;

function login(){
global $sqlcn;    
    $sql="select * from sms_center_config where sel='Yes'";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу прочитать настройки sms_center_config!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
      $this->smsdiffres=$row["smsdiff"];  
      $this->sender=$row["sender"];        
      $this->agentname=$row["agname"];        
      $this->login=$row["smslogin"];        
      $this->password=$row["smspass"];        
    };                
}
public function getBalance(){
$src = '<?xml version="1.0" encoding="utf-8" ?><request><security><login value="'.$this->login.'" /> <password value="'.$this->password.'" /> </security></request>'; 
$href = 'https://sms.targetsms.ru/xml/balance.php'; // адрес сервера $res= '';		
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CRLF, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $src);
curl_setopt($ch, CURLOPT_URL, $href);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

$result = curl_exec($ch);
$xml = simplexml_load_string($result);
//$xml->money="2.25";
$rez=trim($xml->money." ")+0;
//$rez=$rez+0;
//$rez=$rez[0]["currency"];
//var_dump($rez);
//$xml->money=str_replace(".", ",", $xml->money);
return $rez;
	
}
public function sendSMS($phones,$text){    
$tsms=new Tcconfig();
$dtsms=$tsms->GetByParam("datetimetosmssend");
if ($dtsms==""){
  $dtsms=microtime(true);  
  $tsms->SetByParam("datetimetosmssend", $dtsms);
};
$nw=intval(round($dtsms-microtime(true),0));
    //проверяем, а нет ли глобального запрета по расписанию на отправку СМС?
    if ($nw<=0){
	$nw=GetCurrentStatusSchedule();
	$nw=$nw["sms"];
    };
if ($nw<=0){    

$sender=$this->sender;
$this->money=($this->getBalance()+0);
    $src='<?xml version="1.0" encoding="utf-8" ?>
    <request>
    <message type="sms"></message>
    <message> 
    <sender>'.$sender.'</sender> 
    <text>'.$text.'</text>
    <abonent phone="'.$phones.'"  number_sms="1"/>
    </message>
    <security>
    <login value="'.$this->login.'" />
    <password value="'.$this->password.'" />
    </security>
    </request>';
    //echo "$src!!!!!!!!";
    $href = 'https://sms.targetsms.ru/xml/index.php'; // адрес сервера $res= '';		
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CRLF, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $src);
    curl_setopt($ch, CURLOPT_URL, $href);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $result = curl_exec($ch);
    $xml = simplexml_load_string($result);
    //var_dump($xml);
    $rz=$xml->information;
    //echo "!!$rz!!";
    $result=array();
    $result[] = array("phone"=>(string)$phones, "id"=>(string)"non");
    //echo "--ok!";
    return $result;
    } else {
	$result=array();
	$result[] = array("phone"=>(string)"non", "id"=>(string)"non");	
    };   
}
//Запросить статус смс по $id (множественный выбор - через запятую)
public function getStatus($id){
    $result=array();
    $aa=$this->money+0;
    $bb=$this->getBalance()+0;
    $newm=$aa-$bb;
    //$newm=1;
   // echo "!!$newm!$aa!$bb!!";
    if (($newm)>0){        
        $rzn=$this->money-$newm;
      $result[] = array("id"=>(int)0, "deliveryStatus"=>(string)"send", "datetime"=>(string)"","smsPrice"=>(string)$newm);        
    } else {
        $result[] = array("id"=>(int)0, "deliveryStatus"=>(string)"fail", "datetime"=>(string)"","smsPrice"=>(string)"0");        
    };
    //var_dump($result);
    return $result;
}
function Destroy(){
    #unset($this);
}    
};