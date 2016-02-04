<?php

/* 
 * Функции наиболее часто используемые в модуле LanBilling
 */

//проверяем имеет ли доступ пользователь к этой базе
function RulesBilling($user_id,$blibaseid){
global $sqlcn;    
$res=false;
$result = $sqlcn->ExecuteSQL("SELECT * FROM lanb_rules_billing_servers WHERE userid='$user_id' and blibaseid='$blibaseid'");                
while ($myrow = mysqli_fetch_array($result)){
    $res=true;
};
return $res;   
};

//проверяем имеет ли доступ пользователь к этой базе
function RulesFerma($user_id,$blibaseid,$fermaid){
global $sqlcn;    
$res=false;
$result = $sqlcn->ExecuteSQL("SELECT * FROM lanb_rules_billing_ferma WHERE userid='$user_id' and billingid='$blibaseid' and fermaid='$fermaid'");                
while ($myrow = mysqli_fetch_array($result)){
    $res=true;
};
return $res;   
};

//проверяем имеет ли доступ пользователь к этой базе
function RulesDevices($user_id,$devid){
global $sqlcn;    
$res=false;
$result = $sqlcn->ExecuteSQL("SELECT * FROM lanb_rules_billing_dev WHERE user_id='$user_id' and devid='$devid'");                
while ($myrow = mysqli_fetch_array($result)){
    $res=true;
};
return $res;   
};

function GetSMSSender($billing_id,$default){
global $sqlcn;    
    $sender=$default;
    $result = $sqlcn->ExecuteSQL("SELECT * FROM lbcfg WHERE id='$billing_id'");                
    while ($myrow = mysqli_fetch_array($result)){
        $sender=$myrow["smssender"];
    };
    return $sender;       
};

