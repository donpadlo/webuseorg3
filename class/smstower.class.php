<?php

class SMSTowers {
	//Переменнные
	var $last_id = 0;
	var $login = "";
	var $password = "";
	var $sender = "";
        var $smsdiffres=0;
	//Конструктор
	function SMSTower($login,$password,$sender)
	{
		$this->login = $login;
		$this->password = $password;
		$this->sender = $sender;
	}
	//Проверить баланс
	public function getBalance()
	{
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
	//Отправка смс (на несколько номеров - через запятую), $text в UTF-8
	public function sendSMS($phones,$text)
	{
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
	}
	//Запросить статус смс по $id (множественный выбор - через запятую)
	public function getStatus($id)
	{
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
						$result[] = array("id"=>(int)$message->idMessage, "deliveryStatus"=>(string)$message->deliveryStatus, "datetime"=>(string)$message->dateFinalStatus);
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
	//Деструктор
	function Destroy()
	{
		unset($this);
	}
    function GetLoginPassSMSTowerFromBase(){
                global $sqlcn;
  		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='smstowerlogin'");               
  		if ($result!='')                    
  		{while ($myrow = mysqli_fetch_array($result))
  		 {$this->login = $myrow["valueparam"];};
  		 } else {die('Неверный запрос SMSTower.GetLoginPassSMSTowerFromBase: ' . mysqli_error($sqlcn->idsqlconnection));}
  		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='smstowerpass'");               
  		if ($result!='')                    
  		{while ($myrow = mysqli_fetch_array($result))
  		 {$this->password = $myrow["valueparam"];};
  		 } else {die('Неверный запрос SMSTower.GetLoginPassSMSTowerFromBase: ' . mysqli_error($sqlcn->idsqlconnection));}
  		$result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='smsdiffres'");               
  		if ($result!='')                    
  		{while ($myrow = mysqli_fetch_array($result))
  		 {$this->smsdiffres = $myrow["valueparam"];};
  		 } else {die('Неверный запрос SMSTower.GetLoginPassSMSTowerFromBase: ' . mysqli_error($sqlcn->idsqlconnection));}
                

    }
	
}