<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

$save = _POST('save');
$ip_message_port = _POST('ip_message_port');
$ip_message_server = _POST('ip_message_server');
$message_wss_url = _POST('message_wss_url');

if ($save=="true"){
  $cfg->SetByParam("message-port", $ip_message_port);  
  $cfg->SetByParam("message-server", $ip_message_server);  
  $cfg->SetByParam("message-wss-url", $message_wss_url);  
  echo "Настройки сохранены!";
};

if ($save=="false"){
    $cfgarr=array();
    $cfgarr["message-port"]=$cfg->GetByParam("message-port");  
    $cfgarr["message-server"]=$cfg->GetByParam("message-server");  
    $cfgarr["message-wss-url"]=$cfg->GetByParam("message-wss-url");      
    echo json_encode($cfgarr);
};