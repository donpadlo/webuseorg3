<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../../class/equipment.php");		// загружаем классы работы с ТМЦ


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся


$id=$_GET["id"];
$step=$_GET["step"];
$comment="";

echo "<script>orgid=$user->orgid</script>";
echo "<script>placesid=''</script>";
echo "<script>userid=$user->id</script>";

$tmptmc=new Tequipment;
$tmptmc->GetById($id);
$dtpost=MySQLDateTimeToDateTime($tmptmc->datepost);
$orgid=$tmptmc->orgid;
echo "<script>orgid1='".$tmptmc->orgid."'</script>";
$placesid=$tmptmc->placesid;
echo "<script>placesid1='".$tmptmc->placesid."'</script>";
$userid=$tmptmc->usersid;
echo "<script>userid1='".$tmptmc->usersid."'</script>";

?>
<script>
$(document).ready(function() { 
            // навесим на форму 'myForm' обработчик отлавливающий сабмит формы и передадим функцию callback.
            $('#myForm').ajaxForm(function(msg) {                 
                if (msg!="ok"){
                    $('#messenger').html(msg); 
                } else {
                    $("#pg_add_edit" ).html("");                                        
                    $("#pg_add_edit" ).dialog( "destroy" );
                    jQuery("#tbl_equpment").jqGrid().trigger('reloadGrid');
                };
                
            }); 
        }); 
</script>    
<div id="messenger"></div>    
<form id="myForm" ENCTYPE="multipart/form-data" action="controller/server/equipment/equipment_form.php?step=move&id=<?php echo "$id" ?>" method="post" name="form1" target="_self">
<div class="row-fluid"> 
  <div class="span12">
    <label>Организация (куда):</label>
        <div id=sorg>
         <select class="span12" name=sorgid id=sorgid >
            <?php
                $morgs=GetArrayOrgs();
                for ($i = 0; $i < count($morgs); $i++) {           
                    $nid=$morgs[$i]["id"];
                    $nm=$morgs[$i]["name"];
                    if ($nid==$user->orgid){$sl=" selected";} else {$sl="";};
                    echo "<option value=$nid $sl>$nm</option>";
                };
            ?>
         </select>
        </div>
        <label>Помещение:</label>
        <div name=splaces id=splaces>идет загрузка..</div>
        <label>Человек:</label>
        <div name=susers id=susers>идет загрузка..</div>      
        <label class="checkbox">
            <input type="checkbox" id=tmcgo name=tmcgo>ТМЦ в "пути"
        </label>
        
  </div>
</div>    
<div class="row-fluid">
  <div class="span12">
    <label>Комментарии: </label>
    <textarea class="span12" name=comment><?php echo "$comment";?></textarea>      
  </div>
</div    
<div align=center><input type="submit"  name="Submit" value="Сохранить"></div> 
</form>
<script>
    function GetListUsers(orgid,userid){
     $("#susers").load("controller/server/common/getlistusers.php?orgid="+orgid+"&userid="+userid);
    };
    function GetListPlaces(orgid,placesid){
       url="controller/server/common/getlistplaces.php?orgid="+orgid+"&placesid="+placesid;
       $("#splaces").load(url);       
    };
    $("#sorgid").click(function(){
      $("#splaces").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
      $("#susers").html="идет загрузка..";
      GetListPlaces($("#sorgid :selected").val(),''); // перегружаем список помещений организации
      GetListUsers($("#sorgid :selected").val(),'') // перегружаем пользователей организации
    });
    
 GetListUsers(orgid,userid);
 GetListPlaces(orgid,placesid);
</script>    
<?
?>