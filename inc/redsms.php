<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

/* 

Класс - прокладка для redsms, обеспечивающий функционал:

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


class Pest {
  public $curl_opts = array(
  	CURLOPT_RETURNTRANSFER => true,  // return result instead of echoing
  	CURLOPT_SSL_VERIFYPEER => false, // stop cURL from verifying the peer's certificate
  	CURLOPT_FOLLOWLOCATION => false,  // follow redirects, Location: headers
  	CURLOPT_MAXREDIRS      => 10     // but dont redirect more than 10 times
  );

  public $base_url;
  
  public $last_response;
  public $last_request;
  public $last_headers;
  
  public $throw_exceptions = true;
  
  public function __construct($base_url) {
    if (!function_exists('curl_init')) {
  	    throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
  	}
  	
  	// only enable CURLOPT_FOLLOWLOCATION if safe_mode and open_base_dir are not in use
  	if(ini_get('open_basedir') == '' && strtolower(ini_get('safe_mode')) == 'off') {
  	  $this->curl_opts['CURLOPT_FOLLOWLOCATION'] = true;
  	}
    
    $this->base_url = $base_url;
    
    // The callback to handle return headers
    // Using PHP 5.2, it cannot be initialised in the static context
    $this->curl_opts[CURLOPT_HEADERFUNCTION] = array($this, 'handle_header');
  }
  
  // $auth can be 'basic' or 'digest'
  public function setupAuth($user, $pass, $auth = 'basic') {
    $this->curl_opts[CURLOPT_HTTPAUTH] = constant('CURLAUTH_'.strtoupper($auth));
    $this->curl_opts[CURLOPT_USERPWD] = $user . ":" . $pass;
  }
  
  // Enable a proxy
  public function setupProxy($host, $port, $user = NULL, $pass = NULL) {
    $this->curl_opts[CURLOPT_PROXYTYPE] = 'HTTP';
    $this->curl_opts[CURLOPT_PROXY] = $host;
    $this->curl_opts[CURLOPT_PROXYPORT] = $port;
    if ($user && $pass) {
      $this->curl_opts[CURLOPT_PROXYUSERPWD] = $user . ":" . $pass;
    }
  }
  
  public function get($url) {
    $curl = $this->prepRequest($this->curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function post($url, $data, $headers=array()) {
    $data = (is_array($data)) ? http_build_query($data) : $data;
        
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'POST';
    $headers[] = 'Content-Length: '.strlen($data);
    $curl_opts[CURLOPT_HTTPHEADER] = $headers;
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    $curl_opts[CURLINFO_HEADER_OUT] = true;
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function put($url, $data, $headers=array()) {
    $data = (is_array($data)) ? http_build_query($data) : $data; 
    
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
    $headers[] = 'Content-Length: '.strlen($data);
    $curl_opts[CURLOPT_HTTPHEADER] = $headers;
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
    public function patch($url, $data, $headers=array()) {
    $data = (is_array($data)) ? http_build_query($data) : $data; 
    
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'PATCH';
    $headers[] = 'Content-Length: '.strlen($data);
    $curl_opts[CURLOPT_HTTPHEADER] = $headers;
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function delete($url) {
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function lastBody() {
    return $this->last_response['body'];
  }
  
  public function lastStatus() {
    return $this->last_response['meta']['http_code'];
  }
  
  /**
   * Return the last response header (case insensitive) or NULL if not present.
   * HTTP allows empty headers (e.g. RFC 2616, Section 14.23), thus is_null()
   * and not negation or empty() should be used.
   */
  public function lastHeader($header) {
    if (empty($this->last_headers[strtolower($header)])) {
      return NULL;
    }
    return $this->last_headers[strtolower($header)];
  }
  
  protected function processBody($body) {
    // Override this in classes that extend Pest.
    // The body of every GET/POST/PUT/DELETE response goes through 
    // here prior to being returned.
    return $body;
  }
  
  protected function processError($body) {
    // Override this in classes that extend Pest.
    // The body of every erroneous (non-2xx/3xx) GET/POST/PUT/DELETE  
    // response goes through here prior to being used as the 'message'
    // of the resulting Pest_Exception
    return $body;
  }

  
  protected function prepRequest($opts, $url) {
    if (strncmp($url, $this->base_url, strlen($this->base_url)) != 0) {
      $url = $this->base_url . $url;
    }
    $curl = curl_init($url);
    
    foreach ($opts as $opt => $val)
      curl_setopt($curl, $opt, $val);
      
    $this->last_request = array(
      'url' => $url
    );
    
    if (isset($opts[CURLOPT_CUSTOMREQUEST]))
      $this->last_request['method'] = $opts[CURLOPT_CUSTOMREQUEST];
    else
      $this->last_request['method'] = 'GET';
    
    if (isset($opts[CURLOPT_POSTFIELDS]))
      $this->last_request['data'] = $opts[CURLOPT_POSTFIELDS];
    
    return $curl;
  }
  
  private function handle_header($ch, $str) {
    if (preg_match('/([^:]+):\s(.+)/m', $str, $match) ) {
      $this->last_headers[strtolower($match[1])] = trim($match[2]);
    }
    return strlen($str);
  }

  private function doRequest($curl) {
    $this->last_headers = array();
    
    $body = curl_exec($curl);
    $meta = curl_getinfo($curl);
    
    $this->last_response = array(
      'body' => $body,
      'meta' => $meta
    );
    
    curl_close($curl);
    
    $this->checkLastResponseForError();
    
    return $body;
  }
  
  protected function checkLastResponseForError() {
    if ( !$this->throw_exceptions)
      return;
      
    $meta = $this->last_response['meta'];
    $body = $this->last_response['body'];
    
    if (!$meta)
      return;
    
    $err = null;
    switch ($meta['http_code']) {
      case 400:
        throw new Pest_BadRequest($this->processError($body));
        break;
      case 401:
        throw new Pest_Unauthorized($this->processError($body));
        break;
      case 403:
        throw new Pest_Forbidden($this->processError($body));
        break;
      case 404:
        throw new Pest_NotFound($this->processError($body));
        break;
      case 405:
        throw new Pest_MethodNotAllowed($this->processError($body));
        break;
      case 409:
        throw new Pest_Conflict($this->processError($body));
        break;
      case 410:
        throw new Pest_Gone($this->processError($body));
        break;
      case 422:
        // Unprocessable Entity -- see http://www.iana.org/assignments/http-status-codes
        // This is now commonly used (in Rails, at least) to indicate
        // a response to a request that is syntactically correct,
        // but semantically invalid (for example, when trying to 
        // create a resource with some required fields missing)
        throw new Pest_InvalidRecord($this->processError($body));
        break;
      default:
        if ($meta['http_code'] >= 400 && $meta['http_code'] <= 499)
          throw new Pest_ClientError($this->processError($body));
        elseif ($meta['http_code'] >= 500 && $meta['http_code'] <= 599)
          throw new Pest_ServerError($this->processError($body));
        elseif (!$meta['http_code'] || $meta['http_code'] >= 600) {
          throw new Pest_UnknownResponse($this->processError($body));
        }
    }
  }
}


