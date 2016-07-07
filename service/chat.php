#!/usr/local/bin/php
<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

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
include_once(WUO_ROOT.'/Socket.php'); // Загружаем рутинные функции для чата
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
$connects = array();
$users=array();
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
	    OfflineStatus($connect);
            continue;
        }

        onMessage($connect, $data,$info);//вызываем пользовательский сценарий
    }
}
fclose($server);

/**
 * Возвращает заполненный массив логин-от, логин-кому, текст, дата
 * @global type $sqlcn
 * @param type $from_id
 * @param type $to_id
 * @param type $dt
 * @param type $txt
 * @return type
 */
function PrepareTextToSendChat($fd,$txt,$dt,$tm){
  global $sqlcn;  
  if ($dt==""){$dt=date("H:i");} else {
   $dt=MySQLDateTimeToDateTimeNoTime($dt);   
  };
    $ret=[];
    //получаем логин отправителя
    $sql="select * from chat_users where id=$fd";
    echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql);
    while($row = mysqli_fetch_array($result)) {
	$name=$row["name"];
    };
   $txt=str_replace("\n", "</br>", $txt);
   if ($tm=="from"){$tm="chatloginfrom";} else {$tm="chatloginto";}
   $ret["txt"]="<span class='chatdt'>[$dt]</span><span class='$tm'>$name:</span><span class='chatchat'>".$txt."</span></br>";
 return $ret;
};

function WhoNOCOnline(){
  $res="";
  global $sqlcn,$users,$chat_admins;   
  $ar=explode(";",$chat_admins);
  foreach ($ar as $user) {
     $sql="select * from chat_users where userid=$user and online=1"; 
     echo "$sql\n";
     $result = $sqlcn->ExecuteSQL($sql);
     while($row = mysqli_fetch_array($result)) {
	$res=$row["id"];
     };     
     if ($res!="") break;
  };
  return $res;
};
//пользовательские сценарии:

function onOpen($connect, $info) {
    echo "open\n";
    var_dump($info);
    $msg=[];
    $msg["command"]="Hello";
    fwrite($connect, encodeSocket(json_encode($msg)));
}
function OfflineStatus($connect){
    global $sqlcn,$users;
    //var_dump($connect);
    foreach ($users as $key => $value) {
	if ($value["connect"]==$connect){
	    $fromuserid=$value["from_user_id"];
	    $sql="update chat_users set online=0 where id=$fromuserid";
	    echo "$sql\n";
	    unset($users[$fromuserid]);
	    $result = $sqlcn->ExecuteSQL($sql);
	};
    }            
};
function OnlineStatus($connect){
    global $sqlcn,$users;
    foreach ($users as $key => $value) {
	if ($value["connect"]==$connect){
	    $fromuserid=$value["from_user_id"];
	    $sql="update chat_users set online=1 where id=$fromuserid";
	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql);
	};
    }
    //RefreshContactList($connect); //обновляем контакт лист у пользователей NOC
};
function RefreshContactList($to_id){
    global $sqlcn,$users;    
    foreach ($users as $key => $value) {
	if ($value["from_user_id"]==$to_id){
		$exmessage=[];
		$exmessage["command"]="RefreshContactList";
		$exmessage["result"]=GetContactList($to_id);
		fwrite($value["connect"], encodeSocket(json_encode($exmessage)));					
		echo "--выслал команду обновления списка контактов ",$value["connect"],$value["from_user_id"];	    
	};
    }
    
};
function UpdateLastMessage($connect){
    global $sqlcn,$users;
    foreach ($users as $key => $value) {
	if ($value["connect"]==$connect){
	    $fromuserid=$value["from_user_id"];
	    $sql="update chat_users set lastmessage=now() where id=$fromuserid";
	    echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql);
	};
    }
};
function YetNoRead($from_id,$to_id){
global $sqlcn;        
    $res=0;
    $sql="select * from chat where readly=1 and from_id='$from_id' and to_id='$to_id'";
    echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
	$res=1;
    };    
    return $res;
};
function GetContactList($me_id){
 global $sqlcn,$users;    
    $cnt_list=[];		
    //online
    //сначала получаем список участников НОС
    //$sql="select * from chat_users where userid<>0 and id<>$me_id and online=1 order by online desc,lastmessage desc";
    $sql="select users_profile.fio as name,chat_users.id,chat_users.userid,chat_users.lastmessage,chat_users.online from chat_users left join users_profile on users_profile.usersid=chat_users.userid where chat_users.userid<>0 and chat_users.id<>$me_id and chat_users.online=1 order by online desc,lastmessage desc";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
      $cnt=[];   
      $cnt["id"]=$myrow["id"];   
      $cnt["name"]=$myrow["name"];   
      $cnt["online"]=$myrow["online"];         
      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
      $cnt_list[]=$cnt;		  
    };
    //а теперь получаем список тех, с кем переписывался 
    $sql="select chat_users.* from chat_users inner join chat on chat.from_id=chat_users.id where  online=1 and userid=0  and chat.to_id=$me_id group by chat_users.id order by online desc,lastmessage desc";
    echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист!: '.mysqli_error($sqlcn->idsqlconnection));
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
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист!: '.mysqli_error($sqlcn->idsqlconnection));
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
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу получить контакт лист!: '.mysqli_error($sqlcn->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
      $cnt=[];   
      $cnt["id"]=$myrow["id"];   
      $cnt["name"]=$myrow["name"];   
      $cnt["online"]=$myrow["online"];   
      $cnt["read"]=YetNoRead($myrow["id"],$me_id);
      $cnt_list[]=$cnt;		  
    };
    
 return	$cnt_list;	
};
function onClose($connect) {
    //обновляем статус пользователя в оффлайн
    OfflineStatus($connect);    
    echo "close\n";
}

