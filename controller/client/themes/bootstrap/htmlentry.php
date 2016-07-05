<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

if ($user->TestRoles("1")==true){

include_once("class/cconfig.php");                    // загружаем первоначальные настройки

$bu=new Tcconfig;
$htmlentry=$bu->GetByParam("htmlentry"); //соответствие
      
?>
<div class="container-fluid">
    <div class="row">            
	<div class="col-xs-12 col-md-12 col-sm-12">
	    <div class="panel panel-default">
	      <div class="panel-heading">Чистый HTML код</div>
	      <div class="panel-body">
		  <textarea rows="13" class="col-xs-12 col-md-12 col-sm-12" name="htmlentry" id="htmlentry" placeholder="Текст сообщения"><?php echo "$htmlentry"?></textarea>
	      </div>
	    </div>
	    <button class="form-control" type="button" onclick="SaveHtmlEntry();" class="btn btn-primary">Сохранить</button>	    
	</div>
    </div>
</div>    
<script>
function SaveHtmlEntry(){
    $.post(route+"controller/server/htmlentry/save.php", { text: $("#htmlentry").val() },
      function(data){
		  if (data=="true"){				  
		     $().toastmessage('showWarningToast', "Запись обновлена");  			    		      
		  } else {
		     $().toastmessage('showWarningToast', data);  			    
		  };			
      });
};     
</script>    
<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}

?>
