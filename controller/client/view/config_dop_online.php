<?php

$idkkm=  _GET("idkkm");

$sql = "SELECT * FROM online_kkm where id='$idkkm'";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список настроек!" . mysqli_error($sqlcn->idsqlconnection));
while ($row = mysqli_fetch_array($result)) {
    $ipaddress = $row['ipaddress'];
    $ipport = $row['ipport'];
    $model = $row['model'];
    $accesspass = $row['accesspass'];
    $userpass = $row['userpass'];
    $protocol = $row['protocol'];
    $logfilename = $row['logfilename'];
    $testmode = $row['testmode'];
    $libpath = $row['libpath'];
    $version = $row['version'];
    $ppath = $row['ppath'];
    $kassir = $row['kassir'];
    $innk = $row['innk'];
    $eorphone = $row['eorphone'];
};
?>
<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Дополнительные настройки онлайн-касс Атол</h3>
    </div>
    <div class="panel-body">
	<span class="help-block">IP адрес</span> 
	<input class="form-control" name="ipaddress" type="text" id="ipaddress" value="<?php echo "$ipaddress";?>">
	<span class="help-block">IP порт</span> 
	<input class="form-control" name="ipport" type="text" id="ipport" value="<?php echo "$ipport";?>">
	<span class="help-block">Модель ККМ Атол</span> 
	<input class="form-control" name="model" type="text" id="model" value="<?php echo "$model";?>">
	<span class="help-block">Пароль администратор</span> 
	<input class="form-control" name="accesspass" type="text" id="accesspass" value="<?php echo "$accesspass";?>">
	<span class="help-block">Пароль пользователя</span> 
	<input class="form-control" name="userpass" type="text" id="userpass" value="<?php echo "$userpass";?>">
	<span class="help-block">Протокол</span> 
	<input class="form-control" name="protocol" type="text" id="protocol" value="<?php echo "$protocol";?>">
	<span class="help-block">Файл логов работы</span> 
	<input class="form-control" name="logfilename" type="text" id="logfilename" value="<?php echo "$logfilename";?>">
	<span class="help-block">Путь к библиотеке</span> 
	<input class="form-control" name="libpath" type="text" id="libpath" value="<?php echo "$libpath";?>">
	<span class="help-block">Версия драйвера</span> 
	<input class="form-control" name="version" type="text" id="version" value="<?php echo "$version";?>">
	<span class="help-block">Путь к скрипту взаимодействия с кассой</span> 
	<input class="form-control" name="ppath" type="text" id="ppath" value="<?php echo "$ppath";?>">

	<span class="help-block">Кассир</span> 
	<input class="form-control" name="kassir" type="text" id="kassir" value="<?php echo "$kassir";?>">
	<span class="help-block">ИНН кассира</span> 
	<input class="form-control" name="innk" type="text" id="innk" value="<?php echo "$innk";?>">
	<span class="help-block">Куда отправлять чек по умолчанию</span> 
	<input class="form-control" name="eorphone" type="text" id="eorphone" value="<?php echo "$eorphone";?>">
	
	<input name=testmode id=testmode type="checkbox" value="Yes" <?php if ($testmode=="1"){echo "checked";};  ?>> Режим тестирования 	
    </div>
</div>
<a  class="btn btn-default" href="#" onclick="SaveConfigKKM();">Сохранить изменения</a>
<a  class="btn btn-default" href="#" onclick="GetInfoKKM();">Получить информацию о кассе с кассы</a>
<script>
function SaveConfigKKM(){    
    $.post(route+'controller/server/online_kkm_save_config.php',{
	    idkkm:<?php echo "$idkkm";?>,
	    ipaddress:$("#ipaddress").val(),
	    ipport:$("#ipport").val(),
	    model:$("#model").val(),
	    accesspass:$("#accesspass").val(),
	    userpass:$("#userpass").val(),
	    protocol:$("#protocol").val(),
	    logfilename:$("#logfilename").val(),
	    libpath:$("#libpath").val(),
	    version:$("#version").val(),	
	    ppath:$("#ppath").val(),		    
	    kassir:$("#kassir").val(),	
	    innk:$("#innk").val(),	
	    eorphone:$("#eorphone").val(),	
	    testmode:testmode.checked

    }, function(data){
	    $().toastmessage('showWarningToast', data);
    });      
};
function GetInfoKKM(){   
 $.post(route+'controller/server/getinfokkm.php',{
	    idkkm:<?php echo "$idkkm";?>,
    }, function(data){
	       $().toastmessage('showToast', {
			text     : data,
			sticky   : true,
			position : 'top-right',
			type     : "notice"
		    });	
    });      
};
</script>    