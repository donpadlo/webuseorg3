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

//проверяем имеет ли доступ пользователь к этой ферме
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
function GetViberSender($billing_id,$default){
global $sqlcn;    
    $sender=$default;
    $result = $sqlcn->ExecuteSQL("SELECT * FROM lbcfg WHERE id='$billing_id'");                
    while ($myrow = mysqli_fetch_array($result)){
        $sender=$myrow["vibersender"];
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
     if (($user[$i]["blocked"]==0) or ($user[$i]["blocked"]==1)) {$bday++;};     
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
function NormalizeAddress($address){
          $address=str_replace("Россия,обл Вологодская,,,", "", $address);    
          $address=str_replace("Россия,", "", $address);
          $address=str_replace(",г Череповец", "г.Череповец", $address);
          $address=str_replace("обл Вологодская,", "", $address);
          $address=str_replace("обл Ярославская,", "", $address);
          $address=str_replace("р-н Вологодский,", "", $address);            
          $address=str_replace("р-н Шекснинский,,", "", $address);            
          $address=str_replace("р-н Шекснинский,", "", $address);            
          $address=str_replace("р-н Кадуйский,,", "", $address);            
          $address=str_replace("р-н Кадуйский,", "", $address);            
          $address=str_replace("р-н Чагодощенский,", "", $address);            
          $address=str_replace("р-н Чагодощенский,,", "", $address);            
          $address=str_replace("р-н Вытегорский,", "", $address);            
          $address=str_replace("р-н Устюженский,", "", $address);            
          $address=str_replace("р-н Ростовский,", "", $address);            
          $address=str_replace("р-н Гаврилов-Ямский,", "", $address);            
          $address=str_replace("р-н Угличский,", "", $address);            
          $address=str_replace("р-н Тутаевский,", "", $address);            
          $address=str_replace(",п Фоминское", "п Фоминское", $address);            
          $address=str_replace(",г Данилов", "г Данилов", $address);            
          $address=str_replace("р-н Мышкинский,", "", $address);
          $address=str_replace(",рп Семибратово", "рп Семибратово", $address);                      
          $address=str_replace(",п Константиновский", "п Константиновский", $address);            
	  $address=str_replace("р-н Даниловскийг", "г", $address);            
          $address=str_replace(",,", ",", $address);            
          $address=str_replace(",,", ",", $address);     
	  $address=str_replace(",рп Чагода", "рп Чагода", $address);     
	  $address=str_replace(",п Борисово,,", "п Борисово", $address);     
	  $address=str_replace("р-н Тотемский,г Тотьма", "г Тотьма", $address);     
	  return $address;
};
function GetBodyIskShab($lb,$blibase,$agrm_id,$shab){    
    global $cfg;
    $br="{\lang1033\langfe1033\langnp1033\insrsid7031049\par }";
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    $cc=new Tcconfig();
    $dt_create_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-1");        // дата создания претензии
    if ($dt_create_pret==""){$dt_create_pret=date("d.m.Y");};
    $dt_send_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-2");	   // дата отправки претензии
    if ($dt_send_pret==""){$dt_send_pret=date("d.m.Y");};

    $dt_send_isk=$cc->GetByParam("dtc-$blibase-$agrm_id-8");	   // дата отправки искового
    if ($dt_send_isk==""){$dt_send_isk=date("d.m.Y");};
    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/2";	   // сквозной номер исходящего пакет
    
	$sud_username=$cc->GetByParam("dtc-$blibase-$agrm_id-3");
	if ($sud_username==""){$sud_username="Тихомировой Елене Николаевне";};
	$sud_uchastok=$cc->GetByParam("dtc-$blibase-$agrm_id-4");
	
	$pocht_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-5");
	if ($pocht_rash==""){$pocht_rash="53.65";};
	$pocht_rash=  str_replace(",", ".", $pocht_rash);
	$poshlina_summ=$cc->GetByParam("dtc-$blibase-$agrm_id-6");
	if ($poshlina_summ==""){$poshlina_summ="400.00";};
	$urist_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-7");
	$dt_pol_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-10");	
    
    //
    //ищем дату возникновения задолженности
    //
	    //определяю период, когда был накоплен долг:
	    // когда последний раз был положительный баланс?
	    $last_dt_plus="";
	    $sql="select * from balances where agrm_id=$agrm_id and round(balance)>=0 order by date desc limit 1";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$last_dt_plus=$row2["date"];
	    };	
	    //если дату не удалось определить, то берем самую первую..
	    if ($last_dt_plus==""){
		$sql="select * from balances where agrm_id=$agrm_id order by date  limit 1";  
		$res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
		while ($row2 = mysqli_fetch_array($res2)){ 
		    $last_dt_plus=$row2["date"];
		};		  
	    };
	    //echo "$last_dt_plus!!";
	    
	    // определяем период за который задолжал абонент
	    $sql="select date,round(balance,2) from balances where agrm_id=$agrm_id and date>'$last_dt_plus' group by round(balance,2) order by date";
//	    echo "$sql\n";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать выборку по балансу абонента!".mysqli_error($lb->idsqlconnection)); 	
	    $cn=0;
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cn++;
		if ($cn==1){$dt_s1=$row2["date"];}	    
		$dt_s2=$row2["date"]; //последняя дата изменения баланса
	    };
    ///////////////////////////
    // считаем неустойку..	
	    $sumnt=0;
	    
	    $dt_s3=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s3=  str_replace(" 00:00:00", "", $dt_s3);
	    
	    $sql="select balance from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s3';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    //$sumnt=0;
	    $costob=$cfg->GetByParam("dtc-$blibase-$agrm_id-24");          
	    $flagob=0;$oldbal=0;
	    if ($costob>0) {$maxbal=abs($balance)-$costob;} else {$maxbal=abs($balance);};
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		
		if (($cbal-$oldbal==$costob) and ($costob>0)){$flagob=1;};
		if ($flagob==1){$cbal=$cbal-$costob;};
		
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
		if ($costob>0){	
		    if ($sumnt>=abs($balance)-$costob){break;};    
		};

		if (($flagob==1) and ($sumnt+$costob>=$balance)){break;};
		
		$oldbal=$cbal;
	    };	    	       	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$maxbal){$sumnt=$maxbal;}; //начисленное пени	    
	    if ($flagob==1) {$sumnt=$balance-$costob;};
    //определяем когда отключен интернет
    $period_ent="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by period desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);
	$period_ent="- $dtvzzadint 23:59:59 было приостановлено оказание услуги доступа в сеть Интернет.";
    };        

    //определяем когда отключено ТВ	
    $period_tv="";$dtvzzadtv="";
    //$sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc  limit 1";
    $sql="select vg_blocks_history.rasp_time from usbox_charge inner join vg_blocks_history on vg_blocks_history.vg_id=usbox_charge.vg_id inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0  and vg_blocks_history.block_id_new=10 order by usbox_charge.period,vg_blocks_history.record_id desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	$period_tv="- $dtvzzadtv было приостановлено оказание услуги предоставления вещания ТВ.";
    };        
    ////////////	
$txt_bal=num2str($balance);
$txt_sumnt=num2str($sumnt);
$txt_pocht_rash=num2str($pocht_rash);
$txt_poshlina_summ=num2str($poshlina_summ);
	
$aa1a="$dog_date г. между ООО «Мультистрим» и Абонентом (гр. $name),был заключен договор на предоставление услуг связи для физических лиц № $number, согласно которого Оператор - ООО «Мультистрим» принял на себя обязательство предоставлять Абоненту (гр. $name) услуги связи,а Абонент обязался принимать и оплачивать оказанные услуги в соответствии с условиями Договора. ";
$aa2a="По состоянию на $dt_create_pret г. за Абонентом (гр. $name) числится ".
	    "задолженность по оплате абонентской платы по Договору № $number от $dog_date в размере ".
	    "$balance руб.($txt_bal). Детализация лицевого счета приведена в Приложении 6 из которого видны все оказанные услуги, поступления и списания денежных средств за все время действия Договора.";

$aa4a="В настоящий момент договор на предоставление услуг связи для физических лиц (лицевой счет  № $number) ".
	"с абонентом  $name  не расторгнут. Заявления о расторжении данного Договора № $number от ".
	"абонента $name  не поступало. У ООО «Мультистрим» нет намерения  расторгать данный Договор.";
$rzt="";
if ($period_ent!="")$rzt=$rzt.$period_ent.$br;
if ($period_tv!="")$rzt=$rzt.$period_tv.$br;
$aa5a=$rzt;

$aa6a="Дополнительно сообщаем, что приостановка услуг кабельного телевидения и доступа в сеть Интернет осуществлялась на основании производственного задания, ".
      "санкционированного руководством ООО «Мультистрим».";
if ($dtvzzadint==""){$zzz="Копию производственного задания на приостановку услуг от $dtvzzadtv прилагаем (Приложение № 11)";};
if ($dtvzzadtv==""){$zzz="Копию производственного задания на приостановку услуг от $dtvzzadint прилагаем (Приложение № 12)";};
if (($dtvzzadtv!="") and ($dtvzzadtv!="")) {$zzz="Копию производственного задания на приостановку услуг от $dtvzzadint и от $dtvzzadtv прилагаем (Приложение № 11,12)";};
$aa6a=$aa6a.$zzz;

$aa7a="Согласно пп. 7.2. Договора в случае неоплаты услуг Оператор вправе взыскать с Абонента неустойку в ".
	    "размере 1 % (одного процента) стоимости неоплаченных услуг за каждый день просрочки вплоть до погашения ".
	    "задолженности, но не более суммы, подлежащих оплате. По состоянию на $dt_create_pret сумма неустойки составляет $sumnt руб.($txt_sumnt). Расчет неустойки приведен в Приложении 7.";

$aa8a="$dt_send_pret г. в адрес абонента была направлена претензия с требованием погасить заложенность по абонентской плате.";
if ($dt_pol_pret==""){$aa8a=$aa8a."Претензия абонентом получена не была.";};
if ($dt_pol_pret!=""){$aa8a=$aa8a."Претензия абонентом была получена $dt_pol_pret.";};
$aa8a=$aa8a."До настоящего момента претензия осталась без удовлетворения, вышеуказанная задолженность Абонентом не погашена.";


