<?php
$viberenterhttp = $cfg->GetByParam("viber-enter-http");
$viberlogin = $cfg->GetByParam("viber-login");
$viberpass = $cfg->GetByParam("viber-password");
$vibersender = $cfg->GetByParam("viber-sender");
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="form-group">
			<label for="viber-enter-http">Точка входа</label> <input
				type="viber-enter-http" class="form-control" id="viber-enter-http"
				placeholder="Точка входа" value="<?php echo "$viberenterhttp";?>">
		</div>
		<div class="form-group">
			<label for="viber-login">Логин</label> <input type="viber-login"
				class="form-control" id="viber-login" placeholder="Логин"
				value="<?php echo "$viberlogin";?>">
		</div>
		<div class="form-group">
			<label for="viber-password">Пароль</label> <input
				type="viber-password" class="form-control" id="viber-password"
				placeholder="Пароль" value="<?php echo "$viberpass";?>">
		</div>
		<div class="form-group">
			<label for="viber-sender">Отправитель</label> <input
				type="viber-sender" class="form-control" id="viber-sender"
				placeholder="Отправитель" value="<?php echo "$vibersender";?>">
		</div>
		<button onclick="SaveViberConfig()">Сохранить настройки</button>
	</div>
</div>
<script>
function SaveViberConfig(){    
    $.post(route+'controller/server/viber/saveconfig.php',{	    
	    viberenterhttp:$("#viber-enter-http").val(),
	    viberlogin:$("#viber-login").val(),
	    viberpass:$("#viber-password").val(),
	    vibersender:$("#viber-sender").val()

    }, function(data){
	if (data==""){
		$().toastmessage('showWarningToast', 'Сохранены настройки Viber!');
	} else {
		$().toastmessage('showWarningToast', data);
	    };
    });        
};
</script>
