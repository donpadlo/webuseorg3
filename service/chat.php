#!/usr/local/bin/php
<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
// 
// Сей файл - это серверная часть чата поддержки. Клиентская - на сайтах и в НОС
// version 2.00

define('WUO_ROOT', dirname(__FILE__));

include_once(WUO_ROOT.'/../config.php');
include_once(WUO_ROOT.'/../class/sql.php'); // Класс работы с БД
include_once(WUO_ROOT.'/../class/config.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/cconfig.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/users.php'); // Класс работы с пользователями

// Загружаем все что нужно для работы движка
include_once(WUO_ROOT.'/../inc/connect.php'); // Соединяемся с БД, получаем $mysql_base_id
include_once(WUO_ROOT.'/../inc/config.php'); // Подгружаем настройки из БД, получаем заполненый класс $cfg
include_once(WUO_ROOT.'/../inc/functions.php'); // Загружаем функции
include_once(WUO_ROOT.'/../inc/login.php'); // Создаём пользователя $user

include_once(WUO_ROOT.'/../inc/func_chat.php'); // Загружаем рутинные функции для чата

//читаю настройки чата
$vl=new Tcconfig();
$ip_chat_server=$vl->GetByParam("ip-chat-server");
$ip_chat_port=$vl->GetByParam("ip-chat-port");
$chat_admins=$vl->GetByParam("chat-admins");
$ssl_pem=$vl->GetByParam("ssl-pem"); //ssl ?
$ssl_pass=$vl->GetByParam("ssl-pass"); //ssl-pass
$chat_wellcome=$vl->GetByParam("chat-wellcome"); //
$chat_wss_url_noc=$vl->GetByParam("chat-wss-url-noc"); //
$chat_wss_url_help=$vl->GetByParam("chat-wss-url-help"); //
if ($ip_chat_server=="" or $ip_chat_port==""){ die("--укажите настройки IP сервера и порта в настройках веб интерфейса чата!\n");};

//при старте сервера, всех отправляю в offline
$sql="update chat_users set online=0";
$result = $sqlcn->ExecuteSQL($sql);

if ($ssl_pem!=""){
    echo "-используем ssl\n";    
    $pemfile = $ssl_pem;
    $pem=file_get_contents($pemfile);
    $context = stream_context_create();
    stream_context_set_option($context, 'ssl', 'local_cert', $pem);
    stream_context_set_option($context, 'ssl', 'passphrase', "$ssl_pass");
    stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
    stream_context_set_option($context, 'ssl', 'verify_peer', false);
    $socket = stream_socket_server("tls://$ip_chat_server:$ip_chat_port", $errno, $errstr,STREAM_SERVER_BIND|STREAM_SERVER_LISTEN, $context);
    stream_socket_enable_crypto($socket, false);
} else 
    $socket = stream_socket_server("tcp://$ip_chat_server:$ip_chat_port", $errno, $errstr);

if (!$socket) {die("$errstr ($errno)\n");};

echo "--демон чат сервера стартовал $ip_chat_server:$ip_chat_port \n";
// $user_array
// $user_array[idconnect]["user_id"]=user_id;
// $user_array[idconnect]["connect"]=$connect

$user_array = array();
$connects = array();
while (true) {
    //формируем массив прослушиваемых сокетов:
    $read = $connects;
    $read []= $socket;
    $write = $except = null;
    if (!stream_select($read, $write, $except, null)) {//ожидаем сокеты доступные для чтения (без таймаута)
        break;
    }

    if (in_array($socket, $read)) {//есть новое соединение
        //принимаем новое соединение и производим рукопожатие:
        if (($connect = stream_socket_accept($socket, -1)) && $info = handshakeSocket($connect)) {
	    echo "---рукопожатие $connect\n";
            $connects[] = $connect;//добавляем его в список необходимых для обработки
            onOpen($connect, $info);//вызываем пользовательский сценарий
        }
        unset($read[ array_search($socket, $read) ]);
    }

    foreach($read as $connect) {//обрабатываем все соединения
        $data = fread($connect, 100000);

        if (!$data) { //соединение было закрыто	
            fclose($connect);
            unset($connects[ array_search($connect, $connects) ]);
            onClose($connect);//вызываем пользовательский сценарий
            continue;
        }

        onMessage($connect, $data,$info);//вызываем пользовательский сценарий
    }
}
fclose($server);

function onClose($connect) {
    OfflineStatus($connect);
    echo "---close $connect\n";
}

function onOpen($connect, $info) {
    echo "---open $connect\n";
    //var_dump($info);
    $msg=[];
    $msg["command"]="GetOnline";
    $msg["result"]=GetOnlineChatManagers();
    fwrite($connect, encodeSocket(json_encode($msg)));
}

