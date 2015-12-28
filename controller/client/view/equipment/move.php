<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
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
<div class="container-fluid">
<div class="row">            
<div id="messenger"></div>    
    <form id="myForm" enctype="multipart/form-data" action="index.php?route=/controller/server/equipment/equipment_form.php?step=move&id=<?php echo "$id" ?>" method="post" name="form1" target="_self">
        <div class="row-fluid"> 
          <div class="col-xs-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label>Организация (куда):</label>
                <div id=sorg>
                    <select class='chosen-select' name=sorgid id=sorgid >
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
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id=tmcgo name=tmcgo>ТМЦ в "пути"
                    </label>
                </div>    
          </div>
          </div>    
        </div>    
        <div class="row-fluid">
          <div class="col-xs-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label>Комментарии: </label>
                <textarea class="form-control" name=comment><?php echo "$comment";?></textarea>                
                <input class="form-control" type="submit"  name="Submit" value="Сохранить">
              </div>
          </div>  
        </div> 
    </form>
</div>
</div>    
<script>
    function UpdateChosen(){
        for (var selector in config) {
            $(selector).chosen({ width: '100%' });
            $(selector).chosen(config[selector]);
        };        
    };    
    function GetListUsers(orgid,userid){     
        $.get("controller/server/common/getlistusers.php?orgid="+orgid+"&userid="+userid, function(data){
           $("#susers").html(data);
           UpdateChosen()
       });
    };
    function GetListPlaces(orgid,placesid){
        $.get(route + "controller/server/common/getlistplaces.php?orgid="+orgid+"&placesid="+placesid, function(data){
           $("#splaces").html(data);
           UpdateChosen()
       });

    };
    $("#sorgid").click(function(){
      $("#splaces").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
      $("#susers").html="идет загрузка..";
      GetListPlaces($("#sorgid :selected").val(),''); // перегружаем список помещений организации
      GetListUsers($("#sorgid :selected").val(),'') // перегружаем пользователей организации
      UpdateChosen();
    });
    
 GetListUsers(orgid,userid);
 GetListPlaces(orgid,placesid);
 UpdateChosen();
</script>    
