<?php
include_once('class/logs.php'); 

$lg=new Tlog();

$ip=  _GET("ip");
$pin=  _GET("pin");
$status=  _GET("status");

$cont = file_get_contents("http://$ip/?command=99&setpin=$pin&setpinstatus=$status");	    
$cont=str_replace("<!DOCTYPE HTML>", "", $cont);	    	    	    
$cont=json_decode($cont);
echo $cont->status;

$lg->Save(101,"--пользователь ".$user->login." сделал манипуляции с розеткой $ip, $pin, $status");
unset($lg);
