<?php
if (isset($_GET["config"])=="save"){
    $checksmsdiff=PostDef("checksmsdiff");
    $emaillanbadmin=PostDef('emaillanbadmin');        
    
    $backupbmysqlhost=PostDef('backupbmysqlhost');    
    $backupbmysqllogin=PostDef('backupbmysqllogin');    
    $backupbmysqlpass=PostDef('backupbmysqlpass');    
    $backupbmysqlbase=PostDef('backupbmysqlbase');    
    
    
  $zz=new Tcconfig;
  $zz->SetByParam('checksmsdiff', $checksmsdiff);
  $zz->SetByParam('emaillanbadmin', $emaillanbadmin);  
  
  $zz->SetByParam('backupbmysqlhost', $backupbmysqlhost); 
  $zz->SetByParam('backupbmysqllogin', $backupbmysqllogin); 
  $zz->SetByParam('backupbmysqlpass', $backupbmysqlpass); 
  $zz->SetByParam('backupbmysqlbase', $backupbmysqlbase); 
}
?>