class Pest_Exception extends Exception { }
class Pest_UnknownResponse extends Pest_Exception { }

/* 401-499 */ class Pest_ClientError extends Pest_Exception {}
/* 400 */ class Pest_BadRequest extends Pest_ClientError {}
/* 401 */ class Pest_Unauthorized extends Pest_ClientError {}
/* 403 */ class Pest_Forbidden extends Pest_ClientError {}
/* 404 */ class Pest_NotFound extends Pest_ClientError {}
/* 405 */ class Pest_MethodNotAllowed extends Pest_ClientError {}
/* 409 */ class Pest_Conflict extends Pest_ClientError {}
/* 410 */ class Pest_Gone extends Pest_ClientError {}
/* 422 */ class Pest_InvalidRecord extends Pest_ClientError {}

/* 500-599 */ class Pest_ServerError extends Pest_Exception {}


/**
 * Клиент для взаимодействия с Devino REST API
 *
 * Клиент может использоваться как набор статичных функций или как класс.
 * Статиченые функции имеют суфикс _St. Необходимо сохранять ID сессии.
 * При использовании класса, идентификатор сессии хранится внутри класса.
 *
 */
class SMSClient {

	//////////////////////////////// Статичные методы ////////////////////////////////

	/**
	 * Базовый адрес для отправки запросов
	 * @const
	 */
	const m_baseURL = "https://integrationapi.net/rest";

