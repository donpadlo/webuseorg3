<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://clients.smstower.ru/getbalance.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "password=".$this->password."&login=".$this->login);
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			die("Ошибка: " . curl_error($ch));
		} else {
			curl_close($ch);
			$xml = simplexml_load_string($data);
			switch($xml->code){
				case 1:
					return (int)$xml->balance;
				break;

				case 4:
					echo 'Ошибка: Неправильный логин или пароль';
				break;

				case 7:
					echo 'Ошибка: Некорректный IP адрес';
				break;

				case 10:
					echo 'Ошибка: Тех. проблема на стороне сервера';
				break;

				default:
					echo $data;
				break;
			}
		}
	}
public function sendSMS($phones,$text){
$tsms=new Tcconfig();
$dtsms=$tsms->GetByParam("datetimetosmssend");
if ($dtsms==""){
  $dtsms=microtime(true);  
  $tsms->SetByParam("datetimetosmssend", $dtsms);
};
$nw=intval(round($dtsms-microtime(true),0));
if ($nw<=0){      
            $sender=$this->sender;
           // echo "!$sender!";
		$result = array();
		//$sms=iconv('Windows-1251','UTF-8',$text);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://clients.smstower.ru/sender.v2.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "sender=".$this->sender."&password=".$this->password."&login=".$this->login."&sms=$text&phone=$phones");
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return "Ошибка: " . curl_error($ch);
		} else {
			curl_close($ch);
			$xml = simplexml_load_string($data);
			switch($xml->code){
				case 1:
					//echo 'Сообщение успешно отправлено. Всего смс: '.$xml->smsCount;
					foreach ($xml->xpath('//message') as $message) {
						$result[] = array("phone"=>(string)$message->phoneNumber, "id"=>(int)$message->idMessage);
					}
					return $result;
				break;

				case 2:
					return 'Ошибка: Некорректный номер телефона';
				break;

				case 3:
					return 'Ошибка: Некорректное смс сообщение';
				break;

				case 4:
					return 'Ошибка: Неправильный логин или пароль';
				break;

				case 5:
					return 'Ошибка: Недостаточно средств на балансе';
				break;

				case 6:
					return 'Ошибка: Некорректный отправитель';
				break;
		
				case 7:
					return 'Ошибка: Некорректный IP адрес';
				break;
		
				case 10:
					return 'Ошибка: Тех. проблема на стороне смс шлюза';
				break;

				default:
					return $data;
				break;
			}
		} 
} else {
    return 'Ошибка: отправка приостановлена';
}
}
//Запросить статус смс по $id (множественный выбор - через запятую)
public function getStatus($id){
		$ch = curl_init();
		$result = array();
		curl_setopt($ch, CURLOPT_URL,"http://clients.smstower.ru/getstatus.v2.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "password=".$this->password."&login=".$this->login."&messageid=$id");
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			die("Ошибка: " . curl_error($ch));
		} else {
			curl_close($ch);
			$xml = simplexml_load_string($data);
			switch($xml->code){
				case 1:
					foreach ($xml->xpath('//message') as $message) {
                                            //var_dump($message);
						$result[] = array("id"=>(int)$message->idMessage, "deliveryStatus"=>(string)$message->deliveryStatus, "datetime"=>(string)$message->dateFinalStatus,"smsPrice"=>(string)$message->smsPrice);
					}	
					return $result;
				break;

				case 2:
					echo 'Ошибка: Не задан список идентификаторов сообщений';
				break;

				case 3:
					echo 'Ошибка: Идентификатор сообщения не является числом';
				break;

				case 4:
					echo 'Ошибка: Неправильный логин или пароль';
				break;

				case 7:
					echo 'Ошибка: Некорректный IP адрес';
				break;

				case 8:
					echo 'Ошибка: Превышено максимальное количество одновременно обрабатываемых идентификаторов (100)';
				break;
		
				case 9:
					echo 'Ошибка: Ни один идентификатор не найден в БД';
				break;
		
				case 10:
					echo 'Ошибка: Тех. проблема на стороне сервера';
				break;

				default:
					echo $data;
				break;
			}
		}
}
function Destroy(){
    unset($this);
}    
};