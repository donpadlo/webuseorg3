<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

$sid=_GET("sid");
$sql="select * from script_run_monitoring where id=$sid";
$result = $sqlcn->ExecuteSQL($sql);
while($row = mysqli_fetch_array($result)) {
    	$script_name=$row["script_name"];
	$comment=$row["comment"];
	$group_name=$row["group_name"];
	$alert_max_count=$row["alert_max_count"];
	$alert_max_time=$row["alert_max_time"];
	$lastupdatedt=$row["lastupdatedt"];
	$current_alert_count=$row["current_alert_count"];
	$sms_txt=$row["sms_txt"];  
	$sms_group_id=$row["sms_group_id"];  	
};
?>
<script>
 $(function(){
        var field = new Array("alert_max_count", "alert_max_time", "sms_txt");//поля обязательные
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
       
// готовим объект
var options = {
    target: "#messenger",
    url: route+"controller/client/view/devicescontrol/save_scriptrun_form.php",
      success: function(msg) {
                if (msg!="ok"){
                    $('#messenger').html(msg); 
                };                
           }
  };                 
// передаем опции в  ajaxSubmit
$("#myForm").ajaxForm(options);	    
}); 
</script>    
<div class="container-fluid">
    <div class="row">            
    <div id="messenger"></div>    
	<form role="form" id="myForm" enctype="multipart/form-data" method="post" name="form1" target="_self">
	    <div class="row-fluid">
		<div class="col-xs-6 col-md-6 col-sm-6">     
		    <div class="form-group">	   
			<h1><?php echo "$script_name"; ?></h1>
			<p><?php echo "$comment"; ?></p>
			 <label for="alert_max_count">Порог превышения просрочки (раз):</label>
			<input  class="form-control"  name=alert_max_count id=alert_max_count value="<?php echo "$alert_max_count"; ?>">                 
			 <label for="alert_max_time">Период (в сек.), после которого скрипт будет считаться не отработавшим:</label>
			<input  class="form-control"  name=alert_max_time id=alert_max_time value="<?php echo "$alert_max_time"; ?>">                 			
			<input  type="hidden"  name=sid id=sid value="<?php echo "$sid"; ?>">                 			
		    </div>
		</div>	
		<div class="col-xs-6 col-md-6 col-sm-6">     
		    <div class="form-group">	
			<label for="alert_max_count">Текст СМС для отправки:</label>
			<textarea class="form-control" name=sms_txt rows="8"><?php echo "$sms_txt";?></textarea>			
			<label for="sms_group_id">Получатели:</label>
			<select class='chosen-select' name=sms_group_id id=sms_group_id>
			<?php
			 $sql="select * from sms_groups";
			 $result = $sqlcn->ExecuteSQL($sql);
			  while($row = mysqli_fetch_array($result)) {
			    echo "<option value=".$row["id"];
			    if ($row['id']==$sms_group_id){echo " selected";};
			    $nm=$row['name'];
			    echo ">$nm</option>";			      
			  };
			?>
		    </div>
		</div>			
	    </div>
            <div align=center>
                <input type="submit" class="form-control btn btn-primary" name="Submit" value="Сохранить">
	    </div>       
	</form>
    </div>
</div>