function onMessage($connect, $data,$info) {    
    global $sqlcn;
    $data=decodeSocket($data);   
    $message=json_decode($data["payload"]);
    echo "---пришло: \n";
    var_dump($message);
    //обрабатываем команды WEB клиента
    if (isset($message->client)){
	if (isset($message->command)){
	    if ($message->command=="GetMyId"){
		//создаем нового пользователя
		$sql="INSERT INTO chat_users (id, name) VALUES (NULL,'')";
		$result = $sqlcn->ExecuteSQL($sql) or die('Не могу добавить абонента!: '.mysqli_error($sqlcn->idsqlconnection));
		//получаем его ID
		$user_id=mysqli_insert_id($sqlcn->idsqlconnection);
		//обновляем имя
		$username="Guest".$user_id;
		$sql="update chat_users set name='$username' where id=$user_id";
		$result = $sqlcn->ExecuteSQL($sql);		
		//////////////////////
		$exmessage=[];
		$exmessage["command"]="GetMyId";
		$exmessage["user_id"]=$user_id;
		$exmessage["name"]=$username;
		echo "---ушло:\n";
		var_dump($exmessage);
		fwrite($connect, encodeSocket(json_encode($exmessage)));		
	    };
	};		
    };
    //обрабатываем команды NOC
    if (isset($message->clientNOC)){
	if (isset($message->command)){
	};	
    };
    //общие для всех команды
    ParseCommon($message,$connect);
    
};    

///////////////////////

//получаем ID менеджера который отвечает в чате поддержки. false - если никого нет
function GetOnlineChatManagers(){
  global $sqlcn,$users,$chat_admins;       
  $mes=array();
  $mes["user_id"]=false;
  $mes["user_name"]="неизвстно";  
  $ar=explode(";",$chat_admins);
  foreach ($ar as $user) {
     $sql="select users_profile.fio from chat_users inner join users_profile on users_profile.usersid=chat_users.userid where chat_users.id=$user and chat_users.online=1;"; 
     echo "$sql\n";
     $result = $sqlcn->ExecuteSQL($sql);
     while($row = mysqli_fetch_array($result)) {	
	  $mes["user_id"]=$user;
	  $mes["user_name"]=$row["fio"];
     };          
  };  
  return $mes;  
};
function OfflineStatus($connect){
global $sqlcn,$user_array,$connects;
#echo "Текущие соединения до удаления:";
#var_dump($user_array);
//если в нашем массиве есть соединение, которого быть не должно - удаляем его
    $id=$user_array[intval($connect)]["user_id"]; //получили пользователя, у которого нужно убить соединение
    unset($user_array[intval($connect)]); // убираем...
    //если больше соединений с этим пользователем нет, то ставим ему "оффлайн"
    $flag=0;
    foreach ($user_array as $infc) {
	if ($infc["user_id"]==$id){$flag=1;};
    };
      if ($flag==0){
	    $sql="update chat_users set online=0,lastping=now() where id=$id";
#	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql);
	    //разослать всем активным соединениям новый контакт лист
	    echo "Текущие соединения для рассылки обновленного контакт листа:\n";
	    //var_dump($connects);
	    //echo "!!!!!!\n";
	    foreach ($connects as $cn) {
		$exmessage=[];
		$exmessage["command"]="GetContactList";
		$exmessage["result"]=GetContactList($cn);
		echo "---ушло для: $cn\n";
		//var_dump($exmessage);
		fwrite($cn, encodeSocket(json_encode($exmessage)));			    		
	    };
	    //var_dump($connects);
      };    
#echo "Текущие соединения после удаления:";
#var_dump($user_array); 
}; 
function OnlineStatus($id){
global $sqlcn,$connects;
    //если пользователь был оффлайн, то делаем всем рассылку
    $sql="select online from chat_users where id=$id";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 1!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
	$res=$myrow["online"];
    };    
    $sql="update chat_users set online=1,lastping=now() where id=$id";
    //echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql);
    if ($res==0){
	    foreach ($connects as $cn) {
		$exmessage=[];
		$exmessage["command"]="GetContactList";
		$exmessage["result"]=GetContactList($cn);
		echo "---ушло для: $cn\n";
		//var_dump($exmessage);
		fwrite($cn, encodeSocket(json_encode($exmessage)));			    		
	    };	
    };
    
};
//есть ли не прочитанные сообщения?
function YetNoRead($from_id,$to_id){
global $sqlcn;        
    $res=0;
    $sql="select count(*) as cnt from chat where readly=1 and from_id='$from_id' and to_id='$to_id'";
    //echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 1!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
	$res=$myrow["cnt"];
    };    
    return $res;
};
/**
 * Узнаем сколько у пользователя не прочитанных сообщений
 * @param type $to_id1
 */
