#!/usr/local/bin/php
<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
// 
// Сей файл - это серверная часть сервиса сообщений
// version 1.00

$debug=true;

define('WUO_ROOT', dirname(__FILE__));

include_once(WUO_ROOT.'/../config.php');
include_once(WUO_ROOT.'/../class/sql.php'); // Класс работы с БД
include_once(WUO_ROOT.'/../class/config.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/cconfig.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/users.php'); // Класс работы с пользователями
include_once(WUO_ROOT.'/../class/mod.php'); // Класс работы с модулями

// Загружаем все что нужно для работы движка
include_once(WUO_ROOT.'/../inc/connect.php'); // Соединяемся с БД, получаем $mysql_base_id
include_once(WUO_ROOT.'/../inc/config.php'); // Подгружаем настройки из БД, получаем заполненый класс $cfg
include_once(WUO_ROOT.'/../inc/functions.php'); // Загружаем функции

include_once(WUO_ROOT.'/message_func.php'); // Загружаем рутинные функции 

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("message", "Сервис сообщений", "Грибов Павел"); 
if ($md->IsActive("message")==0) {
  die("-модуль не включен в настройках\n");
};
unset($md);

//читаю настройки чата
    $ip_message_port = $cfg->GetByParam("message-port");
    $ip_message_server = $cfg->GetByParam("message-server");
    $message_wss_url = $cfg->GetByParam("message-wss-url"); //        
    
if ($ip_message_port==""||$ip_message_server==""||$message_wss_url==""){
	die("-не выставлены настройки модуля в админке\n");    
};


$socket = stream_socket_server("tcp://$ip_message_server:$ip_message_port", $errno, $errstr);

if (!$socket) {die("$errstr ($errno)\n");};

echo "-демон сервера сообщений стартовал $ip_message_server:$ip_message_port \n";

$connects = array();	//массив открытых соединений
$users_online=array();	//массив онлайн пользователей

while (true) {
    //формируем массив прослушиваемых сокетов:
    $read = $connects;
    $read []= $socket;
    $write = $except = null;
    if (!stream_select($read, $write, $except, null)) {
	//ожидаем сокеты доступные для чтения (без таймаута)
        break;
    };
    if (in_array($socket, $read)) {
	//есть новое соединение
        //принимаем новое соединение и производим рукопожатие:
        if (($connect = stream_socket_accept($socket, -1)) && $info = handshakeSocket($connect)) {
	    echo "-ура! есть новый коннект..";
            $connects[] = $connect;//добавляем его в список необходимых для обработки
            onOpen($connect, $info);//вызываем пользовательский сценарий
        }
        unset($read[ array_search($socket, $read) ]);
    };

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
    global $users_online,$debug;
    echo "---close $connect\n";
    foreach ($users_online as $key => $value) {
	if ($value["connect"]==$connect){
	    unset($users_online[$key]);
	};
    };
    if ($debug==true){
	//var_dump($users_online);
    };
};

function onOpen($connect, $info) {
    global $users_online,$debug;
    echo "---open $connect\n";
    $id=GetRandomId(10);
    $users_online[$id]["connect"]=$connect;
    if ($debug==true){
	//var_dump($users_online);
    };    
    //узнаем кто постучался?
    fwrite($connect, encodeSocket(json_encode(array("command"=>"whois"))));
    
};

function onMessage($connect, $data,$info) {    
    global $users_online,$debug;
    $data=decodeSocket($data);   
    $message=json_decode($data["payload"]);
    echo "---пришло: \n";
    var_dump($message); 
    if (isset($message->command)){
	switch ($message->command) {
	    //пришла команда с идентификаций пользователя NOC
	    case "iam":
		    $ret2=UserUpdateMassive($connect,$message->user_id);
		    //говорим "Привет"
		    //fwrite($connect, encodeSocket(json_encode(array("command"=>"message","type"=>"success","sticky"=>"true","title"=>"Приветствие","body"=>"Привет ".$ret2["fio"]))));
	    break;
	    //кто-то спрашивает какие пользователи "онлайн"
	    case "whois_online":
		    $ret=WhoIsOnline();		    
		    fwrite($connect, encodeSocket(json_encode(array("command"=>"send_id_users_online","usersid"=>$ret))));		
	    break;	
	    //пришел пакет с cronus о состоянии заббикса. Задача передать конкретному пользователю в НОС!
	    case "zabbix_packet_from_cronus":
		SendZabbixInfoToNoc($message);
	    break;
	    // пришел пакет о состоянии дел в SBSS
	    case "sbss_packet_from_cronus":
		SendSBSSInfoToNoc($message);
	    break;	
	    //пришел пакет от обработчика виртуальной АТС о входящем звонке
	    case "call_from_cronus":
		echo "--пришел звонок от vats!\n";
		SendVatsCallToNoc($message);
	    break;
	
	    default:
		break;
	}
    };
};
function SendVatsCallToNoc($message){
 global $users_online;        
 foreach ($users_online as $user) {
     //если этот пользователь онлайн, то посылаем ему пакет с уведомлением...
     if (isset($user["userid"])==true){
	if (in_array($user["userid"], $message->to_user)==true){
	    echo "--отсылаю звонок НОС!\n";
	    fwrite($user["connect"], encodeSocket(json_encode(array("command"=>"call_to_noc","packet"=>$message->packet))));		
	};     
     };
 };    
};
function SendSBSSInfoToNoc($message){
 global $users_online;        
 foreach ($users_online as $user) {
     if (isset($user["userid"])==true){
	if ($user["userid"]==$message->to_user){
	    fwrite($user["connect"], encodeSocket(json_encode(array("command"=>"sbss_packet_to_noc","packet"=>$message->packet))));		
	};
     };
 };
    
};
function SendZabbixInfoToNoc($message){
 global $users_online;        
 foreach ($users_online as $user) {
     if (isset($user["userid"])==true){
	if ($user["userid"]==$message->to_user){
	    fwrite($user["connect"], encodeSocket(json_encode(array("command"=>"zabbix_packet_to_noc","packet"=>$message->packet))));		
	};
     };
 };
};
function WhoIsOnline(){
global $users_online;        
    $ret=array();
    foreach ($users_online as $key => $value) {
	if (isset($users_online[$key]["userid"])==true){
	    if (in_array($users_online[$key]["userid"],$ret)==false){
		$ret[]=$users_online[$key]["userid"];
	    };
	};
    };
    return $ret;
};
function UserUpdateMassive($connect,$user_id){
global $users_online,$sqlcn,$debug;    
    $ret="";
    foreach ($users_online as $key => $value) {
	if ($value["connect"]==$connect){
	    $users_online[$key]["userid"]=$user_id;
	    $sql="select users.login,users.mode,users_profile.fio from users inner join users_profile on users_profile.usersid=users.id where users.id=$user_id";      
	    //echo "$sql\n";
	    $result = $sqlcn->ExecuteSQL($sql);
	    while($row = mysqli_fetch_array($result)) {	
		$users_online[$key]["login"]=$row["login"];		
		$users_online[$key]["fio"]=$row["fio"];		
		$users_online[$key]["mode"]=$row["mode"];		
		$ret=$users_online[$key];
	    };
	};    
    };
    //var_dump($users_online);
    return $ret;
};

