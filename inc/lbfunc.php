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
          $address=str_replace(",,", ",", $address);            
          $address=str_replace(",,", ",", $address);     
	  return $address;
};
function GetBodyIskShab($lb,$blibase,$agrm_id,$shab){    
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
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
	    };	    	       	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$balance){$sumnt=$balance;}; //начисленное пени
	    
    //определяем когда отключен интернет
    $period_ent="";	
    $sql="select * from rentcharge where agrm_id=$agrm_id and amount>0 order by period desc limit 1";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания Интернет!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){ 
	$dtvzzadint=MySQLDateTimeToDateTimeNoTime($row2["period"]);
	$period_ent="- $dtvzzadint 23:59:59 было приостановлено оказание услуги доступа в сеть Интернет.";
    };        

    //определяем когда отключено ТВ	
    $period_tv="";	
    $sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc  limit 1";
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
function GetBodyShab($lb,$blibase,$agrm_id,$shab){    
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
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
	    };	    	       	    
	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$balance){$sumnt=$balance;}; //начисленное пени

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
    $sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc limit 1";
    //echo "$sql!";
    $res12 = $lb->ExecuteSQL($sql) or die("Не могу выбрать дату последнего списания TV!".mysqli_error($lb->idsqlconnection));     
    while ($row2 = mysqli_fetch_array($res12)){     	
	$dtvzzadtv=MySQLDateTimeToDateTimeNoTime($row2["rasp_time"]);    
	$period_tv="$dtvzzadtv было приостановлено оказание услуги предоставления вещания ТВ.";
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
    $abzac6="В случае Вашей неоплаты оказанных услуг в ближайшее время, мы будем вынуждены обратиться в суд для защиты своих ".
	    "имущественных прав с требованиями принудительного взыскания образовавшийся задолженности. В этом случае Вам будет ".
	    "предъявлена общая сумма задолженности с учетом неустойки, рассчитанной на дату подачи иска. Так же на Вас будут ".
	    "возложены судебные расходы (госпошлина, стоимость юридических услуг, почтовые и иные расходы).";
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
    $period_ent="";	
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
    $sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc limit 1";
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
function Get_order_of_court($lb,$blibase,$agrm_id,$shab){
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
	    while ($row2 = mysqli_fetch_array($res2)){ 
		$cbal=abs(round($row2["balance"],2));    
		$peni=round(($cbal)*0.01,2);
		$sumnt=round($sumnt+$peni,2);
		if ($sumnt>=$balance){break;};
	    };	    	       	    
	    //echo "!!$sumnt!!";
	    if ($sumnt>$balance){$sumnt=$balance;}; //начисленное пени
	    
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
    $sql="select rasp_time from vg_blocks_history where vg_id in (select usbox_charge.vg_id from usbox_charge inner join usbox_services on usbox_services.serv_id=usbox_charge.serv_id inner join categories on categories.tar_id=usbox_services.tar_id where usbox_charge.agrm_id=$agrm_id and usbox_charge.amount>0 and usbox_services.cat_idx=categories.cat_idx and categories.common<>0 order by usbox_charge.period) and block_id_new=10 order by record_id desc  limit 1";
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
$abzac11="01.03.2016 г. был заключен договор на юридические услуги, согласно п. 5 которого оплата и подписание актов выполненных работ производится после выполнения работ. Услуги считаются оказанными в день вынесения Мировым судом судебного приказа о взыскании задолженности, размер оплаты услуг  юриста зависит от взысканной судом суммы, тогда же подписывается и акт выполненных работ.";
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
 *  Возвращает ID клиента SBSS по ID абонента биллинга
 */
function GetSBSSClientID($lb,$sbss,$agrm_id){
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
	$sql="select * from clients where login='$login' and country='$number'";
	$result2 = $sbss->ExecuteSQL($sql);
	while ($myrow2 = mysqli_fetch_array($result2)){
	    $sbss_client_id=$myrow2["id"];
	};
	//echo "!!$sbss_client_id!!"; 
	//если не нашли - добавляем в базу sbss
	if ($sbss_client_id==""){
	//    echo "--добаваляем!!";
	  $sql="insert into clients (login,pass,created,type,classid,name,phone,country,city,address) values ".
				    "('$login','$pass',now(),2,$class_id,'$name','$mobile','$number','$city','$address')";  
	  $result2 = $sbss->ExecuteSQL($sql) or die ("Не смог добавить клиента в базу sbss!".mysqli_connect_error());  	  
      	  $sql="select * from clients where login='$login' and country='$number'";
	   $result2 = $sbss->ExecuteSQL($sql)  or die ("Не смог выбрать клиента в базе sbss!".mysqli_connect_error());  	  
	    while ($myrow2 = mysqli_fetch_array($result2)){
	     $sbss_client_id=$myrow2["id"];
	    };
	};    
	
    return $sbss_client_id;
};
function get_info_manager_sbss($sbss,$responsible_man){
    $res=false;
    return $res;
};

function Get_responsible_man($lb,$sbss,$blibase,$agrm_id,$sbss_classes_ids){
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
    if ($group_id!=""){
	switch ($blibase) {
	  //Вологда  
	  case 1:
	    switch ($group_id) {	      
		//Шексна
		case 1:
		    switch ($sbss_classes_ids) {
			case 1 :$res=4;break; 		    // общие вопросы
			case 5 :$res=4;break; 		    // ТВ/Видео
			case 6 :$res=4;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      
		break;  
		//Лоста
		case 2:
		    switch ($sbss_classes_ids) {
			case 1 :$res=37;break; 		    // общие вопросы
			case 5 :$res=37;break; 		    // ТВ/Видео
			case 6 :$res=37;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    
		break; 
		//Кадуй
		case 3:
		    switch ($sbss_classes_ids) {
			case 1 :$res=16;break; 		    // общие вопросы
			case 5 :$res=16;break; 		    // ТВ/Видео
			case 6 :$res=16;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    
		    
		break; 
		//Чагода
		case 5:
		    switch ($sbss_classes_ids) {
			case 1 :$res=48;break; 		    // общие вопросы
			case 5 :$res=48;break; 		    // ТВ/Видео
			case 6 :$res=48;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    
		break;  
		//Вытегра
		case 6:
		    switch ($sbss_classes_ids) {
			case 1 :$res=35;break; 		    // общие вопросы
			case 5 :$res=35;break; 		    // ТВ/Видео
			case 6 :$res=35;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    
		break;   
		// Хохлово
		case 8:
		    switch ($sbss_classes_ids) {
			case 1 :$res=16;break; 		    // общие вопросы
			case 5 :$res=16;break; 		    // ТВ/Видео
			case 6 :$res=16;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    
		break;    
		// Нифантово
		case 10:
		    switch ($sbss_classes_ids) {
			case 1 :$res=4;break; 		    // общие вопросы
			case 5 :$res=4;break; 		    // ТВ/Видео
			case 6 :$res=4;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    
		break;    
		// Устюжна
		case 11:
		    switch ($sbss_classes_ids) {
			case 1 :$res=58;break; 		    // общие вопросы
			case 5 :$res=58;break; 		    // ТВ/Видео
			case 6 :$res=58;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    
		break;    
		// Юрлица
		case 12:
		    switch ($sbss_classes_ids) {
			case 1 :$res=8;break; 		    // общие вопросы
			case 5 :$res=43;break; 		    // ТВ/Видео
			case 6 :$res=3;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    
		break;    	    
		// Тотьма
		case 13:
		    switch ($sbss_classes_ids) {
			case 1 :$res=60;break; 		    // общие вопросы
			case 5 :$res=60;break; 		    // ТВ/Видео
			case 6 :$res=60;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    
		break;    	    
	    
	    };	      
	  break;
	  //Ярославль
	  case 2:
	    switch ($group_id) {	      
		//Семибратово
		case 1:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=39;break; 		    // общие вопросы
			case 5 :$res=39;break; 		    // ТВ/Видео
			case 6 :$res=39;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    
		break;    
	    // Ям
		case 2:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=12;break; 		    // общие вопросы
			case 5 :$res=12;break; 		    // ТВ/Видео
			case 6 :$res=12;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    
		break;    
	    // Углич
		case 3:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=9;break; 		    // общие вопросы
			case 5 :$res=9;break; 		    // ТВ/Видео
			case 6 :$res=9;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    
		break;    
	    //Констант
		case 4:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=14;break; 		    // общие вопросы
			case 5 :$res=14;break; 		    // ТВ/Видео
			case 6 :$res=14;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    
		break;    
	    //Фомиское
		case 5:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=14;break; 		    // общие вопросы
			case 5 :$res=14;break; 		    // ТВ/Видео
			case 6 :$res=14;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    
		    
		break;    
	    //Данилов
		case 6:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=13;break; 		    // общие вопросы
			case 5 :$res=13;break; 		    // ТВ/Видео
			case 6 :$res=13;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    
		    
		break;    
	    //Мышкин
		case 8:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=9;break; 		    // общие вопросы
			case 5 :$res=9;break; 		    // ТВ/Видео
			case 6 :$res=9;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    
		    
		break;    
	    //ЮрЛица
		case 9:	      
		    switch ($sbss_classes_ids) {
			case 1 :$res=8;break; 		    // общие вопросы
			case 5 :$res=8;break; 		    // ТВ/Видео
			case 6 :$res=8;break; 		    // Интернет
			case 7 :$res=8;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    
		break;    	    
	    };	      
	  break;
	  //Череповец
	  case 3:
	    switch ($group_id) {	      
	      //Череповец
		case 2:
		    switch ($sbss_classes_ids) {
			case 1 :$res=11;break; 		    // общие вопросы
			case 5 :$res=11;break; 		    // ТВ/Видео
			case 6 :$res=11;break; 		    // Интернет
			case 7 :$res=11;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    		    
		break;
	    //Расторгнутые
		case 3:
		    switch ($sbss_classes_ids) {
			case 1 :$res=11;break; 		    // общие вопросы
			case 5 :$res=11;break; 		    // ТВ/Видео
			case 6 :$res=11;break; 		    // Интернет
			case 7 :$res=11;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    		    		    
		break;
	    //Не платящие
		case 4:
		    switch ($sbss_classes_ids) {
			case 1 :$res=11;break; 		    // общие вопросы
			case 5 :$res=11;break; 		    // ТВ/Видео
			case 6 :$res=11;break; 		    // Интернет
			case 7 :$res=11;break; 		    // Биллинг
			case 8 :$res=6;break; 		    // Жалобы
			default: $res=8;break; 		    // Не понятный запрос
		    };	      		    		    		    		    		    		    		    
		break;	    
	    };	      	      
	  break;
      
	};	
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
	$res["comments"]=$myrow2["comments"];		
	$res["result"]=true;
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

?>
