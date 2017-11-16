<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("../../../../config.php"); // загружаем первоначальные настройки
                                         
// загружаем классы

include_once ("../../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../../class/config.php"); // загружаем классы настроек
include_once ("../../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../../class/employees.php"); // загружаем классы работы с профилем пользователя
                                                 
// загружаем все что нужно для работы движка

include_once ("../../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../../inc/functions.php"); // загружаем функции

if (isset($_GET["step"])) {
    $step = $_GET["step"];
}
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
;
$dtpost = "";
$title = "";
$txt = "";

if ($step == 'edit') {
    $result = $sqlcn->ExecuteSQL("SELECT * FROM news WHERE id='$id';");
    while ($myrow = mysqli_fetch_array($result)) {
        $dtpost = MySQLDateTimeToDateTimeNoTime($myrow["dt"]);
        $title = $myrow["title"];
        $txt = $myrow["body"];
    }
    ;
} else {
    $step = "add";
    $id = "";
}
;

?>
<script type="text/javascript" src="js/tinymce/jquery.tinymce.min.js"></script>
<div id="messenger"></div>
<form ENCTYPE="multipart/form-data"
	action="?content_page=news&step=<?php echo "$step&newsid=$id"; ?>"
	method="post" name="form1" target="_self">
	<input name=dtpost id=dtpost value="<?php echo "$dtpost"; ?>"><br> <input
		name=title id=title value="<?php echo "$title";?>" class="span8"
		placeholder="Заголовок"><br>
	<textarea class="span12" id="txt" name=txt rows="15"
		placeholder="Введите новость">
        <?php echo "$txt";?>
    </textarea>

</form>
<script>
$( "#pg_add_edit" ).dialog({
  close: function() {$( "#dtpost" ).datepicker( "destroy" );
   tinymce.activeEditor.destroy();
   }
});

 $(function(){
        var field = new Array("dtpost", "title", "txt");//поля обязательные
        $("form1").submit(function() {// обрабатываем отправку формы
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
    
$().ready(function() {

      $(document).on('focusin', function(e) {
    if ($(event.target).closest(".mce-window").length) {
		e.stopImmediatePropagation();
	}
});
if ($(".textarea").length) {
    $('.textarea').tinymce().hide();
};    
   $('textarea').tinymce({
      script_url : 'js/tinymce/tinymce.min.js',
      theme : "modern",      
      mode: "none",
      'theme_advanced_buttons3_add': 'code',
      plugins: "save fullscreen link emoticons code",
      toolbar: "save fullscreen link emoticons",                    
      save_enablewhendirty: true,
      save_onsavecallback: function() {document.form1.submit();}      
   });
});
 


    $("#dtpost").datepicker();
    $("#dtpost").datepicker( "option", "dateFormat", "dd.mm.yy");            
<?php if ($step!='edit'){?>    
    $("#dtpost").datepicker( "setDate" , "0");
<?php } else {?>        
 $("#dtpost").datepicker( "setDate" , "<?php  echo "$dtpost"; ?>");
<?php };?>    
    
    
</script>