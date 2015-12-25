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
include_once("../../../../class/bp.php");		// загружаем классы работы c "Бизнес процессами"


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся

function GetListBpXml(){    
    $fl=GetArrayFilesInDir('../../../../modules/bp');  
    $cn=count($fl);
    $rs=array();
   for ($i = 0; $i < count($fl); $i++)
  {       
    $fname=$fl[$i];
    $xml = simplexml_load_file("../../../../modules/bp/$fname");    
    $name=$xml->name;
    $rs[$i]['name']=$name;
    $rs[$i]['file']=$fl[$i];
  }  
  return $rs;
};



 $step=GetDef('mode');
 $randomid=GetRandomId(60);
 $bpid=GetDef('bpid');
 if ($bpid!=""){
     $bp1=new Tbp;
     $bp1->GetById($bpid);
     $dt=MySQLDateTimeToDateTime($bp1->dt);
     $title=$bp1->title;
     $bodytxt=$bp1->bodytxt;
     $status=$bp1->status; 
     $bpshema=$bp1->xml; 
 } else {
     $dt=Date("d.m.Y H:i:s");
     $title="";
     $bodytxt="";
     $status="0"; 
     $bpshema="1";      
}
 
?> 
<script>
 $(function(){
        var field = new Array("dt", "title", "bodytxt");//поля обязательные
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
            $("#messenger").addClass("alert alert-error");
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
                    $("#bp_xml_info" ).dialog( "destroy" );
                    jQuery("#bp_xml").jqGrid().trigger('reloadGrid');
                    $("#bp_xml_info").html("");                                       

                };                
            }); 
        }); 
</script>
<div class="container-fluid">
<div class="row-fluid">       
<div id="messenger"></div>    
<form role="form" id="myForm" ENCTYPE="multipart/form-data" action="controller/server/bp/bp_xml_form_addedit.php?step=<?php echo "$step&id=$bpid"; ?>" method="post" name="form1" target="_self">
<?php
     if ($status==0) { echo "<input class=form-control name=dt id=dt readonly=true value='$dt' >";};
?>     
     <label>Заголовок:</label>
     <?php
     if ($status==0) { echo "<input placeholder='Введите задачу БП' name=title id=title value='$title' class=form-control>";} else
         {echo "<p>$title</p>";};
      ?>   
     <label>Пояснение:</label>
     <?php
     if ($status==0) {
     ?>
     <textarea placeholder='Подробное описание задачи' class=form-control name=bodytxt><?php echo "$bodytxt";?></textarea>
     <?php } else {echo "<p>$bodytxt</p>";};
      ?>   
        <label>Статус БП</label>
        <?php
         if ($status==0) {
        ?>
            <select class="form-control" name="status" id="status">
                <option value=0 <?php if ($status=="0"){echo "selected";};?>>Подготовка</option>
                <option value=1 <?php if ($status=="1"){echo "selected";};?>>В работе</option>
                <option value=3 <?php if ($status=="3"){echo "selected";};?>>Отменен</option>
            </select>  
                <label>Схема БП:</label>
         <?php
         } else {
         ?>
            <select class="form-control" name="status" id="status" disabled>
                <option value=0 <?php if ($status=="0"){echo "selected";};?>>Подготовка</option>
                <option value=1 <?php if ($status=="1"){echo "selected";};?>>В работе</option>
                <option value=2 <?php if ($status=="2"){echo "selected";};?>>Утвержден</option>
                <option value=3 <?php if ($status=="3"){echo "selected";};?>>Отменен</option>                
                <option value=4 <?php if ($status=="4"){echo "selected";};?>>В доработке</option>                
            </select>                   
            <label>Схема БП:</label>
         <?php                   
         }
         $bpl=GetListBpXml();
         // показываем какая схема БП
         if ($status==0) {
         echo " <select class=form-control name=bpshema id=bpshema>";
          for ($i = 0; $i < count($bpl); $i++){
                        $bpname=$bpl[$i]['name'];
                        $bpfile=$bpl[$i]['file'];
                        echo "<option value=$bpfile ";
                        if ($bpshema==$bpfile){echo "selected";};
                        echo ">$bpname</option>";
          };
          echo "</select>";
         } else {
            for ($i = 0; $i < count($bpl); $i++){ 
             $bpname=$bpl[$i]['name'];
             $bpfile=$bpl[$i]['file'];                
             if ($bpshema==$bpfile){ echo "$bpname<br>";};
            };
         };
         // если можно редактировать, то показываем кнопку "Сохранить"
         if ($status==0) {                 
         ?>           
            <div align=center><input type="submit" class="btn btn-primary" name="Submit" value="Сохранить"></div>      
          <?php }; ?>
</form>
</div>
</div>    
<script>status='<?php echo "$status";?>';</script>

