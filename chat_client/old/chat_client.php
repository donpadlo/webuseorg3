<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// данный код - это подгружаемая часть javascript кода,размещаемого на сайте-клиенте, отвечает за отрисовку диалогового окна
// беседы вопрошаеющего с оператором.

define('WUO_ROOT', dirname(__FILE__));

include_once(WUO_ROOT.'/../config.php');
include_once(WUO_ROOT.'/../class/sql.php'); // Класс работы с БД
include_once(WUO_ROOT.'/../class/config.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/cconfig.php'); // Класс настроек
include_once(WUO_ROOT.'/../class/users.php'); // Класс работы с пользователями

// Загружаем все что нужно для работы движка
include_once(WUO_ROOT.'/../inc/connect.php'); // Соединяемся с БД, получаем $mysql_base_id
include_once(WUO_ROOT.'/../inc/config.php'); // Подгружаем настройки из БД, получаем заполненый класс $cfg
include_once(WUO_ROOT.'/../inc/functions.php'); // Загружаем функции
include_once(WUO_ROOT.'/../inc/login.php'); // Создаём пользователя $user

include_once(WUO_ROOT.'/../inc/func_chat.php'); // Загружаем рутинные функции для чата

//читаю настройки чата
$vl=new Tcconfig();
$ip_chat_server=$vl->GetByParam("ip-chat-server");
$ip_chat_port=$vl->GetByParam("ip-chat-port");
$ssl_pem=$vl->GetByParam("ssl-pem");
$ssl_pass=$vl->GetByParam("ssl-pass"); //ssl-pass
$chat_wellcome=$vl->GetByParam("chat-wellcome"); //
$chat_wss_url_noc=$vl->GetByParam("chat-wss-url-noc"); //
$chat_wss_url_help=$vl->GetByParam("chat-wss-url-help"); //


if ($ssl_pem!=""){$ssl_pem="wss";} else {$ssl_pem="ws";};


if ($ip_chat_server=="" or $ip_chat_port==""){ die("--укажите настройки IP сервера и порта в настройках веб интерфейса чата!\n");};

?>
//<script>    
pingpong=false; // если true - будет раз в секунду "пинпонг" между клиентолм и сервером
cntwrite=0;	//счетчик нажатий на клавиши. Каждое 5 нажатие - сообщаем что что-то пишем..
window.onload=function(){
    if (typeof jQuery == 'undefined') {    
	dhtmlLoadScript("https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js");
    };
	//проверяем, а подгрузился ли juery?
	var timerId = setInterval(function() {
	    if (typeof jQuery == 'undefined'){
		console.log("--еще не догрузился jquery..ждем..");
	    } else {
		dhtmlLoadScript("http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js");		
		console.log("--вроде jquery загрузился, гружу jquery-ui..");		
		clearInterval(timerId);
		var timerId2 = setInterval(function() {		
		    if (typeof $.ui == 'undefined'){
			console.log("--еще не догрузился jquery-ui..ждем..");
		    } else {
			clearInterval(timerId2);
			console.log("--вроде jquery-ui загрузился, инициализирую чат..");					
			mainchat();
		    };		    
		},1000);		
	    };	    
	}, 1000);	
	
};
function dhtmlLoadScript(url){
   var e = document.createElement("script");
   e.src = url;
   e.type="text/javascript";
   document.getElementsByTagName("head")[0].appendChild(e); 
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie(name, value, options) {
  options = options || {};
  var expires = options.expires;
  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {options.expires = expires.toUTCString();}
  value = encodeURIComponent(value);
  var updatedCookie = name + "=" + value;
  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {updatedCookie += "=" + propValue;}
  }
  document.cookie = updatedCookie;
}
function ChatSendText(){
    msg=[];
    msg={to_user_id:to_user_id,client:"client",command:"SendMessage",from_user_id:from_user_id,sendtext:$("#chat_enter_text").val()};
    console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
    chatsocket.send(JSON.stringify(msg));
    $("#chat_enter_text").val("");
    msg=[];	      		  
  
};
function CreateChatBox(){
    $("#chat_message_tab").remove();
    $("body" ).append("<div id='chat_message_box' class='chat_message_box'>"+
"<div id='chat_title' class='chat_title'>Онлайн-консультант</div>"+
    "<div id='chat_scroll_box' class='chat_scroll_box'>Загружаю историю переписки..</div>"+
    "<div id='chat_send_box' class='chat_send_box'>"+
    "<textarea oninput='textinput()' id='chat_enter_text' class='chat_enter_text' placeholder='Введите текст сообщения'></textarea><input class='chat_button_send' type='submit' onclick='ChatSendText()' value='Отправить'>"+
    "</div>"+
    "<div class='chat_button_close' onclick='CloseChatBox()' id='chat_button_close'>[x]</div>"+
"</div>" );    
    $('#chat_message_box').draggable();
    msg=[];
    msg={client:"client",command:"GetHistory",from_user_id:from_user_id,to_user_id:to_user_id};
    console.log("--спрашиваем, историю сообщений:",JSON.stringify(msg));
    chatsocket.send(JSON.stringify(msg));
    msg=[];	      		  
}
function CloseChatBox(){
    $("#chat_message_box").remove();
    CreateOnlineTab();
}    
function CreateOnlineTab(){
    $("body" ).append( "<div id='chat_message_tab' onclick='CreateChatBox()' class='message_online'>Онлайн-консультант</div>" );
    $('#chat_message_tab').draggable();
    zhtml = document.documentElement;
    $('#chat_message_tab').css("top",zhtml.clientHeight/2);
    $('#chat_message_tab').fadeIn({duration: 2400});
};

