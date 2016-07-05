<?php

include_once("class/cconfig.php");                    // загружаем первоначальные настройки
$text=  _POST("text");
$bu=new Tcconfig;

$text=mysqli_real_escape_string($sqlcn->idsqlconnection,$text);

$htmlentry=$bu->SetByParam("htmlentry",$text); //соответствие

echo "true";
?>