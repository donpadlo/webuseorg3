<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../class/equipment.php");		// загружаем классы работы с ТМЦ
include_once("../../../class/class.phpmailer.php");	// класс управления почтой

// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// соеденяемся с БД, получаем $mysql_base_id

function SendEmailByPlaces($plid,$title,$txt){
    global $sqlcn;
    $sql="SELECT userid AS uid, users.email AS email FROM places_users
          INNER JOIN users ON users.id = places_users.userid WHERE places_users.placesid =$plid AND users.email <>  ''";
    $result = $sqlcn->ExecuteSQL($sql);  
    while($row = mysqli_fetch_array($result)) {
     smtpmail($row['email'],$title,$txt);   
    };
};

if (isset($_GET["step"])) {$step=$_GET["step"];} else {$step="";};
// Выполняем токма если юзер зашел!
if (($user->TestRoles("1,4,5,6")==true) and ($step!='')) {
    if ($step!='move'){
	$dtpost=DateToMySQLDateTime2($_POST["dtpost"]." 00:00:00");
        $dtendgar=DateToMySQLDateTime2($_POST["dtendgar"]." 00:00:00");
	if ($dtpost==""){$err[]="Не выбрана дата!";};   	
        if (isset($_POST["sorgid"]))    {$sorgid=$_POST["sorgid"];} else if ($sorgid==""){$err[]="Не выбрана организация!";}; 
	if (isset($_POST["splaces"]))   {$splaces=$_POST["splaces"];} else if ($splaces==""){$err[]="Не выбрано помещение!";};    	
	if (isset($_POST["suserid"]))   {$suserid=$_POST["suserid"];} else if ($suserid==""){$err[]="Не выбран пользователь!";};    	
	if (isset($_POST["sgroupname"]))   {$sgroupname=$_POST["sgroupname"];} else if ($sgroupname==""){$err[]="Не выбрана группа номенклатуры!";};    
	if (isset($_POST["svendid"]))   {$svendid=$_POST["svendid"];} else if ($svendid==""){$err[]="Не выбран производитель!";};    	
	if (isset($_POST["snomeid"]))   {$snomeid=$_POST["snomeid"];} else if ($snomeid==""){$err[]="Не выбрана номенклатура!";};    
	if (isset($_POST["kntid"]))   {$kntid=$_POST["kntid"];} else if ($kntid==""){$err[]="Не выбран поставщик!";};            
        if (isset($_POST["os"]))        {$os=$_POST["os"];} else {$os="0";};
	if (isset($_POST["mode"]))      {$mode=$_POST["mode"];} else {$mode="0";};
        if (isset($_POST["mapyet"]))    {$mapyet=$_POST["mapyet"];} else {$mapyet="0";};                
	$buhname=$_POST["buhname"];$sernum=$_POST["sernum"];
	$invnum=$_POST["invnum"];  $shtrihkod=$_POST["shtrihkod"];    
	$cost=$_POST["cost"];$picphoto=$_POST["picname"];
	$currentcost=$_POST["currentcost"];$comment=$_POST["comment"];
        $ip=$_POST["ip"];
    } else
    {        
	if (isset($_POST["sorgid"]))    {$sorgid=$_POST["sorgid"];} else if ($sorgid==""){$err[]="Не выбрана организация!";};          
	if (isset($_POST["splaces"]))   {$splaces=$_POST["splaces"];} else if ($splaces==""){$err[]="Не выбрано помещение!";};                    
	if (isset($_POST["suserid"]))   {$suserid=$_POST["suserid"];} else if ($suserid==""){$err[]="Не выбран пользователь!";};	
        if (isset($_POST["tmcgo"]))    {
            if ($_POST["tmcgo"]=='on'){$tmcgo=1;} else {$tmcgo=0;};            
        } else {$tmcgo="0";};                
	$comment=$_POST["comment"];
    };
       // Добавляем родимую
    
    if ($step=="add")
    {
     if (count($err)==0)
     {               
        $sql="INSERT INTO equipment (id,orgid,placesid,usersid,nomeid,buhname,datepost,cost,currentcost,sernum,invnum,shtrihkod,os,mode,comment,active,ip,mapyet,photo,kntid,dtendgar)
        VALUES (NULL,'$sorgid','$splaces','$suserid','$snomeid','$buhname','$dtpost','$cost','$currentcost','$sernum','$invnum','$shtrihkod','$os','$mode','$comment','1','$ip','$mapyet','$picphoto','$kntid','$dtendgar')";                                      
  	$result = $sqlcn->ExecuteSQL($sql);                
  	if ($result==''){die('Не смог добавить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));}
	if ($cfg->sendemail==1){
		  //   $txt="Внимание! На Вашу ответственность переведена новая единица ТМЦ. <a href=$url?content_page=eq_list&usid=$suserid>Подробности здесь.</a>";
		    //  smtpmail("$touser->email","Уведомление о перемещении ТМЦ",$txt);
                     // SendEmailByPlaces($splaces,"Изменился состав ТМЦ в помещении","Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url?content_page=eq_list>Подробнее здесь.</a>");
		    };		      
     };
    };
    if ($step=="edit")
    {
     if (count($err)==0)
     {
              $id=$_GET["id"];              
	      //echo "!$id!$sorgid!$picphoto!";
              $buhname = mysqli_real_escape_string($sqlcn->idsqlconnection, $buhname);
              $sql="UPDATE equipment SET
	      usersid='$suserid',nomeid='$snomeid',buhname='$buhname',
	      datepost='$dtpost',cost='$cost',currentcost='$currentcost',sernum='$sernum',invnum='$invnum',
	      shtrihkod='$shtrihkod',os='$os',mode='$mode',comment='$comment',photo='$picphoto',ip='$ip',mapyet='$mapyet',kntid='$kntid',dtendgar='$dtendgar' WHERE id='$id'";                                      
  		$result = $sqlcn->ExecuteSQL( $sql);                
  		if ($result==''){die('Не смог изменить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));}

     };
    };
    if ($step=="move")
    {
     if (count($err)==0)
     {
              $id=$_GET["id"];
                $etmc=new Tequipment;
                $etmc->GetById($id);
                $sql="UPDATE equipment SET tmcgo='$tmcgo',mapmoved=1,orgid='$sorgid',placesid='$splaces',usersid='$suserid' WHERE id='$id'";  
                $result = $sqlcn->ExecuteSQL($sql);                
  		if ($result==''){$err[]='Не смог изменить регистр номенклатуры - перемещение!: ' . mysqli_error($sqlcn->idsqlconnection);}        
                    $sql="INSERT INTO move (id,eqid,dt,orgidfrom,orgidto,placesidfrom,placesidto,useridfrom,useridto,comment) VALUES (NULL,'$id',NOW(),'$etmc->orgid','$sorgid','$etmc->placesid','$splaces','$etmc->usersid','$suserid','$comment')";                                      
                    $result = $sqlcn->ExecuteSQL($sql);
                    if ($result==''){$err[]='Не смог добавить перемещение!: ' . mysqli_error($sqlcn->idsqlconnection);}
                        if ($cfg->sendemail==1){
                                $touser= new Tusers;
                                $touser->GetById($suserid);
                                $url=$cfg->urlsite;
                                $tmcname=$etmc->tmcname;	     
                                    $txt="Внимание! На Вашу ответственность переведена новая единица ТМЦ ($tmcname). <a href=$url/index.php?content_page=eq_list&usid=$suserid>Подробности здесь.</a>";
                                    smtpmail("$touser->email","Уведомление о перемещении ТМЦ",$txt);   // отсылаем уведомление кому пришло
                                    SendEmailByPlaces($etmc->placesid,"Изменился состав ТМЦ в помещении","Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url/index.php?content_page=eq_list>Подробнее здесь.</a>");
                                    SendEmailByPlaces($splaces,"Изменился состав ТМЦ в помещении","Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url/index.php?content_page=eq_list>Подробнее здесь.</a>");                     
                                $touser= new Tusers;
                                $touser->GetById($etmc->usersid);
                                $txt="Внимание! С вашей отвественности снята единица ТМЦ ($tmcname). <a href=$url/index.php?content_page=eq_list&usid=$etmc->usersid>Подробности здесь.</a>";
                                smtpmail("$touser->email","Уведомление о перемещении ТМЦ",$txt);		      
		    };

     };
    };
 
};
  if (count($err)==0) {echo "ok";} else {
  echo '<script>$("#messenger").addClass("alert alert-error");</script>';
        for ($i = 0; $i <= count($err); $i++) {echo "$err[$i]<br>";};  };
?>
