<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

if (isset($_GET["config"])=="save"){
    $loginsms=PostDef("loginsms");
    $passsms=PostDef("passsms");   
    $smsdiffres=PostDef("smsdiffres");   
    $result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='smstowerlogin'");        
    $row = mysqli_fetch_array($result);
    $cnt=count($row);
    // или добавляем настройки или сохраняем
    if ($cnt==0) {
        //echo "$loginsms!$passsms!";
        $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('smstowerlogin','$loginsms')");        
        $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('smstowerpass','$passsms')");                
        $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('smsdiffres','$smsdiffres')");                
    } else  {
      $result = $sqlcn->ExecuteSQL("UPDATE  config_common SET valueparam='$loginsms' WHERE nameparam='smstowerlogin' ");          
      $result = $sqlcn->ExecuteSQL("UPDATE  config_common SET valueparam='$passsms' WHERE nameparam='smstowerpass' ");          
      $result = $sqlcn->ExecuteSQL("UPDATE  config_common SET valueparam='$smsdiffres' WHERE nameparam='smsdiffres' ");          
    };
        
}
?>