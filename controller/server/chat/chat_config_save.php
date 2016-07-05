<?php


// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("inc/lbfunc.php");			// загружаем функции LB
include_once ("class/cconfig.php");                     // загружаем функции работы с настройками

$ip_chat_server=  _POST("ip_chat_server");
$ip_chat_port=_POST("ip_chat_port");
$chat_admins=_POST("chat_admins");
$ssl_pem=_POST("ssl_pem");

$ssl_pass=_POST("ssl_pass");
$chat_wellcome=_POST("chat_wellcome");
$chat_wss_url_noc=_POST("chat_wss_url_noc");
$chat_wss_url_help=_POST("chat_wss_url_help");

$vl=new Tcconfig();	

$vl->SetByParam("ip-chat-port", $ip_chat_port);
$vl->SetByParam("ip-chat-server", $ip_chat_server);
$vl->SetByParam("chat-admins", $chat_admins);
$vl->SetByParam("ssl-pem", $ssl_pem);

$vl->SetByParam("ssl-pass", $ssl_pass);
$vl->SetByParam("chat-wellcome", $chat_wellcome);
$vl->SetByParam("chat-wss-url-noc", $chat_wss_url_noc);
$vl->SetByParam("chat-wss-url-help", $chat_wss_url_help);

 echo "Данные сохранены!";
 
?>