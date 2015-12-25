<?php
$cfg->navbar[]="<a href=index.php>Главная</a>";
$cfg->navbar[]="LanBilling";
$cfg->navbar[]="Настройки";
$cfg->navbar[]="Общие настройки";

if (isset($_GET["config"])=="save"){
    $checksmsdiff=PostDef("checksmsdiff");
    $emaillanbadmin=PostDef('emaillanbadmin');        
  $zz=new Tcconfig;
  $zz->SetByParam('checksmsdiff', $checksmsdiff);
  $zz->SetByParam('emaillanbadmin', $emaillanbadmin);  
}
?>