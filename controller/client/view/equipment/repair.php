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


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся

$eqid=$_GET["eqid"];
$step=$_GET["step"];
?>
 <script>
 $(function(){
        var field = new Array("dtpost","dt","kntid" );//поля обязательные
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
            if(error==1) var err_text = "Не все обязательные поля заполнены!";
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
                    $("#pg_add_edit" ).dialog( "destroy" );
                    $("#pg_add_edit" ).html("");                                                            
                    jQuery("#tbl_equpment").jqGrid().trigger('reloadGrid');
                    jQuery("#tbl_rep").jqGrid().trigger('reloadGrid');
                };
                
            }); 
        }); 
    
</script>
<div class="container-fluid">
<div class="row">            
<div id="messenger"></div>    
        <form id="myForm" ENCTYPE="multipart/form-data" action="controller/server/equipment/repair.php?step=add&eqid=<?php echo "$eqid" ?>" method="post" name="form1" target="_self">
            <label>Кто ремонтирует:</label>
            <div id=sorg1>
                <select class='chosen-select' name=kntid id=kntid>
                        <?php
                            $morgs=GetArrayKnt();
                            for ($i = 0; $i < count($morgs); $i++) {           
                                $nid=$morgs[$i]["id"];$nm=$morgs[$i]["name"];
                                echo "<option value=$nid>$nm</option>";
                            };
                        ?>
             </select>   
            </div>            
            <div class="row-fluid">         
            <div class="col-xs-6 col-md-6 col-sm-6">
                <label>Начало ремонта:</label>        
                <input class="form-control" name=dtpost id=dtpost size=14>
                <label>Конец ремонта:</label>
                <input class="form-control" name=dt id=dt size=14>
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6">           
                <label>Стоимость ремонта:</label>
                <input class="form-control" name=cst id=cst>        
                <label>Статус:</label>
                <select class="form-control" name=status id=status>
                    <option value='1'>В ремонте</option>
                   <option value='0'>Ремонт завершен</option>            
                </select>            
            </div>            
            <label>Комментарии:</label>
            <textarea class="form-control" name=comment></textarea>
            </div>
            <div class="form-group">
                <input class="form-control" type="submit"  name="Submit" value="Сохранить">
            </div>           
        </form>
</div>
<script>
    
    $("#dtpost").datepicker();
    $("#dtpost").datepicker( "option", "dateFormat", "dd.mm.yy");
    $("#dtpost").datepicker( "setDate" , "0");
    $("#dt").datepicker();
    $("#dt").datepicker( "option", "dateFormat", "dd.mm.yy");
    $("#dt").datepicker( "setDate" , "0");    
    
    $("#status").change(function(){       
       $("#dt").datepicker("show");
    });  
    for (var selector in config) {
      $(selector).chosen({ width: '100%' });   
      $(selector).chosen(config[selector]);
    };  
</script>    