if ($urist_rash==""){$urist_rash=$sumnt;};
$txt_urist_rash=num2str($urist_rash);	
$aa9a=
"1. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» задолженность по оплате абонентской платы, в сумме $balance руб ($txt_bal).".$br.
"2. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» неустойку в размере 1 % (одного процента) стоимости неоплаченных услуг за каждый день просрочки, в сумме $sumnt руб ($txt_sumnt).".$br.
"3. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату почтовых услуг связи, в сумме $pocht_rash руб ($txt_pocht_rash)".$br.
"4. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату государственной пошлины, в сумме $poshlina_summ руб ($txt_poshlina_summ). ".$br.
"5. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату услуг юриста, в сумме $urist_rash руб ($txt_urist_rash). ";
	
    $aa1a=@iconv("UTF-8", "CP1251",$aa1a);
    $aa2a=@iconv("UTF-8", "CP1251",$aa2a);    
    $aa4a=@iconv("UTF-8", "CP1251",$aa4a);
    $aa5a=@iconv("UTF-8", "CP1251",$aa5a);
    $aa6a=@iconv("UTF-8", "CP1251",$aa6a);
    $aa7a=@iconv("UTF-8", "CP1251",$aa7a);
    $aa8a=@iconv("UTF-8", "CP1251",$aa8a);
    $aa9a=@iconv("UTF-8", "CP1251",$aa9a);
    
    if ($mobile!=""){
	$address=$address.$br."Телефон: ".$mobile;
    };    
    $address=@iconv("UTF-8", "CP1251",$address);
    $ish_nom=@iconv("UTF-8", "CP1251",$ish_nom);
    $sud_username=@iconv("UTF-8", "CP1251",$sud_username);
    $sud_uchastok=@iconv("UTF-8", "CP1251",$sud_uchastok);
    $username=@iconv("UTF-8", "CP1251",$name);

    $shab = str_replace("Address", $address, $shab);
    $shab = str_replace("username", $username, $shab);
    $shab = str_replace("ish_nom", $ish_nom, $shab);
    $shab = str_replace("ish_date", $dt_send_isk, $shab);
    $shab = str_replace("namesudya", $sud_username, $shab);
    $shab = str_replace("sudych", $sud_uchastok, $shab);
    $shab = str_replace("abrakadamba1", $aa1a, $shab);
    $shab = str_replace("abrakadamba2", $aa2a, $shab);
    $shab = str_replace("abrakadamba4", $aa4a, $shab);
    $shab = str_replace("abrakadamba5", $aa5a, $shab);
    $shab = str_replace("abrakadamba6", $aa6a, $shab);
    $shab = str_replace("abrakadamba7", $aa7a, $shab);
    $shab = str_replace("abrakadamba8", $aa8a, $shab);
    $shab = str_replace("abrakadamba9", $aa9a, $shab);
        
    return $shab;    
};
function GetBodyShabV2($lb,$blibase,$agrm_id,$shab){    
global $sqlcn,$cfg;    
    $br="{\lang1033\langfe1033\langnp1033\insrsid7031049\par }";	
    switch ($blibase) {
	case 1:$firmname="ООО «Телесервис-плюс»";break;
	case 2:$firmname="ООО «Яртелесервис»";break;
	case 3:$firmname="ООО «Мультистрим»";break;
	case 6:$firmname="ООО «Мультистрим»";break;

	default:$firmname="";break;
    }
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    $cc=new Tcconfig();
    $dt_create_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-1");        // дата создания претензии
    if ($dt_create_pret==""){$dt_create_pret=date("d.m.Y");};
    $dt_send_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-2");	   // дата отправки претензии
    if ($dt_send_pret==""){$dt_send_pret=date("d.m.Y");};

    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/1";	   // сквозной номер исходящего пакет
	if ($cc->GetByParam("dtc-$blibase-$agrm_id-9")==""){
	   $sql="SELECT count(*) as cnt FROM `config_common` WHERE nameparam like '%dtc-%'"; 
           $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать количество записей!".mysqli_error($sqlcn->idsqlconnection));
	   while($row = mysqli_fetch_array($result)) {$ish_nom=$row["cnt"];};             	   
	   $cc->SetByParam("dtc-$blibase-$agrm_id-9", $ish_nom);
	};    
    //
    // ищем дату возникновения задолженности
    //
	    //определяю период, когда был накоплен долг:
	    // когда последний раз был положительный баланс?
	    $last_dt_plus="";
	    $sql="select * from balances where agrm_id=$agrm_id and round(balance)>=0 order by date desc limit 1";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$last_dt_plus=$row2["date"];
	    };	
	    //если дату не удалось определить, то берем самую первую..
	    if ($last_dt_plus==""){
		$sql="select * from balances where agrm_id=$agrm_id order by date  limit 1";  
		$res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
		while ($row2 = mysqli_fetch_array($res2)){ 
		    $last_dt_plus=$row2["date"];
		};		  
	    };
	    //echo "$last_dt_plus!!";
	    
	    // определяем период за который задолжал абонент
	    $sql="select date,round(balance,2) from balances where agrm_id=$agrm_id and date>'$last_dt_plus' group by round(balance,2) order by date";
	    //echo "$sql\n";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать выборку по балансу абонента!".mysqli_error($lb->idsqlconnection)); 	
	    $cn=0;
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cn++;
		if ($cn==1){$dt_s1=$row2["date"];}	    
		$dt_s2=$row2["date"]; //последняя дата изменения баланса
	    };
/*	    $dt_s1= str_replace("31", "01", $dt_s1);
	    $dt_s1= str_replace("30", "01", $dt_s1);
	    $dt_s1= str_replace("28", "01", $dt_s1);
	    $dt_s1= str_replace("29", "01", $dt_s1);
	    //$dt_s_mysql=$dt_s1;
*/
    ///////////////////////////
    // считаем неустойку..	
	    $sumnt=0;
	    /*$dt_s2=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s2=  str_replace(" 00:00:00", "", $dt_s2);
	    $sql="select sum(balance*0.01) as peni from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s2';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$sumnt=abs(round($row2["peni"]));	
	    };*/
	    
	    $dt_s3=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s3=  str_replace(" 00:00:00", "", $dt_s3);
	    
	    $sql="select balance from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s3';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    //$sumnt=0;
	    $costob=$cfg->GetByParam("dtc-$blibase-$agrm_id-24");          
	    $flagob=0;$oldbal=0;
	    if ($costob>0) {$maxbal=abs($balance)-$costob;} else {$maxbal=abs($balance);};
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		
		if (($cbal-$oldbal==$costob) and ($costob>0)){$flagob=1;};
		if ($flagob==1){$cbal=$cbal-$costob;};
		
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
		if ($costob>0){	
		    if ($sumnt>=abs($balance)-$costob){break;};    
		};		
		if (($flagob==1) and ($sumnt+$costob>=$balance)){break;};		
		$oldbal=$cbal;
	    };	    	       	    
	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$maxbal){$sumnt=$maxbal;}; //начисленное пени	    
	    if ($flagob==1) {$sumnt=$balance-$costob;};
//	    $dt_s1=MySQLDateTimeToDateTimeNoTime($dt_s1." 00:00:00"); // дата начала задолженности
//	    $dt_s2=MySQLDateTimeToDateTimeNoTime($dt_s2." 00:00:00"); // последняя дата начисления
	    
	    /*$datetime1 = new DateTime($dt_s_mysql);
	    $datetime2 = new DateTime(DateToMySQLDateTime2($dt_create_pret));
	    $interval = $datetime1->diff($datetime2);
	    //var_dump($interval);
	    $resdey=$interval->format('%R%a');
	    $rzxc=$resdey+1;
	    $resdey=$resdey/100;
	    $sumnt=$balance;
	    $sumnt=$sumnt+$balance*$resdey;
	    $sumnt=round($sumnt-$balance,2);
	    if ($sumnt>$balance){$sumnt=$balance;}; //начисленное пени
	    */
	    
    //определяем когда отключен интернет
    $period_ent="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by c_date desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);
	$period_ent="Оказание услуги доступа в сеть Интернет было приостановлено $dtvzzadint 23:59:59.";
    };        

    //определяем когда отключено ТВ	
    $period_tv="";	
    
    //сначала определяем, а вообще ТВ учетка отключена?
    $sql="select count(*) as cnt from vg_blocks where vg_id in (select vg_id from vgroups where agrm_id=$agrm_id and vgroups.blocked=10 and vgroups.id in (select id from settings where type=13))";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$cnttv=$row2["cnt"];
    };
    if ($cnttv>0){
	//$sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc limit 1";
	$sql="select vg_blocks_history.rasp_time from usbox_charge inner join vg_blocks_history on vg_blocks_history.vg_id=usbox_charge.vg_id inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0  and vg_blocks_history.block_id_new=10 order by usbox_charge.period,vg_blocks_history.record_id desc limit 1";
	//echo "$sql!";
	$res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
	while ($row2 = mysqli_fetch_array($res12)){     	
	    $dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	    $period_tv="Оказание услуги предоставления вещания ТВ было приостановлено $dtvzzadtv.";
	};        
    };
    ////////////	


	//узнаем, есть акция Лояльность у абонента?
	$lol="";
	$sql="select tarifs.descr from vgroups inner join tarifs on vgroups.tar_id=tarifs.tar_id where vgroups.id in (select id from settings where type<>13) and agrm_id=$agrm_id and blocked<>10 and tarifs.descr like '%ояльность%'";
        $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать информацию по лояльности!".mysqli_error($lb->idsqlconnection)); 
        while ($row2 = mysqli_fetch_array($res2)){ 
	    $lol=$row2["descr"];
	};
	
	//узнаем, есть выданные абоненту роутеры?
	$router_name="";$router_serial="";	
	$sql="select categories.uuid,equipment.chip_id,usbox_services.tar_id,usbox_services.cat_idx,equipment_history.serv_id,equipment.name,equipment.serial,equipment.descr from equipment inner join equipment_history on equipment_history.equip_id=equipment.equip_id  inner join usbox_services on usbox_services.serv_id=equipment_history.serv_id inner join categories on categories.cat_idx=usbox_services.cat_idx where equipment.archive=0 and equipment_history.timeto is null and equipment_history.agrm_id=$agrm_id and usbox_services.need_calc=1 and categories.tar_id=usbox_services.tar_id and categories.uuid like '%router_%'";
        $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать информацию по роутеру!".mysqli_error($lb->idsqlconnection)); 
        while ($row2 = mysqli_fetch_array($res2)){ 
	    $router_name=$row2["name"];
	    $router_serial=$row2["serial"];
	};
	
	//узнаем, есть у абонента приставка?
	$dvc_name="";$dvc_serial="";
	$sql="select categories.uuid,equipment.chip_id,usbox_services.tar_id,usbox_services.cat_idx,equipment_history.serv_id,equipment.name,equipment.serial,equipment.descr from equipment inner join equipment_history on equipment_history.equip_id=equipment.equip_id  inner join usbox_services on usbox_services.serv_id=equipment_history.serv_id inner join categories on categories.cat_idx=usbox_services.cat_idx where equipment.archive=0 and equipment_history.timeto is null and equipment_history.agrm_id=$agrm_id and usbox_services.need_calc=1 and categories.tar_id=usbox_services.tar_id and categories.uuid like '%iptv%'";
        $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать информацию по приставке!".mysqli_error($lb->idsqlconnection)); 
        while ($row2 = mysqli_fetch_array($res2)){ 
	    $dvc_name=$row2["name"];
	    $dvc_serial=$row2["serial"];
	};    
    
    
    $shab = str_replace("username", @iconv("UTF-8", "CP1251",$name), $shab);
    $shab = str_replace("address", @iconv("UTF-8", "CP1251",$address), $shab);

    $abzac1="$dog_date г между $firmname и Абонентом (гр. $name) ".
	    "был заключен договор на предоставление услуг связи для физических лиц № $number, согласно ".
	    "которого Оператор - $firmname принял на себя обязательство предоставлять Абоненту ".
	    "(гр. $name) услуги связи,".
	    "а Абонент обязался принимать и оплачивать оказанные услуги в соответствии с условиями Договора.";
    $abzac1=@iconv("UTF-8", "CP1251",$abzac1);
    if ($costob>0){
	$cblan=$balance-$costob;
    } else {
	$cblan=$balance;
    };
    $abzac2="По состоянию на $dt_create_pret г. за Абонентом (гр. $name) числится ".
	    "задолженность по оплате абонентской платы по Договору № $number от $dog_date в размере ".
	    "$cblan руб.";
    if ($lol!=""){
	$ccc="(абонентская плата за весь период фактического пользования услугами пересчитана согласно полной стоимости тарифного плана в связи нарушением условий акции «Лояльность»)";
	$abzac2=$abzac2.$ccc;	    
    };    
    $abzac2=@iconv("UTF-8", "CP1251",$abzac2);
    $abzac3="Согласно П. 7.2. Договора в случае неоплаты услуг Оператор вправе взыскать с Абонента неустойку в ".
	    "размере 1 % (одного процента) стоимости неоплаченных услуг за каждый день просрочки вплоть до погашения ".
	    "задолженности, но не более суммы, подлежащей оплате. По состоянию на $dt_create_pret сумма неустойки составляет $sumnt руб.";
    if ($router_name!=""){
	$abzac3=$abzac3.$br."Кроме того,согласно дополнительного соглашения к договору от $dog_date, Вам в аренду было предоставлено оборудование - роутер-маршрутизатор $router_name стоимостью $costob руб. Наличие задолженности за услуги связи более одного месяца является основанием для расторжения указанного соглашения, и оборудование подлежит возврату, а в случае его не возврата (утраты) или порчи в результате нарушения правил эксплуатации абонент обязан возместить оператору стоимость оборудования, зафиксированную в акте приема-передачи.";
    };
    if ($dvc_name!=""){
	$abzac3=$abzac3.$br."Кроме того,согласно дополнительного соглашения к договору от $dog_date, Вам в аренду было предоставлено оборудование - DVB-C приставка ($dvc_name) стоимостью __________ руб. Наличие задолженности за услуги связи более одного месяца является основанием для расторжения указанного соглашения, и оборудование подлежит возврату, а в случае его не возврата (утраты) или порчи в результате нарушения правил эксплуатации абонент обязан возместить оператору стоимость оборудования, зафиксированную в акте приема-передачи.";
    };
    
    $abzac3=@iconv("UTF-8", "CP1251",$abzac3);
    $abzac4="Согласно П. 6.1.1. Договора на предоставление услуг связи для физических лиц Оператор имеет право ".
	    "приостановить оказание услуг в случае возникновения задолженности (обнуления баланса) на Лицевом счете Абонента.$period_ent $period_tv";
    $abzac4=@iconv("UTF-8", "CP1251",$abzac4);
    $abzac5="На основании вышеизложенного просим погасить образовавшуюся задолженность в ".
	    "течение 10 (десяти) дней с момента получения настоящей претензии";
    if ($costob>0){
	$abzac5=$abzac5.", а так же вернуть оборудование или возместить его стоимость";
    };
    $abzac5=$abzac5.".";
    $abzac5=@iconv("UTF-8", "CP1251",$abzac5);
    $abzac6="В случае отсутствия оплаты в установленные сроки, оставляем за собой право обратиться в суд с требованиями принудительного взыскания образовавшейся задолженности, неустойки, рассчитанной на дату подачи иска, а также возмещения судебных расходов (госпошлины, стоимости юридических услуг, почтовых и иных расходов).";
    $abzac6=@iconv("UTF-8", "CP1251",$abzac6);

    $shab = str_replace("ish_nom", $ish_nom, $shab);
    $shab = str_replace("ish_date", $dt_create_pret, $shab);
    $shab = str_replace("Abzac1", $abzac1, $shab);
    $shab = str_replace("Abzac2", $abzac2, $shab);
    $shab = str_replace("Abzac3", $abzac3, $shab);
    $shab = str_replace("Abzac4", $abzac4, $shab);
    $shab = str_replace("Abzac5", $abzac5, $shab);
    $shab = str_replace("Abzac6", $abzac6, $shab);
    
    //ну и до кучи проставлю дату формирования претензии в НОС
    if ($cc->GetByParam("dtc-$blibase-$agrm_id-1")==""){
	$cc->SetByParam("dtc-$blibase-$agrm_id-1", Date("d.m.Y"));
    };    
    if ($cc->GetByParam("dtc-$blibase-$agrm_id-14")==""){
	$cc->SetByParam("dtc-$blibase-$agrm_id-14", abs($balance));
    };
    
    return $shab;
};

