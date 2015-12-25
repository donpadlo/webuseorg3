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

$step=GetDef("step");
$id=GetDef("id");
$name="";
$vendorid="";
$groupid="";
//echo "!$user->mode!";
if ($user->TestRoles("1,4,5,6")==true) {
    
if ($step=="edit"){
        $SQL = "SELECT * FROM nome WHERE id='$id'";  
	$result = $sqlcn->ExecuteSQL($SQL,$cfg->base_id);
	if ($result!='') {
         while ($myrow = mysqli_fetch_array($result)){
                $groupid=$myrow['groupid'];
                $vendorid=$myrow['vendorid'];
                $name=$myrow['name'];
	 };
	} else {die('Неверный запрос : ' . mysqli_error($sqlcn->idsqlconnection));}  
  };    
?>
 
 <script>
 $(function(){
        var field = new Array("namenome");//поля обязательные                 
        $("form").submit(function() {// обрабатываем отправку формы    
            var error=0; // индекс ошибки
            $("form").find(":input").each(function() {// проверяем каждое поле в форме
                for(var i=0;i<field.length;i++){ // если поле присутствует в списке обязательных
                    if($(this).attr("name")==field[i]){ //проверяем поле формы на пустоту                        
                        if(!$(this).val()){// если в поле пустое
                            $(this).css('border', 'red 1px solid');// устанавливаем рамку красного цвета
                            error=1;// определяем индекс ошибки                                                               
                        }
                        else{
                            $(this).css('border', 'gray 1px solid');// устанавливаем рамку обычного цвета
                        }
                        
                    }               
                }
           })           
            if(error==0){ // если ошибок нет то отправляем данные
                return true;
            }
            else {
            if(error==1) var err_text = "Не все обязательные поля заполнены!<hr>";
            $("#messenger").html(err_text); 
            $("#messenger").fadeIn("slow"); 
            return false; //если в форме встретились ошибки , не  позволяем отослать данные на сервер.
            }                                       
        })
    });
$(document).ready(function() { 
            // навесим на форму 'myForm' обработчик отлавливающий сабмит формы и передадим функцию callback.
            $('#myForm').ajaxForm(function(msg) {                 
                if (msg!="ok"){
                    $('#messenger').html(msg); 
                } else {                    
                    $("#add_edit" ).html("");
                    $("#add_edit" ).dialog( "destroy" );
                    jQuery("#list2").jqGrid().trigger('reloadGrid');
                };
                
            }); 
        }); 
    
</script>
<div class="container-fluid">
<div class="row">            
    <div id="messenger"></div>    
    <form role="form" id='myForm' ENCTYPE="multipart/form-data" action="controller/server/tmc/add_edit_tmc.php?step=<?php echo "$step&id=$id"; ?>" method="post" name="form1" target="_self">
    <div class="row-fluid">    
    <div class="col-xs-6 col-md-6 col-sm-6">     
      <label for="groupid">Группа</label></br>
     <select class='chosen-select' class="form-control" name=groupid id="groupid">
    <?php
      $result = $sqlcn->ExecuteSQL("SELECT * FROM group_nome WHERE active=1 order by name;",$cfg->base_id);
        while ($myrow = mysqli_fetch_array($result))
        { $vl=$myrow['id'];
          echo "<option value=$vl";
          if ($myrow['id']==$groupid){echo " selected";};
          $nm=$myrow['name'];
          echo ">$nm</option>";
        };
    ?>   
     </select>
    </div> 
    <div class="col-xs-6 col-md-6 col-sm-6">     
     <label for="vendorid">Производитель</label></br>
     <select class='chosen-select' class="form-control" name=vendorid id="vendorid">
    <?php
      $result = $sqlcn->ExecuteSQL("SELECT * FROM vendor WHERE active=1 order by name;",$cfg->base_id);
        while ($myrow = mysqli_fetch_array($result))
        {$vl=$myrow['id'];
          echo "<option value=$vl";
          if ($myrow['id']==$vendorid){echo " selected";};
          $nm=$myrow['name'];
          echo ">$nm</option>";
        };
    ?>   
     </select>
     </div>
    </div>        
    <script>
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
    </script>        
    <div class="form-group">                 
         <label for="namenome">Наименование</label>
        <input class="form-control" placeholder="Введите наименование номенклатуры" name="namenome" id="namenome" size=100 value="<?php echo "$name";?>">    
    </div>    
     <div class="form-group">         
         <input class="form-control" type="submit"  name="Submit" value="Сохранить">
     </div> 
    </form> 
</div>    
</div>        
<?php    
 } else echo "Нужны права администратора!";
?>