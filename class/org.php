<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Torgs
{
        var $id;            // идентификатор организации
        var $name;          // наименование организации
        var $picmap;        // файл картинки
        var $active;        // 1 - активна, 0 - помечена на удаление   
        
function GetById($id){ // получить данные о пользователе по идентификатору
		global $sqlcn;
                $result = $sqlcn->ExecuteSQL("SELECT *
                                              FROM org
                                              WHERE id ='$id'");
                
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["sid"];
                             $this->name=$myrow["name"];
                             $this->picmap=$myrow["picmap"];
                             $this->active=$myrow["active"];
                             }
        } else {die('Неверный запрос GetById.GetById: ' . mysqli_error($sqlcn->idsqlconnection));}
                 }        
}

?>