function GetHexColorById($id){    
    switch ($id) {
        case 1: $color="ff0000";break;
        case 2: $color="f8c411";break;
        case 3: $color="ffff00";break;
        case 4: $color="00ff00";break;
        case 5: $color="0000ff";break;
        case 6: $color="800080";break;
        case 7: $color="8c1f1f";break;
        case 8: $color="000000";break;
        case 9: $color="ffffff";break;
        case 10: $color="c0c0c0";break;
        case 11: $color="1199f8";break;
        case 12: $color="FF91A4";break;
        case 13: $color="00FFFF";break;
        case 14: $color="808000";break;
        case 15: $color="b5b771";break;
        case 16: $color="d2d4a5";break;
        default:$color=false;break;
    }    
    return $color;      
};
function GetTextColorById($id){    
    switch ($id) {
        case 1: $color="Красный";break;
        case 2: $color="Оранжевый";break;
        case 3: $color="Желтый";break;
        case 4: $color="Зеленый";break;
        case 5: $color="Синий";break;
        case 6: $color="Фиолетовый";break;
        case 7: $color="Коричневый";break;
        case 8: $color="Черный";break;
        case 9: $color="Белый";break;
        case 10: $color="Серый";break;
        case 11: $color="Бирюзовый (Голубой)";break;
        case 12: $color="Розовый";break;
        case 13: $color="Салатовый";break;
        case 14: $color="Оливковый";break;
        case 15: $color="Бежевый";break;
        case 16: $color="Натуральный (Прозрачный)";break;
        default:$color=false;break;
    }    
    return $color;      
};
//////////
// Блок расчетов предварительных платежей
//////////
function GetBlockedDayByDay($vg_id,&$user,$lb){
   $sql="select blocked from vgroups where vg_id=$vg_id";
   $res2 = $lb->ExecuteSQL($sql);
   while ($row2 = mysqli_fetch_array($res2)) {$blocked=$row2["blocked"];};
   for ($i=1; $i<=(date("t")); $i++) {
       $user[$i]["blocked"] = $blocked;
       $user[$i]["pay_day"]=0; //платежи за день
   };     
    // Прошедшие блокировки
    $sql = "SELECT timefrom,timeto,block_type FROM vg_blocks WHERE vg_id=$vg_id AND ((DATE(timefrom) BETWEEN '".date("Y-m-01")."' AND '".date("Y-m-t")."') OR (DATE(timeto) BETWEEN '".date("Y-m-01")."' AND '".date("Y-m-t")."'))";        
    $res2 = $lb->ExecuteSQL($sql);
    while ($row2 = mysqli_fetch_array($res2)) { 
        if (date("m-Y", strtotime($row2["timefrom"]))==date("m-Y")) {
                $t1 = (int)intval(date("d", strtotime($row2["timefrom"])));
            } else {
                $t1 = 0;
            }
        if (date("m-Y", strtotime($row2["timeto"]))==date("m-Y")) {
                $t2 = (int)intval(date("d", strtotime($row2["timeto"])))-1;
            } else {
                $t2 = intval(date("t"));
            }
        if ($row2["timefrom"]=="0000-00-00 00:00:00") $t1 = 1;
        if (date("Y",strtotime($row2["timeto"]))=="9999" && $row2["block_type"]==10) {
                $t2 = date("t");
            }
            elseif (date("Y",strtotime($row2["timeto"]))=="9999" && $row2["block_type"]!=10) $t2 = (int)date("t");
        for ($i=$t1; $i<=$t2; $i++) $user[$i+0]["blocked"] = $row2["block_type"];
    };
    // Запланированные блокировки
	    $sql = "SELECT change_time,blk_req FROM vg_blocks_rasp WHERE vg_id=$vg_id AND DATE(change_time) BETWEEN '".date("Y-m-01")."' AND '".date("Y-m-t")."'";	    
	    $res2 = $lb->ExecuteSQL($sql);	    
	    if (mysqli_num_rows($res2)!=0){
		    while ($row2 = mysqli_fetch_array($res2)){
			//echo $row2["timefrom"]." - ".$row2["timeto"]."<br>";
			if (date("m-Y", strtotime($row2["change_time"]))==date("m-Y")){
				$t1 = (int)intval(date("d", strtotime($row2["change_time"])));
			    }
			$t2 = intval(date("t"));
			for ($i=$t1; $i<=$t2; $i++)
			    $user[$i]["blocked"] = $row2["blk_req"];
		    };
		};      
   return $user;
};
function GetTarsDayByDay($vg_id,&$user,$lb){
//тариф по умолчанию    
$sql="select tar_id from vgroups where vg_id=$vg_id";
$res2 = $lb->ExecuteSQL($sql);
while ($row2 = mysqli_fetch_array($res2)) {$tar_id=$row2["tar_id"];};
//по умалчанию заполняем текущим тарифом
for ($i=1; $i<=date("t"); $i++) {$user[$i]["tar_id"] = $tar_id;};
//а теперь, если вдруг были переходы, то считаем что начисления уже были до дня перехода
$sql = "SELECT * FROM tarifs_history WHERE vg_id=$vg_id AND DATE(rasp_time) BETWEEN '".date("Y-m-01")."' AND '".date("Y-m-t")."'";
$res2 = $lb->ExecuteSQL($sql);
        $t1 = 1;
        while ($row2 = mysqli_fetch_array($res2)) { 
            $t2 = date("d", strtotime($row2["rasp_time"]));            
            for ($i=$t1; $i<$t2; $i++) {
                $user[$i]["tar_id"] = $row2["tar_id_old"];                           
                $user[$i]["pay_already"] =-1; //начисления за это время уже сделано!
            };
            for ($i=$t2; $i<=date("t"); $i++){                
                $user[$i]["tar_id"] = $row2["tar_id_new"];
            };    
            $t1 = $t2;
        }; 
 return $user;     
};
function GetNextOneSpis($vg_id,$user,$lb){
    $sql="select usbox_services.timefrom,usbox_services.tar_id,usbox_services.cat_idx,usbox_services.mul from usbox_services inner join tarifs on tarifs.tar_id=usbox_services.tar_id where timefrom between now() and ADDDATE('".date("Y-m-t")."',interval 1 day) and vg_id=$vg_id";
    $res2 = $lb->ExecuteSQL($sql);
    while ($row2 = mysqli_fetch_array($res2)) { 
      $day = (int)intval(date("d", strtotime($row2["timefrom"])));
      $cat_idx=$row2["cat_idx"];
      $tar_id=$row2["tar_id"];
      $mul=$row2["mul"];
      $sql="select above from categories where tar_id=$tar_id and cat_idx=$cat_idx and common=0";
        $res3 = $lb->ExecuteSQL($sql);
        while ($row3 = mysqli_fetch_array($res3)) { 
          $rent=$row3["above"]*$mul;	    //разовый платеж
          $user[$day]["pay_day"]=$user[$day]["pay_day"]+$rent; //Добавляем списание в этот день
          $user[$day]["type_rent"]="usl";
        };
      
    };    
    return $user;    
};
function GetPeriodicSpis($vg_id,$user,$lb){
 $bday=0;
 for ($i=1; $i<=date("t"); $i++) {
     if (($user[$i]["blocked"]==0) or ($users[$i]["blocked"]==1)) {$bday++;};     
 };
 if ($bday>0){     
    //узнаем : услуга или интернет
    $type=0;    
    $sql="select settings.type from vgroups inner join settings on settings.id=vgroups.id  where vgroups.vg_id=$vg_id";
    $res2 = $lb->ExecuteSQL($sql);
    while ($row2 = mysqli_fetch_array($res2)) { $type=$row2["type"];};
    //проходим каждый день и начисляем а/п за этот день
    for ($i=1; $i<=date("t"); $i++) {
       if (isset($user[$i]["pay_already"])==false){ //если не было переходов с тарифа на тариф, то начисляем
	   $tar_id=$user[$i]["tar_id"];
	   if ($type==6){
	       $sql="select rent from tarifs where tar_id=$tar_id";
	   } else {
	       $sql="select sum(usbox_services.mul*categories.above) as rent from usbox_services inner join categories on categories.tar_id=usbox_services.tar_id where usbox_services.vg_id=$vg_id and usbox_services.tar_id=$tar_id and (now() between timefrom and timeto) and usbox_services.cat_idx=categories.cat_idx";	    
	   };
	   $res3 = $lb->ExecuteSQL($sql);
	   while ($row3 = mysqli_fetch_array($res3)) { $rent=$row3["rent"]/date("t");};
	 $user[$i]["pay_day"]=$user[$i]["pay_day"]+$rent;
	 if ($type==6){$user[$i]["type_rent"]="int";} else {$user[$i]["type_rent"]="usl";};
       };
    };  
 };
 return $user; 
};
function GetPredPlatByVg_id($vg_id,$lb){
    $user=array();  
    $user=GetBlockedDayByDay($vg_id,$user,$lb); //получаем блокировки пользователя днем за нем...        
    $user=GetTarsDayByDay($vg_id,$user,$lb);    //получаем тарифы пользователя день за днем...
    $user=GetNextOneSpis($vg_id,$user,$lb);	//начисляем разовые списания будущего периода
    $user=GetPeriodicSpis($vg_id,$user,$lb);		//начисляем списание за интернет
    return $user;
};
function GetPredPlatByAgrmId($agrm_id,$lb){    
    $rent=array();
    $sql="select vg_id from vgroups where agrm_id='$agrm_id' and archive=0";
    $result = $lb->ExecuteSQL($sql);                
    while ($myrow = mysqli_fetch_array($result)){
        $vg_id=$myrow["vg_id"];
	$rent[$vg_id]=GetPredPlatByVg_id($vg_id,$lb);
    };
    return $rent;           
};
?>