function AddMessageToChat(txt){
 $("#chat_scroll_box").append(txt);
};
function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
};
function textinput(){
    if (cntwrite==0){
	msg=[];
	msg={client:"client",command:"textwrite",from_user_id:from_user_id,to_user_id:to_user_id};
	console.log("--что-то пишем..:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));
	msg=[];	    
    };
    cntwrite++;
    if (cntwrite>5){cntwrite=0;};
};    
chat_step=1;  // выясняем кто мы
online="no";  // по умолчанию онлайн никого нет
from_user_id="no";
function mainchat(){
 $( document ).ready(function() {    
    if (typeof chatsocket == 'undefined') {    
	//соединяемся с сервером
	chatsocket = new WebSocket("<?php echo "$chat_wss_url_help";?>");
	chatsocket.onopen = function() {
	    console.log("Соединение с сервером установлено...");
	};

	chatsocket.onclose = function(event) {
	  if (event.wasClean) {
	    console.log('Соединение закрыто чисто');
	  } else {
	    console.log('Обрыв соединения'); // например, "убит" процесс сервера
	  }
	  console.log('Код: ' + event.code + ' причина: ' + event.reason);
	};
	
	chatsocket.onmessage = function(event) {
	  console.log("Получены данные " + event.data);
	  msg=JSON.parse(event.data);
	  //если только установили соединение,и нет ID в кукисах, то спрашиваем новый ID
	  if ((chat_step==1)&(msg["command"]=="Hello")){
	      from_user_id=getCookie("from_user_id");
	      chat_username=getCookie("chat_username");
	      if (from_user_id==undefined){
		msg=[];
		msg={client:"client",command:"Get_new_id_client"};
		console.log("--отвечаем на Hello, запрашивая ID:",JSON.stringify(msg));
		chatsocket.send(JSON.stringify(msg));
	      } else {
		console.log("--получили из куков from_user_id=",from_user_id,",chat_username=",chat_username)	      		  
		msg=[];
		msg={client:"client",command:"Online",from_user_id:from_user_id};
		console.log("--спрашиваем, есть кто онлайн?:",JSON.stringify(msg));
		chatsocket.send(JSON.stringify(msg));
		msg=[];	      		  
	      };
	  };
	  if (msg["command"]=="Put_new_id_client"){	
	      from_user_id=msg["result"];
	      chat_username=msg["name"];
	      console.log("--получили from_user_id=",from_user_id,",chat_username=",chat_username)	      
	      //устанавливаем куки
		setCookie("from_user_id", from_user_id, {expires:1000000})	      		
		setCookie("chat_username", chat_username, {expires:1000000})	      		
	      msg=[];
	      msg={client:"client",command:"Online",from_user_id:from_user_id};
	      console.log("--спрашиваем, есть кто онлайн?:",JSON.stringify(msg));
	      chatsocket.send(JSON.stringify(msg));
	      msg=[];	      
	  };
	  if (msg["command"]=="Online"){
	      online=msg["result"];
	      to_user_id=msg["to_user_id"]; //кто будет отвечать на НОС
	      console.log("--получили online=",online)	      	      
	      msg=[];
	      if (online=="yes"){
		  console.log("--рисуем язычек онлайн");
		  CreateOnlineTab();
	      } else {
		  console.log("--рисуем язычек оффлайн");
	      };
	  };    
	  if (msg["command"]=="GetHistory"){
	        texthist=msg["txt"];
	        console.log("--получили историю сообщений:",texthist);	
		$("#chat_scroll_box").html("");
	        AddMessageToChat(msg["txt"]);	  	      	      
		$('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);
		//даем команду на обновление списка контактов у собеседника
		msg=[];
		msg={client:"client",command:"RefreshContactList",to_user_id:to_user_id};
		console.log("--даем команду на обновление списка контактов у собеседника:",JSON.stringify(msg));
		chatsocket.send(JSON.stringify(msg));
		msg=[];	      
		
	  };
	  if (msg["command"]=="ping"){	      
	      console.log("--ping=",msg["result"])	      	      
	      msg=[];
	  };    
	  //получили входящее сообщение от самого себя
	  if (msg["command"]=="AddEchoMessageToChat"){	      
	      console.log("--AddEchoMessageToChat=",msg["txt"])	      	      	      
	      AddMessageToChat(msg["txt"]);	      
	      $('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);	      
	      beep();
	      msg=[];
	  };    
	  
	};

	chatsocket.onerror = function(error) {
	  console.log("Ошибка " + error.message);
	};	    
	//ping
	if (pingpong==true){
	    var timerId = setInterval(function() {
		//если ктото есть онлайн, и from_user_id<>no тогда пингуем на всякий случай..
		if ((from_user_id!="no")&(online=="yes")){
			msg=[];
			msg={client:"client",command:"ping",from_user_id:from_user_id};		
			chatsocket.send(JSON.stringify(msg));
		    };
	    }, 20000);	
	};
    };
    //присваиваем себе id. Если нет в кукисах, запрашиваем у сервера
 });    
};