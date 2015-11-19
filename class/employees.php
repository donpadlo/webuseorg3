<?php
class Temployees
{
    var $id;            // идентификатор 
    var $usersid;       // связь с пользователем
    var $faza;          // в какой фазе пользователь (например в отпуске)
    var $code;          // связь с ERP
    var $enddate;       // дата когда фаза кончится
    var $post;          // Должность

function Add(){ // добавляем профиль работника с текущими данными (все что заполнено)
		global $sqlcn;
                $result = $sqlcn->ExecuteSQL("INSERT INTO users_profile (id,usersid,faza,code,enddate,post) VALUES (NULL,'$this->usersid','$this->faza','$this->code','$this->enddate','$this->post')");                
  		if ($result==''){die('Неверный запрос Temployees.Add: ' . mysqli_error($sqlcn->idsqlconnection));}
    
}
function Update(){ // обновляем профиль работника с текущими данными (все что заполнено)
		global $sqlcn;
                $result = $sqlcn->ExecuteSQL("UPDATE users_profile SET fio='$this->fio',faza='$this->faza',code='$this->code',enddate='$this->enddate',post='$this->post' WHERE code='$this->code'");                
  		if ($result==''){die('Неверный запрос Temployees.Update: ' . mysqli_error($sqlcn->idsqlconnection));}
    
}
function GetByERPCode(){ // обновляем профиль работника с текущими данными (все что заполнено)
		global $sqlcn;
                $result = $sqlcn->ExecuteSQL("SELECT * FROM users_profile WHERE code='$this->code'");                
  		if ($result!=''){                    
                    while ($myrow = mysqli_fetch_array($result)){
                    $this->id=$myrow["id"];
                    $this->usersid=$myrow["usersid"];                    
                    $this->fio=$myrow["fio"];
                    $this->faza=$myrow["faza"];
                    $this->enddate=$myrow["enddate"];
                    $this->post=$myrow["post"];
                };};
  		if ($result==''){die('Неверный запрос Temployees.GetByERPCode: ' . mysqli_error($sqlcn->idsqlconnection));}
    
}

function EmployeesYetByERPCode($TERPCode){ // а есть ли такой в базе (проверка по ERPCode. Если есть - возврат 1, иначе 0
    global $sqlcn;
    //echo "$TERPCode";
    $res=false;
    $result = $sqlcn->ExecuteSQL("SELECT * FROM users_profile WHERE code='$TERPCode'");
    if ($result!=''){
      while ($myrow = mysqli_fetch_array($result)){
        $res=true;   
      };
    } else {die('Ошибка (EmployeesYetByERPCode): ' . mysqli_error($sqlcn->idsqlconnection));}            
    return $res;
}

};

?>