function onMessage($connect, $data,$info) {    
    global $sqlcn,$users;
    $data=decodeSocket($data);   
    $message=json_decode($data["payload"]);
    echo "--пришло:\n";
    var_dump($message);
    //отдаем клиенту новый ID
    if (isset($message->command) and isset($message->client)){
	//обновляю онлайн статус	
	//если клиент - клиент чата поддержки
	if ($message->client=="client"){	    
	    if ($message->command=="Get_new_id_client"){
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
		$exmessage["command"]="Put_new_id_client";
		$exmessage["result"]=$user_id;
		$exmessage["name"]=$username;
		fwrite($connect, encodeSocket(json_encode($exmessage)));			
	    };
	    //отвечаем, есть ктонить онлайн?
	    if ($message->command=="Online"){
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;	    		
		OnlineStatus($connect);
		$exmessage=[];

		$exmessage["result"]="yes";
		$exmessage["to_user_id"]=WhoNOCOnline();//выбираем того, кто в NOC онлайн
		if ($exmessage["to_user_id"]!=""){$exmessage["command"]="Online";} else {
		 $exmessage["command"]="Offline";
		};
		fwrite($connect, encodeSocket(json_encode($exmessage)));	
	    };
	};
	//если клиент - кто-то из NOC
	if ($message->client=="noc"){
	    if ($message->command=="GetContactList"){		
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;	    		
		$users[$message->from_user_id]["client"]=$message->client;
		OnlineStatus($connect);	
		$exmessage=[];
		$exmessage["command"]="GetContactList";
		$exmessage["result"]=GetContactList($message->from_user_id);
		fwrite($connect, encodeSocket(json_encode($exmessage)));			
	    };

	};    
	//общие для всех обработки сообщений
	    //обновление списка контактов собеседника
	    if ($message->command=="RefreshContactList"){
		RefreshContactList($message->to_user_id);
	    };
	    
	    //отвечаем на ping
	    if ($message->command=="ping"){
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;		
		$exmessage=[];
		$exmessage["command"]="ping";
		$exmessage["result"]="pong";
		fwrite($connect, encodeSocket(json_encode($exmessage)));	
	    };
	    //помечаем, что сообщения пользователем прочитаны..
	    if ($message->command=="AllMessagesRead"){
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;		
		$users[$message->from_user_id]["client"]=$message->client;		
		$to_user_id=$message->to_user_id;	
		$from_user_id=$message->from_user_id;
		$sql="update chat set readly=0 where from_id=$to_user_id and to_id=$from_user_id";
		echo "$sql\n";
		$result = $sqlcn->ExecuteSQL($sql);
	    }
	    //получаем историю сообщений пользователя
	    if ($message->command=="GetHistory"){
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;		
		$users[$message->from_user_id]["client"]=$message->client;		
		$to_user_id=$message->to_user_id;
		$from_user_id=$message->from_user_id;
		$sql="select * from chat where (from_id='$from_user_id' and to_id='$to_user_id') or (from_id='$to_user_id' and to_id='$from_user_id') order by dt ";		
		$result = $sqlcn->ExecuteSQL($sql);
		$exmessage=[];
		$exmessage["txt"]="";
		while($row = mysqli_fetch_array($result)) {
		    $frid=$row["from_id"];
		    $toid=$row["to_id"];
		    $txt=$row["txt"];
		    $dt=$row["dt"];
		    if ($frid==$message->from_user_id){$tm="from";} else {$tm="to";};
   	            $pretext=PrepareTextToSendChat($frid,$txt,$dt,$tm);
		    $exmessage["txt"]=$exmessage["txt"].$pretext["txt"];				    
		}; 	
		//если истории нет, то выводим приветсвие!
		if ($exmessage["txt"]==""){
   	            $pretext=PrepareTextToSendChat($to_user_id,$chat_wellcome,"","from");
		    $exmessage["txt"]=$exmessage["txt"].$pretext["txt"];				    		    
		};
		$exmessage["command"]="GetHistory";
		fwrite($connect, encodeSocket(json_encode($exmessage)));			    
		echo "--send: ",json_encode($exmessage);		
	    };
	    //пользователь чтото прислал
	    if ($message->command=="SendMessage"){
		$users[$message->from_user_id]["from_user_id"]=$message->from_user_id;
		$users[$message->from_user_id]["connect"]=$connect;
		UpdateLastMessage($connect);
		$sendtext=$message->sendtext;	//текст который прислал пользователь	
		$from_user_id=$message->from_user_id; //кому он прислал		
		$to_user_id=$message->to_user_id; //кому он прислал		
		$sendtext=mysqli_real_escape_string($sqlcn->idsqlconnection,$sendtext);
		$sendtext=strip_tags($sendtext);
		$sql="insert into chat (id,from_id,to_id,dt,txt,readly,session) values (null,'$from_user_id','$to_user_id',now(),'$sendtext',1,'')";
		echo "$sql\n";
		$result = $sqlcn->ExecuteSQL($sql);
		//обновляем окно чата того кто прислал
		$pretext=PrepareTextToSendChat($from_user_id,$sendtext,"","from");
		$exmessage=[];
		$exmessage["command"]="AddEchoMessageToChat";
		$exmessage["from_user_id"]=$from_user_id;		
		$exmessage["txt"]=$pretext["txt"];		
		fwrite($connect, encodeSocket(json_encode($exmessage)));	
		//и отправляем сообщение тому кому послано
		    foreach ($users as $key => $value) {
		    if ($value["from_user_id"]==$to_user_id){
			    RefreshContactList($to_user_id);
			    $pretext=PrepareTextToSendChat($from_user_id,$sendtext,"","to");
			    $exmessage=[];
			    $exmessage["command"]="AddEchoMessageToChat";
			    $exmessage["from_user_id"]=$from_user_id;
			    $exmessage["to_user_id"]=$to_user_id;
			    $exmessage["txt"]=$pretext["txt"];		
			    fwrite($value["connect"], encodeSocket(json_encode($exmessage)));				
			};
		    }
	    };	
	    //пользователь что-то пишет...
	    if ($message->command=="textwrite"){
		$from_user_id=$message->from_user_id; //кому он прислал		
		$to_user_id=$message->to_user_id; //кому он прислал		
		//и отправляем сообщение тому кому послано
		    foreach ($users as $key => $value) {
		    if ($value["from_user_id"]==$to_user_id){
			    $exmessage=[];
			    $exmessage["command"]="textwrite";			    
			    $exmessage["to_user_id"]=$to_user_id;			    		
			    $exmessage["from_user_id"]=$from_user_id;			    					    
			    fwrite($value["connect"], encodeSocket(json_encode($exmessage)));				
			};
		    }		
	    };	    
    };
    echo "--вышли из цикла\n";    
}