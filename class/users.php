<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tusers
{
        var $id;        // идентификатор пользователя
        var $randomid;  // случайны идентификатор (время от времени может менятся)
        var $orgid;     // принадлежность к организации
        var $login;     // логин
        var $pass;      // пароль
        var $email;     // электронная почта
        var $mode;      // 0 - пользователь 1- админ
	var $lastdt;    // дата и время последнего посещения
	var $active;    // 1-не помечен на удаление
        // далее выдергивается из профиля если оный есть по GetById
        var $fio;               // фамилия имя отчество
        var $telephonenumber;	// телефонный номер (сотовый)
        var $homephone;          // телефонный номер (альтернатива)
        var $jpegphoto;          // фотография из папки photos
        var $tab_num;            // табельный номер
        var $post;            // должность

/**Проверяем соответствие роли
 * 
 * Роли:  
 *            1="Полный доступ"
 *            2="Просмотр финансовых отчетов"
 *            3="Просмотр количественных отчетов"
 *            4="Добавление"
 *            5="Редактирование"
 *            6="Удаление"
 *            7="Отправка СМС"
 *            8="Манипуляции с деньгами"    
 *            9="Редактирование карт"    
 * 
 * @global type $sqlcn
 * @param type $roles
 * @return boolean
 */        
function TestRoles($roles){ // проверяем соответствие роли 1,2,3,4
    
		global $sqlcn;
                $rol=  explode(',',$roles);
                $rz=false;
                //echo "!rz=$rz!";    
                foreach ($rol as $key) {
                    //echo "-$key-";
                    $sql="select * from  usersroles WHERE userid='$this->id' and role='$key'";
                    //echo "$sql!";
                    $result = $sqlcn->ExecuteSQL($sql);
                    if ($result==''){die('Неверный запрос Tusers.TestRoles: ' . mysqli_error($sqlcn->idsqlconnection));}
                    while ($myrow = mysqli_fetch_array($result)){
                        //echo "-ok;";
                        $rz=true;     
                    };
                };
                //echo "!rz=$rz!";    
                return $rz;
    }
        
function UpdateLastdt($id){ // обновляем данные о последнем посещении
		global $sqlcn;
		$lastdt=date( 'Y-m-d H:i:s');
  		$result = $sqlcn->ExecuteSQL("UPDATE users SET lastdt='$lastdt' WHERE id='$id'");
  		if ($result==''){die('Неверный запрос Tusers.UpdateLastdt: ' . mysqli_error($sqlcn->idsqlconnection));}
    }
function Update(){ // обновляем данные о текущем пользователе в базу
		global $sqlcn;		
  		$result = $sqlcn->ExecuteSQL("UPDATE users SET orgid='$this->orgid',login='$this->login',pass='$this->pass',email='$this->email',mode='$this->mode',active='$this->active' WHERE id='$this->id'");                  		
  		if ($result==''){die('Неверный запрос Tusers.Update (1): ' . mysqli_error($sqlcn->idsqlconnection));}
  		$result = $sqlcn->ExecuteSQL("UPDATE users_profile SET fio='$this->fio',telephonenumber='$this->telephonenumber',homephone='$this->homephone',jpegphoto='$this->jpegphoto',code='$this->tab_num',post='$this->post' WHERE usersid='$this->id'");                  		
  		if ($result==''){die('Неверный запрос Tusers.Update (2): ' . mysqli_error($sqlcn->idsqlconnection));}
                
    }    
function GetByLogin($login){ // получить данные о пользователе по логину
		global $sqlcn;
  		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE login='$login'");
                $result = $sqlcn->ExecuteSQL("SELECT users_profile.* , users.* ,users.id as sid
                                              FROM users
                                              INNER JOIN users_profile ON users_profile.usersid = users.id
                                              WHERE users.login ='$login'");                
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["sid"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
                             $this->mode=$myrow["mode"];                             
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];    
                             $this->telephonenumber=$myrow["telephonenumber"];  
                             $this->jpegphoto=$myrow["jpegphoto"];  
                             $this->homephone=$myrow["homephone"];                                
                             $this->fio=$myrow["fio"];                                
                             $this->tab_num=$myrow["code"];                                
                             $this->post=$myrow["post"];                                
                             
                             }
        } else {die('Неверный запрос Tusers.GetByLogin: ' . mysqli_error($sqlcn->idsqlconnection));}
                 }
        
function GetByLoginPass($login,$pass){ // получить данные о пользователе по логину/паролю
		global $sqlcn;
  		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE (login='$login') and (pass='$pass')");
                $result = $sqlcn->ExecuteSQL("SELECT users_profile.* , users.* ,users.id as sid
                                              FROM users
                                              INNER JOIN users_profile ON users_profile.usersid = users.id
                                              WHERE users.login ='$login' and users.pass='$pass'");                
                
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["sid"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
                             $this->mode=$myrow["mode"];                             
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];   
                             $this->telephonenumber=$myrow["telephonenumber"];  
                             $this->jpegphoto=$myrow["jpegphoto"];  
                             $this->homephone=$myrow["homephone"];                                
                             $this->fio=$myrow["fio"];                                
                             $this->tab_num=$myrow["code"];   
                             $this->post=$myrow["post"];                                
                             
                             }
        } else {die('Неверный запрос Tusers.GetByLoginPass: ' . mysqli_error($sqlcn->idsqlconnection));}
                 }