function GetBodyShab($lb,$blibase,$agrm_id,$shab){    
    global $cfg;
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    $cc=new Tcconfig();
    $dt_create_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-1");        // дата создания претензии
    if ($dt_create_pret==""){$dt_create_pret=date("d.m.Y");};
    $dt_send_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-2");	   // дата отправки претензии
    if ($dt_send_pret==""){$dt_send_pret=date("d.m.Y");};

    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/1";	   // сквозной номер исходящего пакет
    
    //
    // ищем дату возникновения задолженности
    //
	    //определяю период, когда был накоплен долг:
	    // когда последний раз был положительный баланс?
	    $last_dt_plus="";
	    $sql="select * from balances where agrm_id=$agrm_id and round(balance)>=0 order by date desc limit 1";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$last_dt_plus=$row2["date"];
	    };	
	    //если дату не удалось определить, то берем самую первую..
	    if ($last_dt_plus==""){
		$sql="select * from balances where agrm_id=$agrm_id order by date  limit 1";  
		$res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
		while ($row2 = mysqli_fetch_array($res2)){ 
		    $last_dt_plus=$row2["date"];
		};		  
	    };
	    //echo "$last_dt_plus!!";
	    
	    // определяем период за который задолжал абонент
	    $sql="select date,round(balance,2) from balances where agrm_id=$agrm_id and date>'$last_dt_plus' group by round(balance,2) order by date";
//	    echo "$sql\n";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать выборку по балансу абонента!".mysqli_error($lb->idsqlconnection)); 	
	    $cn=0;
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cn++;
		if ($cn==1){$dt_s1=$row2["date"];}	    
		$dt_s2=$row2["date"]; //последняя дата изменения баланса
	    };
/*	    $dt_s1= str_replace("31", "01", $dt_s1);
	    $dt_s1= str_replace("30", "01", $dt_s1);
	    $dt_s1= str_replace("28", "01", $dt_s1);
	    $dt_s1= str_replace("29", "01", $dt_s1);
	    //$dt_s_mysql=$dt_s1;
*/
    ///////////////////////////
    // считаем неустойку..	
	    $sumnt=0;
	    /*$dt_s2=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s2=  str_replace(" 00:00:00", "", $dt_s2);
	    $sql="select sum(balance*0.01) as peni from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s2';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$sumnt=abs(round($row2["peni"]));	
	    };*/
	    
	    $dt_s3=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s3=  str_replace(" 00:00:00", "", $dt_s3);
	    
	    $sql="select balance from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s3';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    //$sumnt=0;
//	    while ($row2 = mysqli_fetch_array($res2)){ 
//		$cbal=abs(round($row2["balance"],2));    
//		$peni=round(($cbal)*0.01,2);
//		$sumnt=round($sumnt+$peni,2);
//		if ($sumnt>=$balance){break;};
//	    };	    	       	    
	    
	    $costob=$cfg->GetByParam("dtc-$blibase-$agrm_id-24");          
	    $flagob=0;$oldbal=0;
	    if ($costob>0) {$maxbal=abs($balance)-$costob;} else {$maxbal=abs($balance);};
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		
		if (($cbal-$oldbal==$costob) and ($costob>0)){$flagob=1;};
		if ($flagob==1){$cbal=$cbal-$costob;};
		
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
		if ($costob>0){	
		    if ($sumnt>=abs($balance)-$costob){break;};    
		};		
		if (($flagob==1) and ($sumnt+$costob>=$balance)){break;};		
		$oldbal=$cbal;
	    };
	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$maxbal){$sumnt=$maxbal;}; //начисленное пени	    
	    if ($flagob==1) {$sumnt=$balance-$costob;};

//	    $dt_s1=MySQLDateTimeToDateTimeNoTime($dt_s1." 00:00:00"); // дата начала задолженности
//	    $dt_s2=MySQLDateTimeToDateTimeNoTime($dt_s2." 00:00:00"); // последняя дата начисления
	    
	    /*$datetime1 = new DateTime($dt_s_mysql);
	    $datetime2 = new DateTime(DateToMySQLDateTime2($dt_create_pret));
	    $interval = $datetime1->diff($datetime2);
	    //var_dump($interval);
	    $resdey=$interval->format('%R%a');
	    $rzxc=$resdey+1;
	    $resdey=$resdey/100;
	    $sumnt=$balance;
	    $sumnt=$sumnt+$balance*$resdey;
	    $sumnt=round($sumnt-$balance,2);
	    if ($sumnt>$balance){$sumnt=$balance;}; //начисленное пени
	    */
	    
    //определяем когда отключен интернет
    $period_ent="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by period desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);
	$period_ent="$dtvzzadint 23:59:59 было приостановлено оказание услуги доступа в сеть Интернет.";
    };        

    //определяем когда отключено ТВ	
    $period_tv="";	
    
    //сначала определяем, а вообще ТВ учетка отключена?
    $sql="select count(*) as cnt from vg_blocks where vg_id in (select vg_id from vgroups where agrm_id=$agrm_id and vgroups.blocked=10 and vgroups.id in (select id from settings where type=13))";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$cnttv=$row2["cnt"];
    };
    if ($cnttv>0){
	//$sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc limit 1";
	$sql="select vg_blocks_history.rasp_time from usbox_charge inner join vg_blocks_history on vg_blocks_history.vg_id=usbox_charge.vg_id inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0  and vg_blocks_history.block_id_new=10 order by usbox_charge.period,vg_blocks_history.record_id desc limit 1";
	//echo "$sql!";
	$res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
	while ($row2 = mysqli_fetch_array($res12)){     	
	    $dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	    $period_tv="$dtvzzadtv было приостановлено оказание услуги предоставления вещания ТВ.";
	};        
    };
      
    ////////////	


    $shab = str_replace("username", @iconv("UTF-8", "CP1251",$name), $shab);
    $shab = str_replace("address", @iconv("UTF-8", "CP1251",$address), $shab);

    $abzac1="$dog_date г между ООО «Мультистрим» и Абонентом (гр. $name) ".
	    "был заключен договор на предоставление услуг связи для физических лиц № $number, согласно ".
	    "которого Оператор - ООО «Мультистрим» принял на себя обязательство предоставлять Абоненту ".
	    "(гр. $name) услуги связи,".
	    "а Абонент обязался принимать и оплачивать оказанные услуги в соответствии с условиями Договора.";
    $abzac1=@iconv("UTF-8", "CP1251",$abzac1);
    $abzac2="По состоянию на $dt_create_pret г. за Абонентом (гр. $name) числится ".
	    "задолженность по оплате абонентской платы по Договору № $number от $dog_date в размере ".
	    "$balance руб.(детализация лицевого счета приведена в Приложении 2).";
    $abzac2=@iconv("UTF-8", "CP1251",$abzac2);
    $abzac3="Согласно пп. 7.2. Договора в случае неоплаты услуг Оператор вправе взыскать с Абонента неустойку в ".
	    "размере 1 % (одного процента) стоимости неоплаченных услуг за каждый день просрочки вплоть до погашения ".
	    "задолженности, но не более суммы, подлежащих оплате. По состоянию на $dt_create_pret сумма неустойки составляет $sumnt руб. (расчет неустойки приведен в Приложении 3)";
    $abzac3=@iconv("UTF-8", "CP1251",$abzac3);
    $abzac4="Согласно пп. 6.1.1. Договора на предоставление услуг связи для физических лиц Оператор имеет право (но не обязан) ".
	    "приостановить оказание услуг в случае обнуления на Лицевом счете Абонента.$period_ent $period_tv";
    $abzac4=@iconv("UTF-8", "CP1251",$abzac4);
    $abzac5="На основании вышеизложенного просим погасить образовавшуюся задолженность по абонентской плате в ".
	    "течение 10 (десяти) дней с момента получения данной претензии.";
    $abzac5=@iconv("UTF-8", "CP1251",$abzac5);
    $abzac6="В случае отсутствия оплаты в установленные сроки, оставляем за собой право обратиться в суд с требованиями принудительного взыскания образовавшейся задолженности, неустойки, рассчитанной на дату подачи иска, а также возмещения судебных расходов (госпошлины, стоимости юридических услуг, почтовых и иных расходов).";
    $abzac6=@iconv("UTF-8", "CP1251",$abzac6);

    $shab = str_replace("ish_nom", $ish_nom, $shab);
    $shab = str_replace("ish_date", $dt_create_pret, $shab);

    $shab = str_replace("Abzac1", $abzac1, $shab);
    $shab = str_replace("Abzac2", $abzac2, $shab);
    $shab = str_replace("Abzac3", $abzac3, $shab);
    $shab = str_replace("Abzac4", $abzac4, $shab);
    $shab = str_replace("Abzac5", $abzac5, $shab);
    $shab = str_replace("Abzac6", $abzac6, $shab);
    
    return $shab;
};
function Getpz_int_create($lb,$blibase,$agrm_id,$shab){
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    //определяем когда отключен интернет
    $period_ent="";$dtvzzadint="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by period desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);	
    };          
    
    $cc=new Tcconfig();
    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/3";	   // сквозной номер исходящего пакет
    $shab = str_replace("numzad", $ish_nom, $shab);
    $shab = str_replace("datanachrabot", $dtvzzadint, $shab);
    $shab = str_replace("dataokonchrabot", $dtvzzadint, $shab);
    $shab = str_replace("useraddress", @iconv("UTF-8", "CP1251",$address), $shab);
    return $shab;    
};
function Getpz_tv_create($lb,$blibase,$agrm_id,$shab){
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    
    //определяем когда отключено ТВ	
    $period_tv="";	
    //$sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc limit 1";
    $sql="select vg_blocks_history.rasp_time from usbox_charge inner join vg_blocks_history on vg_blocks_history.vg_id=usbox_charge.vg_id inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0  and vg_blocks_history.block_id_new=10 order by usbox_charge.period,vg_blocks_history.record_id desc limit 1";
    //echo "$sql!";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	$period_tv="$dtvzzadtv было приостановлено оказание услуги предоставления вещания ТВ.";
    };        
    ////////////	
    
    $cc=new Tcconfig();
    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/4";	   // сквозной номер исходящего пакет
    $shab = str_replace("numzad", $ish_nom, $shab);
    $shab = str_replace("datanachrabot", $dtvzzadtv, $shab);
    $shab = str_replace("dataokonchrabot", $dtvzzadtv, $shab);
    $shab = str_replace("useraddress", @iconv("UTF-8", "CP1251",$address), $shab);
    return $shab;    
};
/**
 * Заполнить шаблон судебного приказа
 * @param type $lb
 * @param type $blibase
 * @param type $agrm_id
 * @param type $shab
 */
