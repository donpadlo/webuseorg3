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
?>