function GetNotReadMessages($to_id){
global $sqlcn;            
    $res=0;
    $sql="select count(*) as cnt from chat where readly=1 and to_id='$to_id'";
    echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить список сообщений (GetNotReadMessages)!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
	$res=$myrow["cnt"];
    };    
    return $res;    
};
/**
 * Помечаем прочитанными все сообщения пользователя
 */
function ReadAllMessages($to_user_id,$from_user_id){
    global $sqlcn;
    echo "--помечаем все сообщения прочитанными!";
    $sql="update chat set readly=0 where readly=1 and from_id=$from_user_id and to_id='$to_user_id'";
    echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу обновить статус сообщений (ReadAllMessages)!: '.mysqli_error($sqlcn->idsqlconnection));    
};
function RefreshStatusesBase(){
 global $sqlcn,$user_array;        
    foreach ($user_array as $value) {
	$uid=$value["user_id"];
	$sql="update chat_users set online=1 where id=$uid";
	$result = $sqlcn->ExecuteSQL($sql) or die('Не могу обновить статусы: '.mysqli_error($sqlcn->idsqlconnection));
    };
};
function GetContactList($connect){
 global $sqlcn,$user_array;    
    // перед тем как отдать - обновим ка все статусы в базе...
    RefreshStatusesBase();
    echo "$connect\n";
    //var_dump($user_array);
    $me_id=$user_array[intval($connect)]["user_id"]; //самого себя не отсылаем
    $cnt_list=[];	
    if ($me_id!=""){    	
	    //online
	    //сначала получаем список участников НОС
	    //$sql="select * from chat_users where userid<>0 and id<>$me_id and online=1 order by online desc,lastmessage desc";
	    $sql="select users_profile.fio as name,chat_users.id,chat_users.userid,chat_users.lastmessage,chat_users.online from chat_users left join users_profile on users_profile.usersid=chat_users.userid where chat_users.userid<>0 and chat_users.id<>$me_id and chat_users.online=1 order by online desc,lastmessage desc";
	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 2!: '.mysqli_error($sqlcn->idsqlconnection));
	    while ($myrow = mysqli_fetch_array($result)) {
	      $cnt=[];   
	      $cnt["id"]=$myrow["id"];   
	      $cnt["name"]=$myrow["name"];   
	      $cnt["online"]=$myrow["online"];         
	      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
	      $cnt_list[]=$cnt;		  
	    };
	    //а теперь получаем список тех, с кем переписывался 
	    $sql="select chat_users.* from chat_users  where  online=1 and userid=0 group by chat_users.id order by online desc";
	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 3!: '.mysqli_error($sqlcn->idsqlconnection));
	    while ($myrow = mysqli_fetch_array($result)) {
	      $cnt=[];   
	      $cnt["id"]=$myrow["id"];   
	      $cnt["name"]=$myrow["name"];   
	      $cnt["online"]=$myrow["online"];   
	      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
	      $cnt_list[]=$cnt;		  
	    };
	    //offline
	    //сначала получаем список участников НОС
	    $sql="select users_profile.fio as name,chat_users.id,chat_users.userid,chat_users.lastmessage,chat_users.online from chat_users left join users_profile on users_profile.usersid=chat_users.userid where chat_users.userid<>0 and chat_users.id<>$me_id and chat_users.online=0 order by online desc,lastmessage desc";
	    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 4!: '.mysqli_error($sqlcn->idsqlconnection));
	    while ($myrow = mysqli_fetch_array($result)) {
	      $cnt=[];   
	      $cnt["id"]=$myrow["id"];   
	      $cnt["name"]=$myrow["name"];   
	      $cnt["online"]=$myrow["online"];         
	      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
	      $cnt_list[]=$cnt;		  
	    };
	    //а теперь получаем список тех, с кем переписывался 
	    $sql="select chat_users.* from chat_users inner join chat on chat.from_id=chat_users.id where  online=0 and userid=0  and chat.to_id=$me_id group by chat_users.id order by online desc,lastmessage desc";
	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист 5!: '.mysqli_error($sqlcn->idsqlconnection));
	    while ($myrow = mysqli_fetch_array($result)) {
	      $cnt=[];   
	      $cnt["id"]=$myrow["id"];   
	      $cnt["name"]=$myrow["name"];   
	      $cnt["online"]=0;   
	      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
	      $cnt_list[]=$cnt;		  
	    };    
    };
 return	$cnt_list;	
};
//обрабатываем общие для всех команды
function ParseCommon($message,$connect){
    global $connects,$sqlcn,$user_array;
    RefreshStatusesBase();
    if (isset($message->command)){

	//команда не мигать другим вкладкам браузер
	if ($message->command=="blinkstop"){
		$f=$message->from_user_id; //от кого сообщение?
		// Перебираем все его активные соединения
		foreach ($user_array as $value) {
		  if ($f==$value["user_id"]){
		    echo "---этому отправим: ".$value['user_id']."\n";  
		    foreach ($connects as $cvalue) {
			if (intval($cvalue)==intval($value['connect'])){
			    $exmessage=[];
			    $exmessage["command"]="StopBlink";
			    echo "---ушло:\n";
			    var_dump($exmessage);
			    fwrite($cvalue, encodeSocket(json_encode($exmessage)));			  
			};
		    };
		  };
		};	    
	};
	
	
	//команда на закрытие соединения
	if ($message->command=="showonlinetoconsole"){
	    echo "################# КТО ОНЛАЙН ##############################\n";
	    var_dump($user_array);
	    echo "###########################################################\n";
	};
	
	//команда на закрытие соединения
	if ($message->command=="close"){
	    unset($connects[ array_search($connect, $connects) ]);
	    onClose($connect);//вызываем пользовательский сценарий	
	    RefreshStatusesBase();
	};
	//получить историю переписки
	if ($message->command=="GetHistory"){
		$ret=GetHistory($message->chat_user_id,$message->chat_opponent_id);		
		$exmessage=[];
		$exmessage["command"]="GetHistory";
		$exmessage["History"]=$ret;
		echo "---ушло:\n";
		//var_dump($exmessage);
		fwrite($connect, encodeSocket(json_encode($exmessage)));		
	};	
	//пометить прочитанными сообщения переписки
	if ($message->command=="ReadAllMessages"){
		ReadAllMessages($message->to_user_id,$message->from_user_id);
	};	
	// принять сообщение, записать в базу и отослать копию собеседнику
	if ($message->command=="SendMessage"){
		$f=$message->from_user_id;
		$t=$message->to_user_id;
		$txt=$message->sendtext;
		//записываем в историю, помечаем не прочитанным получаетелем 
	    	$sql="insert into chat (id,from_id,to_id,dt,txt,readly) values (null,'$f','$t',now(),'$txt',1)";
		#echo "$sql\n";
		$result = $sqlcn->ExecuteSQL($sql);	    
		//и отправляем еще и получателю, если он "Онлайн"		
		echo "--перебираем список соединений получателя. Ищем получателя $t\n";
		var_dump($connects);
		var_dump($user_array);
		foreach ($user_array as $value) {
		  if ($t==$value["user_id"]){
		    echo "---этому отправим: ".$value['user_id']."\n";  
		    foreach ($connects as $cvalue) {
			if (intval($cvalue)==intval($value['connect'])){
			    $exmessage=[];
			    $exmessage["command"]="Message";
			    $exmessage["from_user_id"]=$f;
			    $exmessage["txt"]=$txt;
			    echo "---ушло:\n";
			    var_dump($exmessage);
			    fwrite($cvalue, encodeSocket(json_encode($exmessage)));			  
			};
		    };
		  };
		};
	};
	    //пользователь что-то пишет...
	    if ($message->command=="textwrite"){
		$from_user_id=$message->from_user_id; //кому он прислал		
		$to_user_id=$message->to_user_id; //кому он прислал		
		//и отправляем сообщение тому кому послано
		foreach ($user_array as $value) {
		  if ($to_user_id==$value["user_id"]){
		    echo "---этому отправим: ".$value['user_id']."\n";   
		    foreach ($connects as $cvalue) {
			if (intval($cvalue)==intval($value['connect'])){
			    $exmessage=[];
			    $exmessage["command"]="textwrite";
			    $exmessage["to_user_id"]=$to_user_id;			    		
			    $exmessage["from_user_id"]=$from_user_id;			    					    
			    fwrite($cvalue, encodeSocket(json_encode($exmessage)));			  
			};
		    };
		  };
		};		
	    };	    
	
	//делаем соответствие id пользователя и его connect, заодно помечаем что юзер "онлайн"
	//ну и до кучи сообщаем если для него не прочиатнные сообщения?
	if ($message->command=="iamonline"){		  
		$user_array[intval($connect)]["user_id"]=$message->chat_user_id;
		$user_array[intval($connect)]["connect"]=$connect;
		OnlineStatus($message->chat_user_id);
		$exmessage=[];
		$exmessage["command"]="iamonline";
		$exmessage["messages"]=GetNotReadMessages($message->chat_user_id);
		echo "---ушло:\n";
		var_dump($exmessage);
		fwrite($connect, encodeSocket(json_encode($exmessage)));			  
	};	
	//команда на закрытие соединения
	if ($message->command=="close"){
	    unset($connects[ array_search($connect, $connects) ]);
	    onClose($connect);//вызываем пользовательский сценарий	
	};	
	//получить список контактов
	if ($message->command=="GetContactList"){
		$exmessage=[];
		$exmessage["command"]="GetContactList";
		$exmessage["result"]=GetContactList($connect);
		echo "---ушло:\n";
		var_dump($exmessage);
		fwrite($connect, encodeSocket(json_encode($exmessage)));			    
	};	
    };
};