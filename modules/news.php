<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if (isset($_GET["step"])) {$step=$_GET["step"];} else {$step="";};
if (($user->mode==1) and ($step!=''))
{    
    $dtpost=DateToMySQLDateTime2(($_POST["dtpost"]));
    if ($dtpost==""){$err[]="Не введена дата!";};
    $title=($_POST["title"]);
    if ($title==""){$title="Не задан заголовок!";};
    $txt=ClearMySqlString($sqlcn->idsqlconnection,$_POST["txt"]);
    if ($txt==""){$txt="Нету тела новости!";};
    $newsid=$_GET["newsid"];
    
    if ($step=="add"){
     if (count($err)==0){
              $sql="INSERT INTO news (id,dt,title,body) VALUES (NULL,'$dtpost','$title','$txt')";                                      
  		$result = $sqlcn->ExecuteSQL($sql);
                //echo "$sql";
  		if ($result==''){die('Не смог добавить новость!: ' . mysqli_error($sqlcn->idsqlconnection));}        
     };
    };
    
    if (($step=="edit") and ($newsid!="")){
     if (count($err)==0){
              $sql="UPDATE news SET dt='$dtpost',title='$title',body='$txt' WHERE id='$newsid'";                                      
  		$result = $sqlcn->ExecuteSQL($sql,$cfg->base_id);                
  		if ($result==''){die('Не смог отредактировать новость!: ' . mysqli_error($sqlcn->idsqlconnection));}        
     };
    };

    
};



?>