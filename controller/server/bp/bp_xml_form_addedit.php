<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../class/bp.php");		// загружаем классы работы c "Бизнес процессами"
include_once("../../../class/class.phpmailer.php");	// класс управления почтой

// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции

$url=$cfg->urlsite;

function Send_mail_BP_userlist($randomid,$title,$txt){
    global $cfg;        
    $result = mysql_query("SELECT * FROM bp_userlist INNER JOIN users ON bp_userlist.userid=users.id WHERE bp_userlist.randomid='$randomid'",$cfg->base_id);
	if ($result!='') {
         while ($myrow = mysql_fetch_array($result)){
             //echo "!!!$myrow[email], $title, $txt!!!";
		smtpmail($myrow[email], $title, $txt);		
             };
        };
};


$step=GetDef("step");
$id=GetDef("id");

$title=PostDef("title");
if ($title==""){$err[]="Нет заголовка!";};  	        
$bodytxt=PostDef("bodytxt");
if ($bodytxt==""){$err[]="Нет пояснения!";};  
$status=PostDef("status");
$bpshema=PostDef("bpshema");                

// Добавляем родимую
if ($step=="add") {
     if (count($err)==0){               
        $sql="INSERT INTO bp_xml (id,userid,title,bodytxt,status,dt,node,xml) VALUES (NULL,'$user->id','$title','$bodytxt','$status',now(),1,'$bpshema')";                                      
  	$result = $sqlcn->ExecuteSQL($sql);                
  	if ($result=='') {die('Не смог добавить БП!: ' . mysqli_error($sqlcn->idsqlconnection));}
        // если стартуем процесс, то добавляем участников процесса
        if ($status==1) {
            $zxxx=new Tbp;
            $zxxx->GetLast();
            $zxxx->SetNodeToBase(1);
        }
     };
};
// ну или еслиредактируем, то обнавляем БП
if ($step=="edit"){             
     if (count($err)==0){ 
              $id=$_GET["id"];
              $sql="UPDATE bp_xml SET title='$title',bodytxt='$bodytxt',status='$status',xml='$bpshema' WHERE id='$id'";                                      
  		$result = $sqlcn->ExecuteSQL($sql,$cfg->base_id);                
  		if ($result==''){die('Не смог изменить БП!: ' . mysqli_error($sqlcn->idsqlconnection));}
                // если стартуем процесс, то добавляем участников процесса                
                if ($status==1) {
                 $zxxx=new Tbp;
                 $zxxx->GetById($id);
                 $zxxx->SetNodeToBase(1);
                };
     };
};

 


  if (count($err)==0) {echo "ok";} else {
  echo '<script>$("#messenger").addClass("alert alert-error");</script>';
        for ($i = 0; $i <= count($err); $i++) {echo "$err[$i]<br>";};  };
?>
