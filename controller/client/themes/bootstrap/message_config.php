<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if ($user->mode == 1) {
    $ip_message_port = $cfg->GetByParam("message-port");
    $ip_message_server = $cfg->GetByParam("message-server");
    $message_wss_url = $cfg->GetByParam("message-wss-url"); //        
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<h1>Настройки сервера Messages</h1>
			<label for="ip_message_server">IP сервера Messages</label> 
			<input type="text" class="form-control" name="ip_message_server" id="ip_message_server" placeholder="Какой IP прослушивает сервер Messages" value="<?php echo "$ip_message_server";?>">				

			<label for="ip_message_port">Порт сервера Messages</label> 
			<input type="text" class="form-control" name="ip_message_port" id="ip_message_port" placeholder="Какой порт прослушивает сервер Messages" value="<?php echo "$ip_message_port";?>">				

			<label for="message_wss_url">URL сервера Messages</label> 
			<input type="text" class="form-control" name="message_wss_url" id="message_wss_url" placeholder="URL для JavaScript WebSocket" value="<?php echo "$message_wss_url";?>">				
			<br/>
			<button onclick="SaveMessagesConfig();" type="submit" class="btn btn-default">Сохранить изменения</button>
		</div>
	</div>
</div>
<script>
function SaveMessagesConfig(){
    $.post(route+'controller/server/message_service/saveconfig.php',{
	    save:true,
	    ip_message_server:$("#ip_message_server").val(),
	    ip_message_port:$("#ip_message_port").val(),
	    message_wss_url:$("#message_wss_url").val()
    }, function(data){
	    $().toastmessage('showWarningToast', data);
    });    
};    
</script>
<?php
}
 else {
	?>
	<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
	<?php
};
?>