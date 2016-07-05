<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->mode==1){

    include_once ("inc/lbfunc.php");			// загружаем функции LB
    include_once ("class/cconfig.php");                     // загружаем функции работы с настройками
    $vl=new Tcconfig();	    
    $ip_chat_port=$vl->GetByParam("ip-chat-port");
    $ip_chat_server=$vl->GetByParam("ip-chat-server");
    $chat_admins=$vl->GetByParam("chat-admins");
    $ssl_pem=$vl->GetByParam("ssl-pem");
    $ssl_pass=$vl->GetByParam("ssl-pass"); //ssl-pass
    $chat_wellcome=$vl->GetByParam("chat-wellcome"); //
    $chat_wss_url_noc=$vl->GetByParam("chat-wss-url-noc"); //
    $chat_wss_url_help=$vl->GetByParam("chat-wss-url-help"); //
    
    unset($vl);
    
?>
<div class="container-fluid">
<div class="row">            
<div class="col-xs-12 col-md-12 col-sm-12">
    <form role="form" id="myForm" enctype="multipart/form-data" action="index.php?route=/controller/server/chat/chat_config_save.php" method="post" name="form1" target="_self">
      <div class="form-group">
	<label for="ip_chat_server">Настройки сервера WEB Socket</label>
	<input type="text" class="form-control" name="ip_chat_server" id="ip_chat_server" placeholder="Какой IP прослушивает сервер socket" value="<?php echo "$ip_chat_server";?>">
	<p class="help-block">Обычно используется: 0.0.0.0 - слушать все интерфейсы</p>	
	<input type="text" class="form-control" name="ip_chat_port"  id="ip_chat_port" placeholder="Какой порт прослушивает сервер socket" value="<?php echo "$ip_chat_port";?>">
	<p class="help-block">Обычно используется: >8000 что бы при запуске демона не требовался root</p>		
	<input type="text" class="form-control" name="chat_admins"  id="chat_admins" placeholder="id пользователей через ;" value="<?php echo "$chat_admins";?>">
	<p class="help-block">Перечислите id пользователей, которым будут попадать сообщения из Онлайн-Консультанта</p>		
	<input type="text" class="form-control" name="ssl_pem"  id="ssl_pem" placeholder="server.pem" value="<?php echo "$ssl_pem";?>">
	<p class="help-block">Если используете SSL соединение - укажите местонахождение server.pem</p>		
	<input type="text" class="form-control" name="ssl_pass"  id="ssl_pass" placeholder="wdwei" value="<?php echo "$ssl_pass";?>">
	<p class="help-block">Если используете SSL соединение - укажите пароль на pem</p>		
	<input type="text" class="form-control" name="chat_wellcome"  id="chat_wellcome" placeholder="Добрый день! Чем могу быть полезен?" value="<?php echo "$chat_wellcome";?>">
	<p class="help-block">Приветственная фраза для чата поддержки</p>		

	<input type="text" class="form-control" name="chat_wss_url_noc"  id="chat_wss_url_noc" placeholder="ws://nco.abirvalg.ru:8010" value="<?php echo "$chat_wss_url_noc";?>">
	<p class="help-block">Точка входа для WebSocket основного портала</p>		
	<input type="text" class="form-control" name="chat_wss_url_help"  id="chat_wss_url_help" placeholder="ws://nco.abirvalg.ru:8010" value="<?php echo "$chat_wss_url_help";?>">
	<p class="help-block">Точка входа для WebSocket мессенджера Онлайн-консультанта</p>		
	
      </div>
      <button type="submit" class="btn btn-default">Отправить</button>
    </form>
Для появления онлайн-консультанта на вашем сайте, вставьте там следующий код вида:
<pre>
   &lt;script&gt;
   (function(){	
	help_url="http://127.0.1.2/chat_client/";
	var hcc = document.createElement("script");
	hcc.type ="text/javascript";
	hcc.async =true;
	hcc.src =help_url+"chat_client.php";
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(hcc, s.nextSibling);
        var css_url=help_url+"chat_client.css";                
        var tag_css = document.createElement('link');                
	tag_css.rel = 'stylesheet';
        tag_css.href = css_url;
	tag_css.type = 'text/css';        
	var tag_head = document.getElementsByTagName('head');
	tag_head[0].appendChild(tag_css);                
   })();   
   &lt;/script&gt;
</pre>
а так-же запустите сервер чата chat.php из папки /service/
</div>
</div>
</div>    
 <script>
            $('#myForm').ajaxForm(function(msg) {                 
		    $().toastmessage('showWarningToast', msg);		
            });     
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