function Get_order_of_isp($lb,$blibase,$agrm_id,$shab){
$br="{\lang1033\langfe1033\langnp1033\insrsid7031049\par }";
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
	$birthdate=MySQLDateTimeToDateTimeNoTime($row2["birthdate"]." 00:00:00");
    };        

    $cc=new Tcconfig();
    $dt_create_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-1");        // дата создания претензии
    if ($dt_create_pret==""){$dt_create_pret=date("d.m.Y");};
    $dt_send_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-2");	   // дата отправки претензии
    if ($dt_send_pret==""){$dt_send_pret=date("d.m.Y");};

    $dt_send_isk=$cc->GetByParam("dtc-$blibase-$agrm_id-8");	   // дата отправки искового
    if ($dt_send_isk==""){$dt_send_isk=date("d.m.Y");};
    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/2";	   // сквозной номер исходящего пакет
    
	$sud_username=$cc->GetByParam("dtc-$blibase-$agrm_id-3");
	if ($sud_username==""){$sud_username="Тихомировой Елене Николаевне";};
	$sud_uchastok=$cc->GetByParam("dtc-$blibase-$agrm_id-4");
	
	$pocht_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-5");
	if ($pocht_rash==""){$pocht_rash="53.65";};
	$pocht_rash=  str_replace(",", ".", $pocht_rash);
	$poshlina_summ=$cc->GetByParam("dtc-$blibase-$agrm_id-6");
	if ($poshlina_summ==""){$poshlina_summ="400.00";};
	$urist_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-7");
	$dt_pol_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-10");
	
	$prist_address=$cc->GetByParam("dtc-$blibase-$agrm_id-15");
	$prist_address=  str_replace("(", $br."(", $prist_address);
	$prist_fio=$cc->GetByParam("dtc-$blibase-$agrm_id-16");
	$prist_isplist=$cc->GetByParam("dtc-$blibase-$agrm_id-17");	    
	$prist_isplist_address_sud=$cc->GetByParam("dtc-$blibase-$agrm_id-18");	    	
	$prist_isplist_date=$cc->GetByParam("dtc-$blibase-$agrm_id-19");	    	
	$prist_isplist_sud_num=$cc->GetByParam("dtc-$blibase-$agrm_id-20");	    	
	$prist_isplist_date_sud=$cc->GetByParam("dtc-$blibase-$agrm_id-21");	    	
	$small_sud_username=smallfio($sud_username);
	$prist_isplist_check=$cc->GetByParam("dtc-$blibase-$agrm_id-23");            
	//var_dump($small_sud_username);
	//var_dump($sud_username);
	if ($mobile!=""){
	    $address=$address.".Телефон: ".$mobile;
	};  
	if ($prist_isplist_check=="true"){$prist_isplist_check="исполнительный лист";} else {$prist_isplist_check="судебный приказ";};
$abzac123="Прошу принять к исполнению исполнительный документ ($prist_isplist_check) $prist_isplist выданный Мировым судьей Вологодской области по судебному участку № $sud_uchastok $small_sud_username ($prist_isplist_address_sud) ".
	" $prist_isplist_date по гражданскому делу № $prist_isplist_sud_num, рассмотренному $prist_isplist_date_sud в открытом судебном заседании по иску Общества с ограниченной ответственностью «Мультистрим» в отношении должника ".
	"гр. $name: $birthdate года рождения, проживающего по адресу: $address".$br;
$abzac123=$abzac123.$br."Ранее по данному исполнительному документу исполнительное производство не возбуждалось.";
$abzac123=$abzac123.$br."На основании изложенного, руководствуясь статьей 30 Федерального закона «Об исполнительном производстве»,";

    $abzac123=iconv("UTF-8", "CP1251//IGNORE",$abzac123);
    $sudpristav=$prist_address.$br.$prist_fio;
    $sudpristav=@iconv("UTF-8", "CP1251",$sudpristav);
    $curdateza=@iconv("UTF-8", "CP1251",$curdateza);
    
    $address=@iconv("UTF-8", "CP1251",$address);	    
    $ish_nom=@iconv("UTF-8", "CP1251",$ish_nom);
    $sud_username=@iconv("UTF-8", "CP1251",$sud_username);
    $sud_uchastok=@iconv("UTF-8", "CP1251",$sud_uchastok);
    $username=@iconv("UTF-8", "CP1251",$name);
//var_dump($abzac123);    
    $shab = str_replace("abzac123", $abzac123, $shab);
    $shab = str_replace("sudpristav", $sudpristav, $shab);
    $shab = str_replace("curdateza", $curdateza, $shab);
    return $shab;    
};

/**
 * Заполнить шаблон судебного приказа
 * @param type $lb
 * @param type $blibase
 * @param type $agrm_id
 * @param type $shab
 */
function Get_order_of_court($lb,$blibase,$agrm_id,$shab){
    global $cfg;
$br="{\lang1033\langfe1033\langnp1033\insrsid7031049\par }";
    //собираем основную информацию...
    $sql="select agreements.uid,accounts.name,accounts_addr.address,agreements.number,agreements.date,agreements.balance from agreements left join accounts_addr on accounts_addr.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid where agrm_id=$agrm_id and accounts_addr.type=1";      
    $res = $lb->ExecuteSQL($sql) or die("Не могу выбрать список абонентов!".mysqli_error($lb->idsqlconnection)); 
    while ($row = mysqli_fetch_array($res)){ 
	$uid=$row["uid"]; ///номер договора
	$name=$row["name"]; ///номер договора
	$adress=$address=  NormalizeAddress($row["address"]); //адрес
	$number=$row["number"]; ///номер договора
	$dog_date=MySQLDateTimeToDateTimeNoTime($row["date"]); //когда создан договор
	$balance=round(abs($row["balance"]),2);	//текщий баланс
    };
    //получаем мобильный телефон
    $mobile="";
    $sql="select * from accounts where uid=$uid";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать омер телефона!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$mobile=$row2["mobile"];
    };        

    $cc=new Tcconfig();
    $dt_create_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-1");        // дата создания претензии
    if ($dt_create_pret==""){$dt_create_pret=date("d.m.Y");};
    $dt_send_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-2");	   // дата отправки претензии
    if ($dt_send_pret==""){$dt_send_pret=date("d.m.Y");};

    $dt_send_isk=$cc->GetByParam("dtc-$blibase-$agrm_id-8");	   // дата отправки искового
    if ($dt_send_isk==""){$dt_send_isk=date("d.m.Y");};
    $ish_nom=$cc->GetByParam("dtc-$blibase-$agrm_id-9")."/2";	   // сквозной номер исходящего пакет
    
	$sud_username=$cc->GetByParam("dtc-$blibase-$agrm_id-3");
	if ($sud_username==""){$sud_username="Тихомировой Елене Николаевне";};
	$sud_uchastok=$cc->GetByParam("dtc-$blibase-$agrm_id-4");
	
	$pocht_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-5");
	if ($pocht_rash==""){$pocht_rash="53.65";};
	$pocht_rash=  str_replace(",", ".", $pocht_rash);
	$poshlina_summ=$cc->GetByParam("dtc-$blibase-$agrm_id-6");
	if ($poshlina_summ==""){$poshlina_summ="400.00";};
	$urist_rash=$cc->GetByParam("dtc-$blibase-$agrm_id-7");
	$dt_pol_pret=$cc->GetByParam("dtc-$blibase-$agrm_id-10");
	
    
    //
    //ищем дату возникновения задолженности
    //
	    //определяю период, когда был накоплен долг:
	    // когда последний раз был положительный баланс?
	    $last_dt_plus="";
	    $sql="select * from balances where agrm_id=$agrm_id and round(balance)>=0 order by date desc limit 1";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$last_dt_plus=$row2["date"];
	    };	
	    //если дату не удалось определить, то берем самую первую..
	    if ($last_dt_plus==""){
		$sql="select * from balances where agrm_id=$agrm_id order by date  limit 1";  
		$res2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать адрес абонента!".mysqli_error($lb->idsqlconnection)); 	
		while ($row2 = mysqli_fetch_array($res2)){ 
		    $last_dt_plus=$row2["date"];
		};		  
	    };
	    //echo "$last_dt_plus!!";
	    
	    // определяем период за который задолжал абонент
	    $sql="select date,round(balance,2) from balances where agrm_id=$agrm_id and date>'$last_dt_plus' group by round(balance,2) order by date";
