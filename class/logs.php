<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tlog {
    
// записываем что-то в лог
function Save(){
//loglevel,txt,cost,billingid
global $sqlcn,$user;
 for ($i=1;$i<=func_num_args();$i++){
  if ($i=1){$loglevel=func_get_arg(0);};
  if ($i=2){
      $txt=@func_get_arg(1);
      $len=  strlen($txt);
      //echo "$len!";
      if ($len>250){$txt="Слишком большая строка!";};
  };
  if ($i=3){$cost=@func_get_arg(2);};
  if ($i=4){$billingid=@func_get_arg(3);};
 };
    if (isset($cost)==false){$cost=0;};
    if (isset($billingid)==false){$billingid='non';};
    if (isset($cost)==false){$cost=0;};
    $userid="";
    if (isset($user->id)==true){$userid=$user->id;};
    $sql="INSERT INTO lanblog (loglevel,dt,txt,userid,billingid,cost) VALUES ('$loglevel',now(),'$txt','$userid','$billingid','$cost')";
    //echo "$sql\n";
    $result = $sqlcn->ExecuteSQL($sql);        
    if ($result==''){die('Неверный запрос Tlog.Save: ' . mysqli_error($sqlcn->idsqlconnection));}
}



}