	/**
	 * Запрос ID сессии
	 *
	 * @access public
	 * @static
	 *
	 * @param string $login Имя пользователя
	 * @param string $password Пароль
	 *
	 * @return string Идентификатор сессии
	 * @throws SMSError_Exception
	 */
	public static function getSessionID_St( $login, $password ) {
		$pest = new Pest(SMSClient::m_baseURL);
		$sessionID = "";
		try {
			$sessionID = str_replace('"', '',
				$pest->get('/User/SessionId?login='.$login.'&password='.$password)
			);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $sessionID;
	}
	
	/**
	 * Запроса баланса
	 *
	 * @access public
	 * @static	 
	 *
	 * @param string $sessionID ID сессии. @see getSessionID_St
	 *
	 * @return double Баланс
	 * @throws SMSError_Exception
	 */
	public static function getBalance_St( $sessionID ) {
		$pest = new Pest(SMSClient::m_baseURL);
		$balance = 0;
		try {
			$balance = str_replace('"', '',
				$pest->get('/User/Balance?sessionId='.$sessionID)
			);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $balance;
	}
	
	/**
	 * Отправка SMS-сообщения
	 *
	 * @access public
	 * @static
	 *
	 * @param string  $sessionID ID сессии. @see getSessionID_St
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param string  $destinationAddress адрес назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp. Необязательный параметр.
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return array массив идентификаторов сообщений
	 * @throws SMSError_Exception
	 */
	public static function send_St( $sessionID, $sourceAddres, $destinationAddress, $data, $sendDate=null, $validity=0 ) {
		$pest = new Pest(SMSClient::m_baseURL);
		$result = array();
		try {
			$result = json_decode($pest->post('/Sms/Send', 
				SMSClient::createRequestParameters( $sessionID, $sourceAddres, $destinationAddress, $data, $sendDate, $validity )
			),true);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $result;
	}
	
	/**
	 * Отправка SMS-сообщения с учетом часового пояса получателя.
	 *
	 * @access public
	 * @static
	 *
	 * @param string  $sessionID ID сессии. @see getSessionID_St
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param string  $destinationAddress адрес назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения по местному времени получателя. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return array массив идентификаторов сообщений
	 * @throws SMSError_Exception
	 */	
	public static function sendByTimeZone_St( $sessionID,$sourceAddres, $destinationAddress, $data, $sendDate, $validity=0 ) {
		$pest = new Pest(SMSClient::m_baseURL);
		$result = array();
		try {
			$result = json_decode($pest->post('/Sms/SendByTimeZone', 
				SMSClient::createRequestParameters( $sessionID, $sourceAddres, $destinationAddress, $data, $sendDate, $validity )
			),true);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $result;
	}
	
	/**
	 * Отправка SMS-сообщения нескольким адрессатам
	 *
	 * @access public
	 * @static
	 *
	 * @param string  $sessionID ID сессии. @see getSessionID_St
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param array   $destinationAddresses массив строк адресов назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp. Необязательный параметр.
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return array массив идентификаторов сообщений
	 * @throws SMSError_Exception
	 */	
	public static function sendBulk_St( $sessionID,$sourceAddres, $destinationAddresses, $data, $sendDate=null, $validity=0 ) {
	
		if (gettype($destinationAddresses) == "string") {
			$destinationAddresses = array($destinationAddresses);
		}
	
		$pest = new Pest(SMSClient::m_baseURL);
		$result = array();
		try {
			$result = json_decode($pest->post('/Sms/SendBulk', 
				SMSClient::createRequestParameters( $sessionID, $sourceAddres, $destinationAddresses, $data, $sendDate, $validity )
			),true);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $result;
	}
	
	/**
	 * Запрос статуса SMS-сообщения
	 *
	 * @access public
	 * @static
	 *
	 * @param string $sessionID ID сессии. @see getSessionID_St
	 * @param string $messageID ID сообщения.
	 *
	 * @return array массив полей:
	 *		State	- статус сообщения. @see SMSClientSMSStatus
	 *		TimeStampUtc		- дата и время получения ответа
	 *		StateDescription	- описание статуса
	 *		CreationDateUtc		- дата создания
	 *		SubmittedDateUtc	- дата отправки
	 *		ReportedDateUtc		- дата доставки
	 *		Price	- цена за сообщение
	 * @throws SMSError_Exception
	 */
	public static function getSMSState_St( $sessionID, $messageID ) {
		$pest = new Pest(SMSClient::m_baseURL);
		$result = array(
			'State' => SMSClientSMSStatus::SMS_STATUS_Unknown,
			'TimeStampUtc' => time(),
			'StateDescription' => '',
			'CreationDateUtc' => null,
			'SubmittedDateUtc' => null,
			'ReportedDateUtc' => null,
			'Price' => null);
		try {
			$result = json_decode($pest->get('/Sms/State?sessionId='.$sessionID.'&messageId='.$messageID),true);
			$result['TimeStampUtc'] = substr(substr($result['TimeStampUtc'],6),0,-2) ;
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);			
		return $result;
	}
	
	/**
	 * Запрос входящих SMS-сообщений
	 *
	 * @access public
	 * @static
	 * 
	 * @param string $sessionID ID сессии. @see getSessionID_St
	 * @param mixed  $minDateUTC начало периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param mixed  $maxDateUTC конец периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp	 
	 *
	 * @return array массив объектов с полями:
	 * 		string Data				- текст сообщения
	 *		string SourceAddress	- адрес отправителя
	 *		string DestinationAddress	- адрес приема входящих сообщений
	 *		string ID	- идентификатор сообщения
	 * @throws SMSError_Exception
	 */
	public static function getInbox_St( $sessionID, $minDateUTC, $maxDateUTC ) {
		$requestString = '/Sms/In?sessionId='.$sessionID;
		
		if (gettype($minDateUTC) == "string") {
			$requestString .= '&minDateUTC='.$minDateUTC;
		} else if (gettype($minDateUTC) == "integer") {
			$requestString .= '&minDateUTC='.date("Y-m-d",$minDateUTC).'T'.date("H:i:s",$minDateUTC);
		}
		
		if (gettype($maxDateUTC) == "string") {
			$requestString .= '&maxDateUTC='.$maxDateUTC;
		} else if (gettype($maxDateUTC) == "integer") {
			$requestString .= '&maxDateUTC='.date("Y-m-d",$maxDateUTC).'T'.date("H:i:s",$maxDateUTC);
		}

		$pest = new Pest(SMSClient::m_baseURL);
		
			$result = array(
				'Data' => '',
				'SourceAddress' => '',
				'DestinationAddress' => '',
				'ID' => null);
		try {
			$result = json_decode($pest->get($requestString),true);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);
		
		return $result;
	}
	
	/**
	 * Запрос статистики по SMS-рассылкам
	 *
	 * @access public
	 * @static	 
	 *
	 * @param string $sessionID ID сессии. @see getSessionID_St
	 * @param mixed  $startDate начало периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param mixed  $stopDate конец периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp	 
	 *
	 * @return array массив с информацией по статистике
	 * @throws SMSError_Exception
	 */
	public static function getStatistics_St( $sessionID, $startDate, $stopDate ) {
		$requestString = '/Sms/Statistics?sessionId='.$sessionID;

		if (gettype($startDate) == "string") {
			$requestString .= '&startDateTime='.$startDate;
		} else if (gettype($startDate) == "integer") {
			$requestString .= '&startDateTime='.date("Y-m-d",$startDate).'T'.date("H:i:s",$startDate);
		}
		
		if (gettype($stopDate) == "string") {
			$requestString .= '&endDateTime='.$stopDate;
		} else if (gettype($stopDate) == "integer") {
			$requestString .= '&endDateTime='.date("Y-m-d",$stopDate).'T'.date("H:i:s",$stopDate);
		}

		$pest = new Pest(SMSClient::m_baseURL);
		$result = array();
		try {
			$result = json_decode($pest->get($requestString),true);
		} catch( Exception $e ) {
			$errorInfo = json_decode($e->getMessage());
			unset($pest);			
			throw( new SMSError_Exception( $errorInfo->Desc, $errorInfo->Code));
		}
		unset($pest);
		return $result;
	}
	
	//////////////////////////////// Методы для работы с классом ////////////////////////////////
	
	/**
	 * Сохраненный идентификатор сессии
	 * 
	 * @access protected
	 */
	protected $m_sessionID = "";
	
	/**
	 * Сохраненный логин
	 * 
	 * @access protected	 
	 */
	protected $m_login = "";
	/**
	 * Сохраненный пароль
	 * 
	 * @access protected	 
	 */
	protected $m_password = "";
	
	/**
	 * Конструктор. В него передаются логин и пароль.
	 * 
	 * @param string $login Логин
	 * @param string $password Пароль
	 *
	 */
	function __construct( $login, $password ) {
		$this->m_login = $login;
		$this->m_password = $password;
	}
	
	/**
	 * Запрос ID сессии.
	 *
	 * @access public
	 *
	 * @return string Идентификатор сессии
	 * @throws SMSError_Exception
	 */	
	public function getSessionID() {
		$this->m_sessionID = SMSClient::getSessionID_St( $this->m_login, $this->m_password );
		return $this->m_sessionID;
	}
	
	/**
	 * Запроса баланса
	 *
	 * @access public
	 *
	 * @return double Баланс
	 * @throws SMSError_Exception
	 */	
	public function getBalance() {
		$balance = SMSClient::getBalance_St( $this->m_sessionID );
		return $balance;
	}
	
	/**
	 * Отправка SMS-сообщения
	 *
	 * @access public
	 *
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param string  $destinationAddress адрес назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp. Необязательный параметр.
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return
	 * @throws SMSError_Exception
	 */	
	public function send( $sourceAddres, $destinationAddress, $data, $sendDate=null, $validity=0 ) {
		$result = SMSClient::send_St( $this->m_sessionID, $sourceAddres, $destinationAddress, $data, $sendDate, $validity );
		return $result;
	}
	
	/**
	 * Отправка SMS-сообщения с учетом часового пояса получателя.
	 *
	 * @access public
	 *
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param string  $destinationAddress адрес назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения по местному времени получателя. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return
	 * @throws SMSError_Exception
	 */		
	public function sendByTimeZone( $sourceAddres, $destinationAddress, $data, $sendDate, $validity=0 ) {
		$result = SMSClient::sendByTimeZone_St( $this->m_sessionID, $sourceAddres, $destinationAddress, $data, $sendDate, $validity );
		return $result;		
	}

	/**
	 * Отправка SMS-сообщения с учетом часового пояса получателя.
	 *
	 * @access public
	 *
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param array   $destinationAddresses массив строк адресов назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp. Необязательный параметр.
	 * @param integer $validity Время жизни сообщения в минутах. Необязательный параметр
	 * 
	 * @return
	 * @throws SMSError_Exception
	 */	
	public function sendBulk( $sourceAddres, $destinationAddresses, $data, $sendDate=null, $validity=0 ) {
		$result = SMSClient::sendBulk_St( $this->m_sessionID, $sourceAddres, $destinationAddresses, $data, $sendDate, $validity );
		return $result;		
	}	
	
	/**
	 * Запрос статуса SMS-сообщения
	 *
	 * @access public
	 *
	 * @param string $messageID ID сообщения.
	 *
	 * @return array массив полей:
	 *		State	- статус сообщения. @see SMSClientSMSStatus
	 *		SMSClientSMSStatus	- дата и время получения ответа
	 *		StateDescription	- описание статуса
	 *		CreationDateUtc		- дата создания
	 *		SubmittedDateUtc	- дата отправки
	 *		ReportedDateUtc		- дата доставки
	 *		Price	- цена за сообщение
	 * @throws SMSError_Exception
	 */
	public function getSMSState( $messageID ) {
		$result = SMSClient::getSMSState_St( $this->m_sessionID, $messageID );
		return $result;
	}
	
	/**
	 * Запрос входящих SMS-сообщений
	 *
	 * @access public
	 * 
	 * @param mixed  $minDateUTC начало периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param mixed  $maxDateUTC конец периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp	 
	 *
	 * @return array массив объектов с полями:
	 * 		string Data				- текст сообщения
	 *		string SourceAddress	- адрес отправителя
	 *		string DestinationAddress	- адрес приема входящих сообщений
	 *		string ID	- идентификатор сообщения
	 * @throws SMSError_Exception
	 */
	public function getInbox( $minDateUTC, $maxDateUTC ) {
		$result = SMSClient::getInbox_St( $this->m_sessionID, $minDateUTC, $maxDateUTC );
		return $result;
	}
	
	/**
	 * Запрос статистики по SMS-рассылкам
	 *
	 * @access public
	 *
	 * @param mixed  $startDate начало периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param mixed    $stopDate конец периода выборки. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp	 
	 *
	 * @return array массив с информацией по статистике
	 * @throws SMSError_Exception
	 */	
	public function getStatistics( $startDate, $stopDate ) {
		$result = SMSClient::getStatistics_St( $this->m_sessionID, $startDate, $stopDate );
		return $result;	
	}
	
	//////////////////////////////// Служебные методы ////////////////////////////////
	
	/**
	 * Функция готовит строку запроса для методов отправки сообщения
	 *
	 * @access protected	 
	 * @static 
	 *
	 * @param string  $sessionID ID сессии. @see getSessionID_St
	 * @param string  $sourceAddres отправитель. До 11 латинских символов или до 15 цифровых.
	 * @param mixed   $destinationAddress адрес или массив адресов назначения. (Код страны+код сети+номер телефона, Пример: 79031234567
	 * @param string  $data Текст сообщения
	 * @param mixed   $sendDate дата отправки сообщения. Строка вида (YYYY-MM-DDTHH:MM:SS) или Timestamp
	 * @param integer $validity Время жизни сообщения в минутах
	 * 
	 * @return array Массив с параметрами
	 */
	protected static function createRequestParameters( $sessionID, $sourceAddres, $destinationAddress, $data, $sendDate, $validity ) {
		$parameters = array(
			'sessionId' => $sessionID,
			'sourceAddress' => $sourceAddres,
			'data' => $data
			);
		
		if (gettype($destinationAddress) == "string") {
			
			$parameters['destinationAddress'] = $destinationAddress;
			
		} else if (gettype($destinationAddress) == "array") {
			$parameters['destinationAddresses'] = $destinationAddress;//$destinationAddressesString;
		}	
		
		if (gettype($sendDate) == "string") {
			$parameters['sendDate'] = $sendDate;
		} else if (gettype($sendDate) == "integer") {
			$parameters['sendDate'] = date("Y-m-d",$sendDate).'T'.date("H:i:s",$sendDate);			
		}
		
		if ((gettype($validity) == "integer") && ($validity != 0)) {
			$parameters['validity'] = $validity;
		}
		return $parameters;
	}
}

/**
 * Список констант с кодами ошибок
 */
class SMSClientError {
	const ERROR_OK							= 0;
	const ERROR_ArgumentCanNotBeNullOrEmpty	= 1;
	const ERROR_InvalidAgrument				= 2;
	const ERROR_InvalidSessionID			= 3;
	const ERROR_UnauthorizedAccess			= 4;
	const ERROR_NotEnoughCredits			= 5;
	const ERROR_InvalidOperation			= 6;
	const ERROR_Forbidden					= 7;
	const ERROR_GatewayError				= 8;
	const ERROR_InternalServerError			= 9;
}

/**
 * Список констант с кодами статусов SMS-сообщений
 */
class SMSClientSMSStatus {
	const SMS_STATUS_Send		= -1;
	const SMS_STATUS_InQueue	= -2;
	const SMS_STATUS_Deleted	= 47;
	const SMS_STATUS_Stopped	= -98;
	const SMS_STATUS_Delivered	= 0;
	const SMS_STATUS_InvalidSourceAddress			= 10;
	const SMS_STATUS_InvalidDestinationAddress		= 11;
	const SMS_STATUS_UnallowedDestinationAddress	= 41;
	const SMS_STATUS_RejectedBySMSCenter			= 42;
	const SMS_STATUS_TimeOut	= 46;
	const SMS_STATUS_Rejected	= 69;
	const SMS_STATUS_Unknown	= 99;
	const SMS_STATUS_UnknownByTimeout = 255;
}

/**
 * Генерируемое исключение при ошибке отправки SMS
 */
class SMSError_Exception extends Exception {}

class SmsAgent {
    
var $last_id = 0;
var $login = "";
var $password = "";
var $sender = "";
var $smsdiffres=0;
var $agentname = "REDSMS";

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
    $client = new SMSClient($this->login,$this->password);
    $sessionID = $client->getSessionID();
    return $client->getBalance();
}
public function sendSMS($phones,$text){
    $client = new SMSClient($this->login,$this->password);
    $sessionID = $client->getSessionID();
    try {
	$client->send("".$this->sender."","$phones","$text");
    } catch( SMSError_Exception $e ) {
	return ($e);
    }        
}
public function getStatus($id){
}
function Destroy(){
    unset($this);
}    
};