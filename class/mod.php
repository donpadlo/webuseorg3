<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tmod
{
    var $id;     // уникальный идентификатор
    var $name;   // наименование модуля
    var $comment;// краткое описание модуля
    var $copy;   // какиенить копирайты, например автор, ссылка на сайт автора и т.п.
    var $active; // 1 - включен, 0 - выключен 

// Регистрируем модуль в системе    
function Register($name,$comment,$copy){
global $sqlcn;
    $modname='modulename_'.$name;
    $result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='$modname'");        
    if ($result!=''){        
        $cnt=0;
        // проверяем, а может модуль уже зарегестрирован? Если нет, то только тогда его заносим в базу 
        while ($myrow = mysqli_fetch_array($result)){$cnt=1;} 
        if ($cnt==0){
           // записываем что такой модуль вообще есть, но не активен
           $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (id,nameparam,valueparam) VALUES (null,'$modname','0')");     
           // записываем его $comment
           $modcomment='modulecomment_'.$name;
           $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (id,nameparam,valueparam) VALUES (null,'$modcomment','$comment')");     
           // записываем его $copy
           $modcopy='modulecopy_'.$name;
           $result = $sqlcn->ExecuteSQL("INSERT INTO config_common (id,nameparam,valueparam) VALUES (null,'$modcopy','$copy')");                
        };
    } else {die('Неверный запрос Tmod.Register: ' . mysqli_error($sqlcn->idsqlconnection));}
}    
// Активируем модуль в системе
function Activate($name){
global $sqlcn;
    $modname='modulename_'.$name;
    $result = $sqlcn->ExecuteSQL("UPDATE config_common SET valueparam='1' WHERE nameparam ='$modname'");        
    if ($result==''){die('Неверный запрос Tmod.Activate: ' . mysqli_error($sqlcn->idsqlconnection));};    
}    

// ДеАктивируем модуль в системе
function DeActivate($name){
global $sqlcn;
    $modname='modulename_'.$name;
    $result = $sqlcn->ExecuteSQL("UPDATE config_common SET valueparam='0' WHERE nameparam ='$modname'");        
    if ($result==''){die('Неверный запрос Tmod.DeActivate: ' . mysqli_error($sqlcn->idsqlconnection));};    
}    

// проверяем включен модуль или нет?
function IsActive($name){
global $sqlcn;
    $modname='modulename_'.$name;
    $result = $sqlcn->ExecuteSQL("SELECT * FROM config_common WHERE nameparam ='$modname'");        
     if ($result!=''){        
        $active=0;
        // проверяем, а может модуль уже зарегестрирован? Если нет, то только тогда его заносим в базу 
        while ($myrow = mysqli_fetch_array($result)){$active=$myrow['valueparam'];} 
        return $active;
    } else {die('Неверный запрос Tmod.IsActive: ' . mysqli_error($sqlcn->idsqlconnection));}
}



}