//	    echo "$sql\n";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать выборку по балансу абонента!".mysqli_error($lb->idsqlconnection)); 	
	    $cn=0;
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cn++;
		if ($cn==1){$dt_s1=$row2["date"];}	    
		$dt_s2=$row2["date"]; //последняя дата изменения баланса
	    };
    ///////////////////////////
    // считаем неустойку..	
	    $sumnt=0;
	    
	    $dt_s3=DateToMySQLDateTime2($dt_create_pret);
	    $dt_s3=  str_replace(" 00:00:00", "", $dt_s3);
	    
	    $sql="select balance from balances where agrm_id=$agrm_id and date between '$dt_s1' and '$dt_s3';";
	    $res2 = $lb->ExecuteSQL($sql) or die("Не могу сделать расчет пени!".mysqli_error($lb->idsqlconnection)); 	
	    //echo "$sql";
	    //$sumnt=0;
//	    while ($row2 = mysqli_fetch_array($res2)){ 
//		$cbal=abs(round($row2["balance"],2));    
//		$peni=round(($cbal)*0.01,2);
//		$sumnt=round($sumnt+$peni,2);
//		if ($sumnt>=$balance){break;};
//	    };	  
	    
	    
	    $costob=$cfg->GetByParam("dtc-$blibase-$agrm_id-24");          
	    $flagob=0;$oldbal=0;
	    if ($costob>0) {$maxbal=abs($balance)-$costob;} else {$maxbal=abs($balance);};
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		
		if (($cbal-$oldbal==$costob) and ($costob>0)){$flagob=1;};
		if ($flagob==1){$cbal=$cbal-$costob;};
		
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
		if ($costob>0){	
		    if ($sumnt>=abs($balance)-$costob){break;};    
		};		
		if (($flagob==1) and ($sumnt+$costob>=$balance)){break;};
		
		$oldbal=$cbal;
	    };	    
	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$maxbal){$sumnt=$maxbal;}; //начисленное пени	    
	    if ($flagob==1) {$sumnt=$balance-$costob;};
	    
    //определяем когда отключен интернет
    $period_ent="";$dtvzzadint="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by period desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);
	$period_ent="- $dtvzzadint 23:59:59 было приостановлено оказание услуги доступа в сеть Интернет.";
    };        

    //определяем когда отключено ТВ	
    $period_tv="";$dtvzzadtv="";	
    //$sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc  limit 1";
    $sql="select vg_blocks_history.rasp_time from usbox_charge inner join vg_blocks_history on vg_blocks_history.vg_id=usbox_charge.vg_id inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0  and vg_blocks_history.block_id_new=10 order by usbox_charge.period,vg_blocks_history.record_id desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	$period_tv="- $dtvzzadtv было приостановлено оказание услуги предоставления вещания ТВ.";
    };        
    ////////////	
$txt_bal=num2str($balance);
$txt_sumnt=num2str($sumnt);
$txt_pocht_rash=num2str($pocht_rash);
$txt_poshlina_summ=num2str($poshlina_summ);
	
$abzac1="$dog_date г. между ООО «Мультистрим» и Абонентом (гр. $name), был заключен договор на предоставление услуг связи для физических лиц № $number, согласно которого Оператор - ООО «Мультистрим» принял на себя обязательство предоставлять Абоненту (гр. $name) услуги связи, а Абонент обязался принимать и оплачивать оказанные услуги в соответствии с условиями Договора. ";
$abzac2="По состоянию на $dt_create_pret г. за Абонентом (гр. $name) числится задолженность по оплате абонентской платы по Договору № $number от $dog_date в размере ".
        "$balance руб. ($txt_bal). Детализация лицевого счета приведена в Приложении 6 из которого видны все оказанные услуги, поступления и списания денежных средств за все время действия Договора.";
$abzac3="Счета абонентам – физическим лицам не направляются почтовым отправлением. Оплата услуг производится по квитанции, либо по лицевому счету через платежные системы или банки";
$abzac4="Квитанцию об оплате абоненты самостоятельно забирают в офисе ООО «Мультистрим» (место по работе с абонентами), либо в Личном кабинете, где происходит автоматическое формирование квитанции об оплате услуг. Скриншот распечатки квитанции через Личный кабинет прилагаем (Приложение № 9, 10).";
$abzac5="Дополнительно сообщаем, что ежемесячно всем абонентам ООО «Мультистрим» производится СМС-рассылка с указанием требуемых сумм внесения денежных средств, для дальнейшего беспрерывного оказания услуг.";
$abzac6="В настоящий момент договор на предоставление услуг связи для физических лиц (лицевой счет № $number) с абонентом ($name) не расторгнут. ".
     "Заявления о расторжении данного Договора № $number от абонента $name не поступало. У ООО «Мультистрим» нет намерения расторгать данный Договор.";
$abzac7="Согласно пп. 6.1.1. Договора на предоставление услуг связи для физических лиц Оператор имеет право (но не обязан) приостановить оказание услуг в случае обнуления баланса на Лицевом счете Абонента. По данному договору было приостановлено оказание услуги в связи с задолженностью, а именно:".
    $br.$period_ent.
    $br.$period_tv;
$abzac8="Дополнительно сообщаем, что приостановка услуг кабельного телевидения и доступа в сеть Интернет осуществлялась на основании производственного задания, "
	."санкционированного руководством ООО «Мультистрим». ";
if ($dtvzzadint==""){$zzz="Копию производственного задания на приостановку услуг от $dtvzzadtv прилагаем (Приложение № 11)";};
if ($dtvzzadtv==""){$zzz="Копию производственного задания на приостановку услуг от $dtvzzadint прилагаем (Приложение № 12)";};
if (($dtvzzadtv!="") and ($dtvzzadtv!="")) {$zzz="Копию производственного задания на приостановку услуг от $dtvzzadint и от $dtvzzadtv прилагаем (Приложение № 11,12)";};
$abzac8=$abzac8.$zzz;
$abzac8=$abzac8.$br."Также сообщаем, что Уведомление о приостановке услуги было сформировано в Личном кабинете Абонента в момент приостановки услуг и доступно по настоящее время. Скриншот уведомления в Личном кабинете Абонента прилагаем (Приложение № 8).";
$abzac9="Согласно пп. 7.2. Договора в случае неоплаты услуг Оператор вправе взыскать с Абонента неустойку в размере 1 % (одного процента) стоимости неоплаченных услуг".
	"за каждый день просрочки вплоть до погашения задолженности, но не более суммы, подлежащих оплате. ".
	"По состоянию на $dt_create_pret г. сумма неустойки составляет $sumnt руб. ($txt_sumnt). ".
	"Расчет неустойки приведен в Приложении 7.";

$abzac10="$dt_send_pret г. в адрес абонента была направлена претензия с требованием погасить заложенность по абонентской плате.";
if ($dt_pol_pret==""){$abzac10=$abzac10."Претензия абонентом получена не была.";};
if ($dt_pol_pret!=""){$abzac10=$abzac10."Претензия абонентом была получена $dt_pol_pret.";};
$abzac10=$abzac10."До настоящего момента претензия осталась без удовлетворения, вышеуказанная задолженность Абонентом не погашена.";
$abzac11="01.01.2017 г. был заключен договор на юридические услуги, согласно п. 5 которого оплата услуги  производится после предъявления  заявления о выдаче судебного приказа в суд наличными денежными средствами до момента предъявления  заявления в суд. Услуги считаются оказанными также с момента предъявления заявления в суд. Размер оплаты услуг  юриста определяется следующим образом:  50% (пятьдесят процентов) от подлежащей взысканию суммы задолженности и неустойки по договорам об оказании услуг связи, в результате выполнения п. 1 договора об оказании юридических услуг.";
$abzac12="";


if ($urist_rash==""){$urist_rash=$sumnt;};
$txt_urist_rash=num2str($urist_rash);	
$abzac13="1. Выдать судебный приказ о взыскании с Должника (гр.$name) в пользу Взыскателя ООО «Мультистрим»".$br.	
"2. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» задолженность по оплате абонентской платы, в сумме $balance руб ($txt_bal).".$br.
"3. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» неустойку в размере 1 % (одного процента) стоимости неоплаченных услуг за каждый день просрочки, в сумме $sumnt руб ($txt_sumnt).".$br.
"4. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату почтовых услуг связи, в сумме $pocht_rash руб ($txt_pocht_rash)".$br.
"5. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату государственной пошлины, в сумме $poshlina_summ руб ($txt_poshlina_summ). ".$br.
"6. Взыскать с ответчика (гр.$name) в пользу ООО «Мультистрим» расходы на оплату услуг юриста, в сумме $urist_rash руб ($txt_urist_rash). ";
"7. Выдать судебный приказ Взыскателю в сроки, установленные действующим законодательством Российской Федерации. ";
	
    $abzac1=@iconv("UTF-8", "CP1251",$abzac1);
    $abzac2=@iconv("UTF-8", "CP1251",$abzac2);
    $abzac3=@iconv("UTF-8", "CP1251",$abzac3);
    $abzac4=@iconv("UTF-8", "CP1251",$abzac4);
    $abzac5=@iconv("UTF-8", "CP1251",$abzac5);
    $abzac6=@iconv("UTF-8", "CP1251",$abzac6);
    $abzac7=@iconv("UTF-8", "CP1251",$abzac7);
    $abzac8=@iconv("UTF-8", "CP1251",$abzac8);
    $abzac9=@iconv("UTF-8", "CP1251",$abzac9);
    $abzac10=@iconv("UTF-8", "CP1251",$abzac10);
    $abzac11=@iconv("UTF-8", "CP1251",$abzac11);
    $abzac12=@iconv("UTF-8", "CP1251",$abzac12);
    $abzac13=@iconv("UTF-8", "CP1251",$abzac13);
    
    if ($mobile!=""){
	$address=$address.$br."Телефон: ".$mobile;
    };    
    $address=@iconv("UTF-8", "CP1251",$address);
    $ish_nom=@iconv("UTF-8", "CP1251",$ish_nom);
    $sud_username=@iconv("UTF-8", "CP1251",$sud_username);
    $sud_uchastok=@iconv("UTF-8", "CP1251",$sud_uchastok);
    $username=@iconv("UTF-8", "CP1251",$name);

    $shab = str_replace("Address", $address, $shab);
    $shab = str_replace("username", $username, $shab);
    $shab = str_replace("dolzhnik",$username.$br.$address, $shab);
    $shab = str_replace("ishnom", $ish_nom, $shab);
    $shab = str_replace("ishdate", $dt_send_isk, $shab);
    $shab = str_replace("sudyaname", $sud_username, $shab);
    $shab = str_replace("uchnom", $sud_uchastok, $shab);
    
    $shab = str_replace("abzac1a", $abzac1, $shab);
    $shab = str_replace("abzac2a", $abzac2, $shab);
    $shab = str_replace("abzac3a", $abzac3, $shab);
    $shab = str_replace("abzac4a", $abzac4, $shab);
    $shab = str_replace("abzac5a", $abzac5, $shab);
    $shab = str_replace("abzac6a", $abzac6, $shab);
    $shab = str_replace("abzac7a", $abzac7, $shab);
    $shab = str_replace("abzac8a", $abzac8, $shab);
    $shab = str_replace("abzac9a", $abzac9, $shab);
    $shab = str_replace("abzac10a", $abzac10, $shab);    
    $shab = str_replace("abzac11a", $abzac11, $shab);
    $shab = str_replace("abzac12a", $abzac12, $shab);
    $shab = str_replace("abzac13a", $abzac13, $shab);    
    return $shab;    
};

