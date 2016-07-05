<?php

/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("inc/lbfunc.php");                    // загружаем функции LB
echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";
if ($user->mode==1){
?>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/FileAPI/FileAPI.exif.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/jcrop/jquery.Jcrop.min.js"></script>
<script src="js/statics/jquery.modal.js"></script>
<div class="container-fluid">
    <div class="row-fluid">
	<div class="col-xs-12 col-md-12 col-sm-12">    
	    <table id="list2"></table>
	    <div id="pager2"></div>
	    <table id="list3"></table>
	    <div id="pager3"></div>
	    <script type="text/javascript" src="controller/client/js/astra/config.js"></script>
	</div>
	<div class="row-fluid">    
	    <div class="col-xs-6 col-md-6 col-sm-6">    
		<table id="list4"></table>
		<div id="pager4"></div>		
	    </div>
	    <div class="col-xs-6 col-md-6 col-sm-6">    
		<table id="list5" style="visibility:hidden"></table>
		<div id="pager5"></div>
		<div align="center" id="simple-btn" class="btn btn-primary js-fileapi-wrapper" style="text-align: center;visibility:hidden">
		    <div class="js-browse" align="center">
			<span class="btn-txt">Загрузить документ</span>
			<input type="file" name="filedata">
		    </div>
		    <div class="js-upload" style="display: none">
		    <div class="progress progress-success"><div class="js-progress bar"></div></div>
		    <span align="center" class="btn-txt">Загружаю (<span class="js-size"></span>)</span>
		    </div>
		</div>             
		<div id="status"></div>		
	    </div>	    
	    <script type="text/javascript" src="controller/client/js/astra/config.js"></script>
	</div>	
    </div>
</div>
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