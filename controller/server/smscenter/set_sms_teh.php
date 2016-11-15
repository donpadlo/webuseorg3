<?php
include_once("class/cconfig.php");		// загружаем классы настроек

$teh_sms=_GET("teh_sms");
$mode=_GET("mode");

$tsms=new Tcconfig();

if ($mode=="set"){
    echo "<div class=\"alert alert-success\">Отправитель для технических СМС установлен!</div>";
    $tsms->SetByParam("settehsmsagent", $teh_sms);    
} else {
    $teh=$tsms->GetByParam("settehsmsagent");    
    echo $teh;
};

?>