/**
 *  Возвращает ID клиента SBSS по ID пользователя НОС
 */
function GetSBSSClientIdByIdNOC ($sbss,$userid){
    $res["id"]="";
    $sql="SELECT * FROM clients where comments='$userid'";    
    $result2 = $sbss->ExecuteSQL($sql);
    while ($myrow2 = mysqli_fetch_array($result2)){
	$res["id"]=$myrow2["id"];
	$res["login"]=$myrow2["login"];	
    };	 
    return $res;    
};
/**
 *  Возвращает ID менеджера SBSS по ID пользователя НОС
 */
function GetSBSSManagerIdByIdNOC($sbss,$userid){
    $id="";
    $sql="SELECT * FROM managers where redmine_id='$userid'";    
    $result2 = $sbss->ExecuteSQL($sql);
    while ($myrow2 = mysqli_fetch_array($result2)){
	$id=$myrow2["id"];
    };	 
    return $id;
};

/**
 * Обнвляем у клиента SBSS его принадлежность к биллингу и его agrm_id
 * @param type $sbss
 * @param type $agrm_id
 * @param type $blibase
 */
function UpdateBillingIdSBSSClient($sbss,$agrm_id,$blibase){
    
};
/**
 *  Возвращает ID клиента SBSS по ID абонента биллинга
 *  если клиента еще нет - добавляет...
 */
function GetSBSSClientID($lb,$sbss,$agrm_id,$blibase){
	//получаем данные по клиенту из биллинга
	$sql="select usergroups.name as city,usergroups_staff.group_id,accounts.mobile,accounts_addr.address,accounts.name,agreements.number,accounts.login,accounts.pass from agreements inner join usergroups_staff on usergroups_staff.uid=agreements.uid inner join accounts on accounts.uid=agreements.uid inner join accounts_addr on accounts_addr.uid=accounts.uid inner join usergroups on usergroups.group_id=usergroups_staff.group_id where agreements.archive=0 and accounts_addr.type=1 and usergroups_staff.group_id<>0 and agreements.agrm_id='$agrm_id'";
	//echo "$sql\n";
	$result2 = $lb->ExecuteSQL($sql);
	while ($myrow2 = mysqli_fetch_array($result2)){
	  $group_id=$myrow2["group_id"];  
	  $mobile=$myrow2["mobile"];  
	  $address=$myrow2["address"];  
	  $name=$myrow2["name"];  
	  $number=$myrow2["number"];  
	  $login=$myrow2["login"];  
	  $pass=$myrow2["pass"];  
	  $city=$myrow2["city"];    
	};
	//ищем класс запроса по названию города
	$class_id="";
	$sql="SELECT * FROM client_classes where name='$city'";
	//echo "$sql\n";
	$result2 = $sbss->ExecuteSQL($sql);
	while ($myrow2 = mysqli_fetch_array($result2)){
	    $class_id=$myrow2["id"];
	};	
	//если такого класса нет, то добавляем его!
	if ($class_id==""){
	   $sql="insert into client_classes (name,color,webaccess,requests,software,knowledges,activation) values ('$city','FF00FF',1,1,1,1,1)"; 
	   $result2 = $sbss->ExecuteSQL($sql) or die ("Не смог добавить класс в базу sbss!".mysqli_connect_error());  	   
	   $sql="SELECT * FROM client_classes where name='$city'";
	   //echo "$sql\n";
	   $result2 = $sbss->ExecuteSQL($sql);
	   while ($myrow2 = mysqli_fetch_array($result2)){
		$class_id=$myrow2["id"];
	   };		   
	};
	
	//ищем такого клиента в базе клиентов sbss
	$sbss_client_id="";
	$sql="select * from clients where login='$login' and country='$number' and agrm_id='$agrm_id'";
	$result2 = $sbss->ExecuteSQL($sql);
	while ($myrow2 = mysqli_fetch_array($result2)){
	    $sbss_client_id=$myrow2["id"];
	    //ну и на всякий случай обновляем его billingid и agrm_id
	    $sql="update clients set phone='$mobile',name='$name',address='$address',group_id='$group_id',agrm_id='$agrm_id',billingid='$blibase' where id=$sbss_client_id";
	    $result123 = $sbss->ExecuteSQL($sql);
	};
	//echo "!!$sbss_client_id!!"; 
	//если не нашли - добавляем в базу sbss
	if ($sbss_client_id==""){
	//    echo "--добаваляем!!";
	  $sql="insert into clients (login,pass,created,type,classid,name,phone,country,city,address,billingid,agrm_id,group_id) values ".
				    "('$login','$pass',now(),2,$class_id,'$name','$mobile','$number','$city','$address',$blibase,$agrm_id,$group_id)";  
	  $result2 = $sbss->ExecuteSQL($sql) or die ("Не смог добавить клиента в базу sbss!".mysqli_connect_error());  	  
      	  $sql="select * from clients where login='$login' and country='$number'";
	   $result2 = $sbss->ExecuteSQL($sql)  or die ("Не смог выбрать клиента в базе sbss!".mysqli_connect_error());  	  
	    while ($myrow2 = mysqli_fetch_array($result2)){
	     $sbss_client_id=$myrow2["id"];
	    };
	};    
	
    return $sbss_client_id;
};
function get_info_manager_sbss($sbss,$author_id){
    $res=array();
    $res["result"]=false;
    $sql="SELECT * FROM clients where id=$author_id";
    $result2 = $sbss->ExecuteSQL($sql);    
    while ($myrow2 = mysqli_fetch_array($result2)){
	$res["login"]=$myrow2["login"];
	$res["email"]=$myrow2["email"];
	$res["name"]=$myrow2["name"];		
	$res["result"]=true;
    };          
    return $res;
};

function Get_responsible_man($lb,$sbss,$blibase,$agrm_id,$sbss_classes_ids){
    global $cfg;
    //echo "$blibase,$agrm_id,$sbss_classes_ids";
    // 1,15,1
  $res=false;
    //определяем какому колхозу принадлежит абонент?
    $sql="select usergroups_staff.group_id from agreements inner join usergroups_staff  on usergroups_staff.uid=agreements.uid where agreements.agrm_id=$agrm_id and usergroups_staff.group_id<>0 limit 1";
    $result2 = $lb->ExecuteSQL($sql);
    $group_id="";
    while ($myrow2 = mysqli_fetch_array($result2)){
	$group_id=$myrow2["group_id"];
    };  
    $res=8;
    if ($group_id!=""){
	$res=$cfg->GetByParam('sbss-ticket-class-user-'."$blibase-$group_id-$sbss_classes_ids");
	//это потом удалить!
    };    
  return $res;
};
/**
 *  Возвращает true если нужно при данном классе, при данном статусе отсылать..
 */
function SBSSSendByStatusid($sbss,$statusid){
  $res=false; 
    $sql="SELECT modify_allow FROM `request_statuses` where id=$statusid";
    $result2 = $sbss->ExecuteSQL($sql);    
    while ($myrow2 = mysqli_fetch_array($result2)){
	if ($myrow2["modify_allow"]==1){$res=true;};
    };      
  return $res;  
};
/**
 *  Возвращает массив информации о менеджере
 */
function GetSBSSManagerInfo($sbss,$responsible_man){
    $res=array();
    $res["result"]=false;
    $sql="SELECT * FROM managers where id=$responsible_man";    
    $result2 = $sbss->ExecuteSQL($sql);    
    while ($myrow2 = mysqli_fetch_array($result2)){
	$res["login"]=$myrow2["login"];
	$res["email"]=$myrow2["email"];
	$res["name"]=$myrow2["name"];		
	$res["result"]=true;
    };          
    return $res;
};
/**
 *  Возвращает массив информации о клиенте
 */
function GetSBSSClientInfo($sbss,$responsible_man){
    $res=array();
    $res["result"]=false;
    $sql="SELECT * FROM clients where id=$responsible_man";
    $result2 = $sbss->ExecuteSQL($sql);    
    while ($myrow2 = mysqli_fetch_array($result2)){
	$res["login"]=$myrow2["login"];
	$res["email"]=$myrow2["email"];
	$res["name"]=$myrow2["name"];		
	$res["phone"]=$myrow2["phone"];		
	$res["comments"]=$myrow2["comments"];		
	$res["numdog"]=$myrow2["country"];		
	$res["billingid"]=$myrow2["billingid"];		
	$res["agrm_id"]=$myrow2["agrm_id"];		
	$res["result"]=true;
    };          
    return $res;
};
/**
 * Получаем  массив "клиентов" на основании связи с "менеджерами"
 * @param type $sbss
 * @param type $sbss_managers
 */
function GetClientsFromManagers($sbss,$sbss_managers){
    $res=array();
    $sql="select clients.id from clients inner join managers on managers.redmine_id=clients.comments where managers.id in($sbss_managers)  and managers.redmine_id<>'';";    
    $result2 = $sbss->ExecuteSQL($sql);    
    while ($myrow2 = mysqli_fetch_array($result2)){
	$res[]=$myrow2["id"];
    };
    return $res;
};
/**
 * ПОлучаем массив с основной информацией о свиче
 * как то: IP, комьюнити, MAC
 * @param type $lb
 * @param type $device_id
 */
function GetCommonInfoSw($lb,$device_id){
    $rez["community"]="";
    $rez["ip"]="";
    $rez["result"]=false;
    $sql="select devices_options.value as ip,device_status.param_value,devices.device_id,devices.device_name,devices.address from devices inner join device_status on device_status.device_id=devices.device_id inner join devices_options on devices_options.device_id=devices.device_id where devices.tpl=0 and device_status.param_name='Uptime' and devices_options.name='IP' and devices.device_id='$device_id'";
    $result2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать инф. по свичу!".mysqli_error($lb->idsqlconnection));
        while($row2 = mysqli_fetch_array($result2)) {
            $rez["ip"]=$row2["ip"];
	    $rez["result"]=true;	    
        };              
    $sql="select devices_options.value as ip,device_status.param_value,devices.device_id,devices.device_name,devices.address from devices inner join device_status on device_status.device_id=devices.device_id inner join devices_options on devices_options.device_id=devices.device_id where devices.tpl=0 and device_status.param_name='Uptime' and devices_options.name='Community' and devices.device_id='$device_id'";
    $result2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать инф. по свичу!".mysqli_error($lb->idsqlconnection));
        while($row2 = mysqli_fetch_array($result2)) {
            $rez["community"]=$row2["ip"];
	    $rez["result"]=true;
        };                  
  return $rez;
};
/**
 * Возвращаем массив из портов, с их состоянием
 * @param type $lb - связь MySQL биллинг
 * @param type $device_id - ид свича
 */