function GetById($idz){ // получить данные о пользователе по идентификатору
		global $sqlcn;
  		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE id='$id'");
                $result = $sqlcn->ExecuteSQL("SELECT users_profile.* , users.* ,users.id as sid
                                              FROM users
                                              INNER JOIN users_profile ON users_profile.usersid = users.id
                                              WHERE users.id ='$idz'");
                
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["sid"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
			     //echo "---$this->email---";
                             $this->mode=$myrow["mode"];                             
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];  
                             $this->telephonenumber=$myrow["telephonenumber"];  
                             $this->jpegphoto=$myrow["jpegphoto"];  
                             $this->homephone=$myrow["homephone"];                                
                             $this->fio=$myrow["fio"];                                
                             $this->tab_num=$myrow["code"];      
                             $this->post=$myrow["post"];                                
                             }
        } else {die('Неверный запрос Tusers.GetById: ' . mysqli_error($sqlcn->idsqlconnection));}
                 }
function GetByRandomId($id){ // получить данные о пользователе по идентификатору TRUE - нашли, FALSe - не нашли
		global $sqlcn;
  		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE randomid='$id'");
                $result = $sqlcn->ExecuteSQL("SELECT users_profile.* , users.* ,users.id as sid
                                              FROM users
                                              INNER JOIN users_profile ON users_profile.usersid = users.id
                                              WHERE users.randomid ='$id'");
                $res=false;
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["sid"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
                             $this->mode=$myrow["mode"];			     
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];   
                             $this->telephonenumber=$myrow["telephonenumber"];  
                             $this->jpegphoto=$myrow["jpegphoto"];  
                             $this->homephone=$myrow["homephone"];                               
                             $this->fio=$myrow["fio"];                                                             
                             $this->tab_num=$myrow["code"];     
                             $this->post=$myrow["post"];                                
                             $res=true;
                             }
                         } else {die('Неверный запрос Tusers.GetByRandomId: ' . mysqli_error($sqlcn->idsqlconnection));}                         
                         return $res;
                 }
function GetByRandomIdNoProfile($id){ // получить данные о пользователе по идентификатору TRUE - нашли, FALSe - не нашли. БЕЗ ПРОФИЛЯ
		global $sqlcn;
  		//$result = $sqlcn->ExecuteSQL("SELECT * FROM users WHERE randomid='$id'");
                $result = $sqlcn->ExecuteSQL("SELECT * 
                                              FROM users                                              
                                              WHERE randomid ='$id'");
                $res=false;
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["id"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
                             $this->mode=$myrow["mode"];			     
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];   
                             $res=true;
                             }
                         } else {die('Неверный запрос Tusers.GetByRandomId: ' . mysqli_error($sqlcn->idsqlconnection));}                         
                         return $res;
                 }

function Add(){ // добавляем пользователя с текущими данными
		global $sqlcn;
                $sql="INSERT INTO users (id,randomid,orgid,login,pass,email,mode,lastdt,active) VALUES
                                      (NULL,'$this->randomid','$this->orgid','$this->login','$this->pass',
                                      '$this->email','$this->mode',now(),1)";                
  		$result = $sqlcn->ExecuteSQL($sql);
                
  		if ($result==''){die('Неверный запрос Tusers.Add (1): ' . mysqli_error($sqlcn->idsqlconnection));}
                $fio=$this->fio;
                $code=$this->tab_num;
                $telephonenumber=$this->telephonenumber;
                $homephone=$this->homephone;
                $jpegphoto=$this->jpegphoto;
                $rid=$this->randomid;
                $post=$this->post;
               
                $zx=new Tusers;
                
                if ($zx->GetByRandomIdNoProfile($rid)==true){
                // добавляю профиль
                $sql="INSERT INTO users_profile (id,usersid,fio,code,telephonenumber,homephone,jpegphoto,post,faza,enddate,res1) VALUES
                                      (NULL,'$zx->id','$fio','$code','$telephonenumber',
                                      '$homephone','$jpegphoto','$post','',now(),'')";                
                $result = $sqlcn->ExecuteSQL($sql);} 
                    else {die('Не найден пользователь по randomid Tusers.Add');};                
  		if ($result==''){die('Неверный запрос Tusers.Add(2): ' . mysqli_error($sqlcn->idsqlconnection));}
                
    
}
function GetByCode($code){ // получить данные о пользователе по  коду из профиля FALSE - если ничего не нашли
		global $sqlcn;
                $res=FALSE;
  		$result = $sqlcn->ExecuteSQL("SELECT users_profile . * , users . * 
                                              FROM users_profile
                                              INNER JOIN users ON users_profile.usersid = users.id
                                              WHERE users_profile.code ='$code'");
  		if ($result!=''){
                            while ($myrow = mysqli_fetch_array($result)){
                             $this->id=$myrow["usersid"];
                             $this->randomid=$myrow["randomid"];
                             $this->orgid=$myrow["orgid"];
                             $this->login=$myrow["login"];
                             $this->pass=$myrow["pass"];
                             $this->email=$myrow["email"];
                             $this->mode=$myrow["mode"];                             
                             $this->lastdt=$myrow["lastdt"];
			     $this->active=$myrow["active"];   
                             $this->telephonenumber=$myrow["telephonenumber"];  
                             $this->jpegphoto=$myrow["jpegphoto"];  
                             $this->homephone=$myrow["homephone"];  
                             $this->fio=$myrow["fio"];                                                             
                             $this->post=$myrow["post"];                                                             
                             $res=TRUE;
                             };
                         } else {die('Неверный запрос Tusers.GetByCode: ' . mysqli_error($sqlcn->idsqlconnection));}
                         return $res;
                 }

}

