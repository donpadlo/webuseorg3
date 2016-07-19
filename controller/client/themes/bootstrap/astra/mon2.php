<?php

include_once ("inc/lbfunc.php");                    // загружаем функции LB
if ($user->TestRoles("1,2,3,4,5,6")==1){
?>
<div class="container-fluid">
    <div class="row-fluid">
	<div class="col-xs-12 col-md-12 col-sm-12">    
	    <iframe id="demo_frame" src="https://noc.yarteleservice.ru/monastra/" width="100%" height="800px" align="left">
	       Ваш браузер не поддерживает плавающие фреймы!
	    </iframe>	    
	</div>    
    </div>    
</div>        

<?php
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}

?>