function GetPortsInfoSW($ip,$community){
  $rez_p["result"]=false;	
	$run="/usr/local/bin/snmpwalk -v2c -c ".$community." ".$ip." 1.3.6.1.2.1.2.2.1.8";
        $res=shell_exec($run);            
	if ($res[0]==""){
	    $run="snmpwalk -v2c -c ".$community." ".$ip." 1.3.6.1.2.1.2.2.1.8";
	    $res=shell_exec($run);            	    
	};
        if ($res[0]!=""){                           
		 $st_ar = explode("\n",$res);                                  	    
                 for ($i=0;$i<count($st_ar)-1;$i++){
		     $st_port=$st_ar[$i];
		     //разделяем по признаку = INTEGER:
		     $lp=explode("= INTEGER:",$st_port);
		     if (trim($lp[1])=="down(2)"){$lp[1]="2";};
		     if (trim($lp[1])=="up(1)"){$lp[1]="1";};
		     $rez_p["port"][$i+1]=$lp[1];
		     $rez_p["result"]=true;
		 };	    
        };
return $rez_p;
};
function GetSnmpModelSW($ip,$community){
    $rez_p["result"]=false;	
    $rez_p["model"]="";
	$run="/usr/local/bin/snmpwalk -v2c -c ".$community." ".$ip." .1.3.6.1.2.1.1.1.0";
        $res=shell_exec($run);                
	$st_arr=  explode("\n", $res);
	$lp=explode("= STRING:",$st_arr[0]);
	if (isset($lp[1])==true){
	    $rez_p["result"]=true;	    	    
	    $rez_p["model"]=trim($lp[1]);	    
	};
    return $rez_p;    
};
function GetDeviceIdByVgid($lb,$vg_id){
   $res=false;
   $sql="select device_id from ports where vg_id=$vg_id";   
   $result2 = $lb->ExecuteSQL($sql) or die("Не могу выбрать инф. по свичу!".mysqli_error($lb->idsqlconnection));
    while($row2 = mysqli_fetch_array($result2)) {
	$res=$row2['device_id'];	   
    };
    return $res;
};
/**
 * Разделяем mac адрес разделителями
 * @param type $mac
 * @return type
 */
function ParseMacRaw($mac){
 $mac=mb_strtolower($mac);
 $newmac=$mac;
 #4C5E0CB72E52
 if (strlen($newmac)==12){
   $newmac=$mac[0].$mac[1].":".$mac[2].$mac[3].":".$mac[4].$mac[5].":".$mac[6].$mac[7].":".$mac[8].$mac[9].":".$mac[10].$mac[11];
 };
 return $newmac;
};

function GetCoorByAddress($address){
        $address=  str_replace("п Лоста", "мкр Лоста", $address);
	$address=  str_replace("Россия,обл Вологодская,р-н Вологодский,","",$address);
	$address=  str_replace(",,,,160000","",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Кадуйский,,пгт Кадуй,,,,,,","рабочий поселок Кадуй,",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Кадуйский,,пгт Кадуй,ул Рукавицкая,","Россия, Вологодская область, Кадуйский район, рабочий посёлок Кадуй, Рукавицкая,",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Кадуйский,,пгт Кадуй,ул Рукавицкая Надежда","поселок Кадуй, ул Надежды,",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Кадуйский,,пгт Кадуй,,,,,,","поселок Кадуй",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Кадуйский,,пгт Кадуй,","поселок Кадуй,",$address);
	$address=  str_replace("Россия,обл Вологодская,р-н Чагодощенский,,рп Чагода","поселок Чагода",$address);
	$address=  str_replace(",,,,162400","",$address);	        
        $url="https://geocode-maps.yandex.ru/1.x/?geocode=$address&format=json&results=1";
        $rn="wget '$url' --no-check-certificate -O /tmp/coor.log";	
        `$rn`;
        $fh = fopen("/tmp/coor.log", 'r');
        $code = '';
            while(!feof($fh)) $code .= fread($fh, 1024);
            fclose($fh);
        $res=json_decode($code);  
        $coor="";
        foreach ($res as $key=>$value) {
            foreach ($value as $key=>$value1) {
                foreach ($value1 as $key=>$value2) {
                     if ($key=="featureMember"){
                         foreach ($value2 as $key=>$value3) {
                            foreach ($value3 as $key=>$value4) {
                                foreach ($value4 as $key=>$value5) {
                                    if ($key=="Point"){
                                        foreach ($value5 as $key=>$value6) {
                                            $coor=$value6;
					    break;
                                        };
					if ($coor!="") break;
                                    };
				    if ($coor!="") break;
                                };
				if ($coor!="") break;
                            };
			    if ($coor!="") break;
                         };
			 if ($coor!="") break;
                     };
		     if ($coor!="") break;
                };
		if ($coor!="") break;
            };
	    if ($coor!="") break;
        };
    $zxa=  explode(" ", $coor);
    $cor["x"]=$zxa[1];
    $cor["y"]=$zxa[0];
    return $cor;
}
/**
 * 
 * @param type $cn_arr
 * [cn] - соеднение
 * [agrm_id] - абонент. Если его нет, будем искать по number
 * [number] - абонент.Если его нет, будем искать по agrm_id
 * [manager] - от кого проводим платеж
 * [summ] - сумма платежа
 * [comment]- комментарий к платежу
 * Возврат:
 * [result] - результат выполнения
 * 0 - всё ок
 * или текстовое сообщение об ошибке
 */
function InsertPayments($cn_arr){    
    $res=array();
    $res["result"]="0";
    if (isset($cn_arr["number"])==true){
	$number=$cn_arr["number"];
	$sql="select agrm_id from agreements where number='$number'";
	$result2 = $cn_arr["cn"]->ExecuteSQL($sql) or die("Не могу выбрать информацию по абоненту!".mysqli_error($lb->idsqlconnection));
	    while($row2 = mysqli_fetch_array($result2)) {
		$cn_arr["agrm_id"]=$row2["agrm_id"];
	    };                  	
    };
    $lb=$cn_arr["cn"];
    if (isset($cn_arr["agrm_id"])==true){    
	if (isset($cn_arr["manager"])==true){    	
	    if (isset($cn_arr["summ"])==true){  
			$agrm_id=$cn_arr["agrm_id"];
			$amount=$cn_arr["summ"];
			$uniid=GetRandomId(20);			
			$manager_id=$cn_arr["manager"];
			if (isset($cn_arr["comment"])==true){$comment=$cn_arr["comment"];} else {$comment="";};
			$err="";
			    //пробуем провести платеж
			    $lb->start_transaction();
			    $sql="INSERT INTO payments (agrm_id,amount,comment,receipt,pay_date,local_date,status,mod_person,amount_cur,amount_cur_id,class_id) VALUES ".
				    "('$agrm_id','$amount','$comment','$uniid',now(),now(),0,'$manager_id','$amount',1,0)";
			    if ($err==""){
				$result2 = $lb->ExecuteSQL($sql);  
				if ($result2=='') {$err="Error!";};
			    };
			    $sql="UPDATE agreements SET balance=balance+$amount where agrm_id='$agrm_id'";                        
			    if ($err==""){
				$result2 = $lb->ExecuteSQL($sql);  
				if ($result2=='') {$err="Error!";};
			    };
			    $dtn=Date("Y-m-d");;
			    $sql="UPDATE balances SET balance=balance+$amount where agrm_id='$agrm_id' and date='$dtn'";                        
			    if ($err==""){
				$result2 = $lb->ExecuteSQL($sql);  
				if ($result2=='') {$err="Error!";};
			    };
			    if ($err==""){
			      $lb->commit();  
			    } else {                
			      $lb->rollback();  
			      $res["result"]="Ошибка занесения платежа:".$err;	
			      $err="false";
			    };		
	    } else {
		$res["result"]="Не указан summ для проведения платежа";	
	    };		    
	} else {
	    $res["result"]="Не указан manager для проведения платежа";	
	};	
    } else {
	$res["result"]="Не указан agrm_id для проведения платежа";	
    };
    return $res;
};

function ParseInput($inp){
    //var_dump($inp);
    $rz="";
    foreach ($inp as $value) {	
	if (strpos($value,"dvb")===false){
	 $rz=$rz.'<span class="label label-default">'.$value.'</span></br>';
	} else {
	    $dva=explode("#", $value);
	    $rz=$rz.'<span class="label label-default">'.$dva[0].'</span>';	    
	    if (isset($dva[1])){
	     $pca=  explode("&", $dva[1]);
	     foreach ($pca as $value) {
		$val=  explode("=", $value);
		 if ($val[0]=="pnr"){
		     $hx=dechex($val[1]);
		     $rz=$rz.'<span class="label label-primary">'.'PNR='.$val[1]."($hx)".'</span>';
		 };
		 if ($val[0]=="cam"){
		     $rz=$rz.'<span class="label label-success">CAM='.$val[1].'</span>';
		 };		 
	     };
	    };
	};	
    };
    return $rz;
};
/**
 * Сокращение полного имени до Фамилия И.О.
 * @param type $fio
 * @return string
 */
function smallfio($fio){
  $ret="";
  $arr=  explode(" ", $fio);
  //var_dump($arr);
  if (isset($arr[0]) and isset($arr[1]) and isset($arr[2])){
    $arr[1]=trim($arr[1]);
    $arr[2]=trim($arr[2]);
    $ret=$arr[0]." ".mb_substr($arr[1],0,2).".".mb_substr($arr[2],0,2).".";
  } else
  if (isset($arr[0]) and isset($arr[1])){
    $ret=$arr[0]." ".mb_substr($arr[1],0,2).".";
  };  
  return $ret;
};
/** Получение значения хранимого параметра по имени параметра
 * 
 * @param type $nameparam - имя параметра
 * @return type
 */
function GetByParam($noc,$nameparam) {
	// получаем данные по идентификатору
	$resz = "";
	$result = $noc->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='$nameparam'")
			or die('Неверный запрос Tcconfig.GetByParam: '.mysqli_error($sqlcn->idsqlconnection));
	$row = mysqli_fetch_array($result);
	$cnt = count($row);
	// или добавляем настройки или выдаем параметр
	if ($cnt == 0) {
		$noc->ExecuteSQL("INSERT INTO config_common (nameparam,valueparam) VALUES ('$nameparam','')");
	}
	$result = $noc->ExecuteSQL("SELECT * FROM config_common WHERE nameparam='$nameparam'")
			or die('Неверный запрос Tcconfig.GetByParam: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$resz = $myrow['valueparam'];
	}
	return $resz;
}

function GetBillingConnect($blibase) {
    global $sqlcn;
    $SQL = "SELECT * FROM lbcfg where id='$blibase'";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список серверов LanBilling!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
	$host=$row["host"];   
	$basename=$row["basename"];   
	$username=$row["username"];   
	$pass=$row["pass"];          
    };
    // соединяемся с базой
    $zb=new Tsql();
    $zb->connect($host,$username,$pass,$basename);    
    return $zb;
}
function Diffuse($label,$cnt){
    $mass=  explode(",", $label);
    //var_dump($mass);
    $pz=round(count($mass)/$cnt); //каждый какой оставляем?
    //echo "---- $pz --------";
    $zz=$pz;
    foreach ($mass as &$value) {
	if ($zz==$pz){
	  $zz=0;	  
	} else {
	    $value="\"\"";
	};
	$zz++;
    };
    //var_dump($mass);
    $ext="";
    foreach ($mass as &$value) {
	$ext=$ext.$value.',';
    };
    $ext=substr($ext,0,strlen($ext)-1);
    return $ext;
};
/**
 * Получаем группу пользователя биллинга по его agrm_id
 * @param type $lb -биллинг
 * @param type $agrm_id - agrm_id
 */
