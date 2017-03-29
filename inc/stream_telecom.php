<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */


/* 

Класс - прокладка для stream-telecom, обеспечивающий функционал:

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
var $agentname = "stream-telecom";
var $server="http://gateway.api.sc/rest/";
var $session_get="";
var $session_post="";

function GetSessionId_Get($server,$login,$password){
        $href = $server.'Session/?login='.$login.'&password='.$password;			
        $result = $this -> GetConnect($href);
        return json_decode($result,true);
}
function GetSessionId_Post($server,$login,$password){
        $href = $server.'Session/session.php';
        $src = 'login='.$login.'&password='.$password;
        $result = $this -> PostConnect($src,$href);
        return json_decode($result,true);
}
function GetConnect($href){			
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $href);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $result=curl_exec($ch);			
        curl_close($ch);						
        return $result;
}
function PostConnect($src,$href){		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CRLF, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $src);
        curl_setopt($ch, CURLOPT_URL, $href);
        $result = curl_exec($ch);
        return $result;
        curl_close($ch);
}
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
    $this->session_get=$this->GetSessionId_Get($this->server,$this->login,$this->password);    
    $this->session_post=$this->GetSessionId_Post($this->server,$this->login,$this->password);    
}

public function getBalance(){
	$href = $this->server.'Balance/?sessionId='.$this->session_get;			
        $result = $this -> GetConnect($href);
	return json_decode($result,true);
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
    
    $href = $this ->server.'Send/SendSms/';
    $src = 'sessionId='.$this->session_post.'&sourceAddress='.$this->sender.'&destinationAddress='.$phones.'&data='.$text.'&validity=';
    $result = $this -> PostConnect($src,$href);	
    $rz=json_decode($result,true);    
    //echo "<br>";
   // var_dump($rz);
    //echo "<br>";
    if (isset($rz["Desc"])==true){
      $res=$rz["Desc"];    
    } else {
      $res[] = array("phone"=>$phones, "id"=>$rz[0]);
    };
    return $res;   
} else {
        $res[] = array("phone"=>"non", "id"=>"non");
	return $res;   
};
}

public function getStatus($id){

$href = $this->server.'State/?sessionId='.$this->session_get.'&messageId='.$id;
$result = json_decode($this -> GetConnect($href),true);
//$result = $this -> ChangeFormateDate(json_decode($result,true));    

$result[] = array("id"=>$id, "deliveryStatus"=>$result["StateDescription"], "datetime"=>$result["ReportedDateUtc"],"smsPrice"=>$result["Price"]);    

return $result;    

    
}

function Destroy(){
    unset($this);
}   

}