function GetUserGroupByAgrm_id($lb,$agrm_id){
  $res=0;
  $sql="select group_id from usergroups_staff where group_id<>0 and uid in (select uid from agreements where agrm_id=$agrm_id)";
  $result = $lb->ExecuteSQL($sql) or die("Неверный запрос GetUserGroupByAgrm_id ($sql): ".mysqli_error($lb->idsqlconnection));
  while ($myrow = mysqli_fetch_array($result)) {
    $res = $myrow['group_id'];
  };
  return $res;  
};
/**
 * Извлекаем шаблон по типу и биллингу
 * @param type $billing
 * @param type $typeshab
 */
function GetShabSMS($blibase,$shablon){
    global $sqlcn;
    $res="";
    $SQL = "SELECT * FROM lanbsmstempl where (blibase='$blibase') and (typetmp='$shablon')";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список шаблонов!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
      $res=$row["txt"];   
    };    
    return $res;
};
function GetCollectorStatus($st){
    switch ($st) {
	case "-1":$st="Взыскание не ведется";break;
	case "-2":$st="Истек срок исковой давности";break;
	case "0":$st="Сбор документов";break;
	case "1":$st="Дело в суде";break;
	case "2":$st="Дело рассмотрено в суде";break;
	case "3":$st="Абонент полностью погасил долг";break;
	default:
	    break;
    } 
  return $st;  
};
/**
 * Получаем колхоз по свичу
 * @param type $lb
 * @param type $vg_id
 */
function GetGroupIdBySW($lb,$billingid,$vg_id){
    $res=0;
    $sql="select group_id from device_groups_members where device_id in (select device_id from ports where vg_id=$vg_id)";
    $result = $lb->ExecuteSQL($sql) or die("Неверный запрос GetUserGroupByAgrm_id ($sql): ".mysqli_error($lb->idsqlconnection));
    while ($myrow = mysqli_fetch_array($result)) {
	$gr = $myrow['group_id'];
    };
    
    if (($billingid==2) and ($gr==1)){$res=2;};
    if (($billingid==2) and ($gr==2)){$res=1;};
    if (($billingid==2) and ($gr==4)){$res=4;};
    if (($billingid==2) and ($gr==5)){$res=3;};
    if (($billingid==2) and ($gr==6)){$res=6;};
    if (($billingid==2) and ($gr==9)){$res=8;};

    if (($billingid==1) and ($gr==1)){$res=2;};
    if (($billingid==1) and ($gr==2)){$res=3;};
    if (($billingid==1) and ($gr==3)){$res=2;};
    if (($billingid==1) and ($gr==4)){$res=5;};
    if (($billingid==1) and ($gr==5)){$res=6;};
    if (($billingid==1) and ($gr==6)){$res=11;};
    if (($billingid==1) and ($gr==7)){$res=8;};
    if (($billingid==1) and ($gr==9)){$res=13;};
    
    return $res;
    
};
function GetUbntInfo($resa){
    global $sqlcn;   
    $res=array();
    $res["result"]=false;
    $connection=ssh2_connect($resa["ip"], 22);    
    if ($connection!=false){
	if (ssh2_auth_password($connection, $resa["username"], $resa["userpass"])) {
	    //Цикл 1
	      $stream = ssh2_exec($connection, "ubntbox statuss");
	      stream_set_blocking($stream,true); 
	      $cmd="";
	      while (!feof($stream)) {
		$cmd.=fread($stream,4096); 
	      }
	      $cmd=  json_decode($cmd);	
	      if ($cmd!=""){			
		    if (isset($cmd->firmware->version)==true){
			$res["firmware"]=$cmd->firmware->version;		    
		    };
		    if (isset($cmd->interfaces[0]->status->speed)==true){
			$res["speed"]=$cmd->interfaces[0]->status->speed;
		    };
		foreach ($cmd->interfaces as $value) {
		  if ($value->ifname=="ath0"){
		      $res["ssid"]=$value->wireless->essid;
		      $res["mac"]=$value->hwaddr;
		      $res["frequency"]=$value->wireless->frequency;
		      $res["channel-width"]=$value->wireless->chwidth;		
		      $res["result"]=true;
		  };
		};
	      } else {
		  // 2-й способ!
		  $stream = ssh2_exec($connection, "/usr/www/./status.cgi");
		    stream_set_blocking($stream,true); 
		    $cmd="";
		    while (!feof($stream)) {
		      $cmd.=fread($stream,4096); 
		    }
		    $cmd=trim(str_replace("Content-Type: application/json", "", $cmd));		    
		    $cmd=  json_decode($cmd);	     
		  if ($cmd!=""){
		      $res["firmware"]=$cmd->host->fwversion;
		      $res["ssid"]=$cmd->wireless->essid;
		      $res["mac"]=$cmd->wireless->apmac;
		      $res["frequency"]=str_replace(" MHz","",$cmd->wireless->frequency);
		      $res["channel-width"]=$cmd->wireless->chwidth;	
		      if (isset($cmd->interfaces[1]->status->speed)==true){
			  $res["speed"]=$cmd->interfaces[1]->status->speed;
		      };
		      $res["result"]=true;
		  } else {
		    echo "-Ошибка получения данных (оба способа не сработали)<br/>"; 
		  };  
		  
	      };	      
	} else {	
	    echo "-не смог соединиться с $resa[ip] по ssh (ubnt). Возможно не верен логин-пароль $resa[username], $resa[userpass]<br/>";
        };

    } else {
	echo "-не смог соединиться с $resa[ip] по ssh (ubnt)<br/>";
    };
    return $res;
};
function GetMicrotickInfo($resa){
    global $sqlcn;   
    $res=array();
    $res["result"]=false;
    $API = new RouterosAPI();
    $API->debug = false;    
    if ($API->connect($resa["ip"], $resa["username"], $resa["userpass"])) {	
	//echo "-connect $resa[ip], $resa[username], $resa[userpass]";
	   //определяем частоту базы и МАС
	   $API->write('/interface/wireless/print');
	   $READ = $API->read(false);
	   $ARRAY = $API->parseResponse($READ); 
	   //echo "<pre>";var_dump($ARRAY);echo "</pre>";
	   foreach ($ARRAY as $value) {
	   if (isset($value["running"])=="true"){	         
		 $res["result"]=true;
		 if (isset($value["frequency"])==true){
			$frequency=$value["frequency"];
			$channel_width=$value["channel-width"];
			if ($channel_width=="20/40mhz-ht-above"){$frequency=$frequency+10;};
			if ($channel_width=="20/40mhz-ht-belowe"){$frequency=$frequency-10;};
			if ($channel_width=="20/40mhz-Ce"){$frequency=$frequency+10;};
			if ($channel_width=="20/40mhz-eC"){$frequency=$frequency-10;};	   		     
			if ($channel_width=="20mhz"){$frequency=$frequency-0;};	   		     
			$res["frequency"]=$frequency;
			$res["channel-width"]=$value["channel-width"];
			$res["mac"]=$value["mac-address"];		
			$res["ssid"]=$value["ssid"];		
		     $res["result"]=true;
		 };
	    };
	   };
	    //количество абонентов и худший сигнал
	   $API->write('/interface/wireless/registration-table/print');
	   $READ = $API->read(false);
	   $ARRAY = $API->parseResponse($READ);              	   
	   $ucnt=0;$low=1000;
	   foreach ($ARRAY as $value) {
	    $ucnt++;   
	    if (isset($value["signal-strength-ch0"])) if ($value["signal-strength-ch0"]<$low){$low=$value["signal-strength-ch0"];};
	    if (isset($value["tx-signal-strength-ch0"])) if ($value["tx-signal-strength-ch0"]<$low){$low=$value["tx-signal-strength-ch0"];};
	   };
	   $res["min_db"]=$low;		
	   $res["usercount"]=$ucnt;		
	    //модель оборудования	    
	   $API->write('/system/routerboard/print');
	   $READ = $API->read(false);
	   $ARRAY = $API->parseResponse($READ);              
	     if (isset($ARRAY[0])==true){
		 $model=$ARRAY[0]["model"];
		 $firmware=$ARRAY[0]["current-firmware"];
		$res["model"]=$model;		
		$res["firmware"]=$firmware;				 
	     };	   
	   //определяем скорость интерфейса  
	   $API->write('/interface/ethernet/print');
	   $READ = $API->read(false);
	   $ARRAY = $API->parseResponse($READ);              	    
	     if (isset($ARRAY[0])==true){
		 $speed=$ARRAY[0]["speed"];		
		$res["speed"]=$speed;				 		 
	     };	     	    	     
	   $API->disconnect();	
    } else {
	echo "-не удалось соедениться с микротиком. Или закрыто api или логин-пароль не верен<br/>";
    };
    return $res;
};

/**
 * Получаю информацию о WiFi устройстве
 * @global type $sqlcn
 * @param type $resa
 * @return type
 */
function GetInfoWiFiDevice($resa){    
    global $sqlcn;        
    $res=array();
    $res["result"]=false;
    $lb=  GetBillingConnect($resa["billingid"]);
    $cminfo=GetCommonInfoSw($lb,$resa["device_id"]);
    if ($cminfo["result"]==true){
	$resa["ip"]=$cminfo["ip"];
	$resa["community"]=$cminfo["community"];
	if (strpos($resa["name"],"ubnt")===false){
	 //это микротик
	    $res=GetMicrotickInfo($resa);
	} else {
	  //это ubnt  или чтото другое
	  $res=GetUbntInfo($resa);
	};
    };
    return $res;
};
/**
 * Получаем информацию об устройстве WiFi по изевстному id из таблицы lanb_microtick
 * @global type $sqlcn
 * @param type $id - id устройства
 * @return type
 */
function RefreshByDeviceIsWiFi($id){
    global $sqlcn;
    $res=array();
    $res["result"]=false;
    $sql="select * from  lanb_microtick where id=$id";
    $result3 = $sqlcn->ExecuteSQL($sql);
    if ($result3=='') die('Ошибка выборки БД для проверки: ' . mysqli_error($sqlcn->idsqlconnection));        
    while ($myrow3 = mysqli_fetch_array($result3)){
	$resa=array();
	$resa["billingid"]=$myrow3["billingid"];
	$resa["device_id"]=$myrow3["device_id"];
	$resa["name"]=$myrow3["name"];
	$resa["username"]=$myrow3["username"];
	$resa["userpass"]=$myrow3["userpass"];
	$resa["active"]=$myrow3["active"];	
	$res=GetInfoWiFiDevice($resa);
    };
    return $res;    
};
function NormalizeMobile($mobile){
  $ret=$mobile;
    if (strlen($mobile)==10){
	$ret="7".$mobile;
    } else {
	if (strlen($mobile)==11){
	    if ($mobile[0]=="8"){
		$mobile[0]="7";$ret=$mobile;	    
	    };	
	};  
    };
    return $ret;
};    
?>