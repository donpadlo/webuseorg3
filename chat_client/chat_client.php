<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// данный код - это подгружаемая часть javascript кода,размещаемого на сайте-клиенте, отвечает за отрисовку диалогового окна
// беседы вопрошаеющего с оператором.
define('WUO_ROOT', dirname(__FILE__));

include_once (WUO_ROOT . '/../config.php');
include_once (WUO_ROOT . '/../class/sql.php'); // Класс работы с БД
include_once (WUO_ROOT . '/../class/config.php'); // Класс настроек
include_once (WUO_ROOT . '/../class/cconfig.php'); // Класс настроек
include_once (WUO_ROOT . '/../class/users.php'); // Класс работы с пользователями
                                              
// Загружаем все что нужно для работы движка
include_once (WUO_ROOT . '/../inc/connect.php'); // Соединяемся с БД, получаем $mysql_base_id
include_once (WUO_ROOT . '/../inc/config.php'); // Подгружаем настройки из БД, получаем заполненый класс $cfg
include_once (WUO_ROOT . '/../inc/functions.php'); // Загружаем функции
include_once (WUO_ROOT . '/../inc/login.php'); // Создаём пользователя $user

include_once (WUO_ROOT . '/../inc/func_chat.php'); // Загружаем рутинные функции для чата
                                                
// читаю настройки чата
$vl = new Tcconfig();
$ip_chat_server = $vl->GetByParam("ip-chat-server");
$ip_chat_port = $vl->GetByParam("ip-chat-port");
$ssl_pem = $vl->GetByParam("ssl-pem");
$ssl_pass = $vl->GetByParam("ssl-pass"); // ssl-pass
$chat_wellcome = $vl->GetByParam("chat-wellcome"); //
$chat_wss_url_noc = $vl->GetByParam("chat-wss-url-noc"); //
$chat_wss_url_help = $vl->GetByParam("chat-wss-url-help"); //

if ($ssl_pem != "") {
    $ssl_pem = "wss";
} else {
    $ssl_pem = "ws";
}
;
if ($ip_chat_server == "" or $ip_chat_port == "") {
    die("--укажите настройки IP сервера и порта в настройках веб интерфейса чата!\n");
}
;
$codepage = _GET('codepage');

?>
//
<script>    
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
function mainchat(){    
	$("#chat_message_box").remove();
	$("#chat_message_tab").remove();
	chat_user_id=getCookie("chat_user_id");
	chat_username=getCookie("chat_username");
	console.log("--читаю печеньки ",chat_user_id,chat_username);
	chatsocket=null;    
      	chatsocket = new WebSocket("<?php echo "$chat_wss_url_help";?>");
	chatsocket.onopen = function() {
	    console.log("Соединение с сервером установлено...");
	};      
	chatsocket.onmessage = function(event) {
	    console.log("Получены данные " + event.data);
	    parsechat(event);
	};
	  //реакция на закрытие соединения
	  chatsocket.onclose = function(event) {	    	    	      
	    if (event.wasClean) {
		console.log('Соединение закрыто чисто');
	    } 
	    else {
		console.log('Обрыв соединения'); // например, "убит" процесс сервера}
		console.log('Код: ' + event.code + ' причина: ' + event.reason);
		    //ну и снова пробуем начать всё с начала...	  
		    chatsocket=null;		    
		    timerId3=setTimeout(function() {		
			clearTimeout(timerId3);
			mainchat();
		    }, 10000);	    
	    };
	   };	
    	  //реакция на ошибки
	    chatsocket.onerror = function(error) {
	      console.log("Ошибка " + error.message);
	    };	        

};
function parsechat(event){
    msg=JSON.parse(event.data);
    if (msg["command"]=="GetOnline"){
	//если онлайн никого нет - отключаемся и больше до перезагруза страницы не пытаемся соедениться..
	if (msg["result"]["user_id"]==false){
	    console.log("--в онлайн чате никого нет..просим нас вырубить");	    
	    chatsocket.send(JSON.stringify({client:"client",command:"close"}));
	    chatsocket.onclose=null;
	    chatsocket.onmessage=null;
	    chatsocket.onclose=null;
	    chatsocket.onopen=null;
	    chatsocket=null;
	} else {
	  //мой собеседник
	  chat_opponent_id=msg["result"]["user_id"];
	  chat_opponent_name=msg["result"]["user_name"];
	  console.log("--мой собеседник:",chat_opponent_id,chat_opponent_name);
	  //если ктото есть - рисуем лепесток открытия чата
	  console.log("--кто то есть - рисуем лепесток открытия чата");
	  CreateOnlineTab();
	  if (chat_user_id==undefined){
	    console.log("--спрашиваю кто я");
	    chatsocket.send(JSON.stringify({client:"client",command:"GetMyId"}));
	  } else {
	      //говорю что я онлайн!
	      IAmOnline();
	      console.log("--из кукисов узнали что я ",chat_user_id,chat_username);
	  };
	};
    };
    if (msg["command"]=="iamonline"){
	if (msg["messages"]>0) CreateOnlineTabMessage();	
    };	
    if (msg["command"]=="Message"){
	AddMessage(msg);
    };	    
    if (msg["command"]=="GetMyId"){
	chat_user_id=msg["user_id"];
	chat_username=msg["name"];
	console.log("--устанавливаю печенюхи ",chat_user_id,chat_username);
	setCookie("chat_user_id", chat_user_id, {expires:1000000});      		
	setCookie("chat_username", chat_username, {expires:1000000});
	IAmOnline();
    };
    if (msg["command"]=="GetHistory"){
	RedrawHistory(msg["History"]);
    };
};


//////////////////////
//функции визуального отображения чата
//////////////////////

function CreateOnlineTab(){
    $("#chat_message_tab").remove();
    $("body" ).append( "<div id='chat_message_tab' onclick='CreateChatBox()' class='message_online'>Онлайн-консультант</div>" );
    $('#chat_message_tab').draggable();
    zhtml = document.documentElement;
    $('#chat_message_tab').css("top",zhtml.clientHeight/2);
    $('#chat_message_tab').fadeIn({duration: 2400});
};
function CreateOnlineTabMessage(){
    $("#chat_message_tab").remove();
    $("body" ).append( "<div id='chat_message_tab' onclick='CreateChatBox()' class='message_online'>Вам сообщение!<img height=20px src='http://downloadicons.net/sites/default/files/message-icon-84161.png'></div>" );
    $('#chat_message_tab').draggable();
    zhtml = document.documentElement;
    $('#chat_message_tab').css("top",zhtml.clientHeight/2);
    $('#chat_message_tab').fadeIn({duration: 10});
};

function CreateChatBox(){
    $("#chat_message_tab").remove();
    $("body" ).append("<div id='chat_message_box' class='chat_message_box'>"+
"<div id='chat_title' class='chat_title'>Онлайн-консультант</div>"+
    "<div id='chat_scroll_box' class='chat_scroll_box'>Загружаю историю переписки..</div>"+
    "<div id='chat_send_box' class='chat_send_box'>"+
    "<textarea onkeypress='ChatCtrlEnter(event)' oninput='textinput()' id='chat_enter_text' class='chat_enter_text' placeholder='Введите текст сообщения'></textarea><input class='chat_button_send' type='submit' onclick='ChatSendText()' value='Отправить'>"+
    "</div>"+
    "<div class='chat_button_close' onclick='CloseChatBox()' id='chat_button_close'>[x]</div>"+
"</div>" );    
    $('#chat_message_box').draggable();     
    console.log("--запрашиваем историю переписки (если есть)");
    chatsocket.send(JSON.stringify({client:"client",command:"GetHistory",chat_user_id:chat_user_id,chat_opponent_id:chat_opponent_id}));
    
};
function CloseChatBox(){
    $("#chat_message_box").remove();
    CreateOnlineTab();
} 

///////////////////////////////////
// функции отображения сообщений
///////////////////////////////////

function RedrawHistory(ha){
    //console.log(ha);  
    //$("#chat_scroll_box").html("");
    ret="<?php echo "$chat_wellcome</br>";?>";
    for (var key in ha) {	
	//console.log(ha[key]["txt"]);  
	dt=ha[key]["dt"];
	txt=ha[key]["txt"];
	from_name=ha[key]["from_name"];
	if (chat_user_id==ha[key]["from_id"]){cl="chatloginfrom";} else {cl="chatloginto";};
	ret=ret+"<span class='chatdt'>["+dt+"]</span><span class='"+cl+"'>"+from_name+":</span><span class='chatchat'>"+txt+"</span></br>";	
    };  
    $("#chat_scroll_box").html(ret);
    $('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);
    ReadAllMessages();
};

function ChatSendText(){
    if (chatsocket==null){
	    $("#chat_scroll_box").append("</br>...Сервер мессенджера не доступен...</br>");
	    $('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);	
    } else {
    if ($("#chat_enter_text").val().length>0){
	    msg=[];
	    msg={"to_user_id":chat_opponent_id,"from_user_id":chat_user_id,command:"SendMessage",sendtext:$("#chat_enter_text").val()};
	    console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
	    chatsocket.send(JSON.stringify(msg));    
	    txt="<span class='chatloginfrom'>"+chat_username+":</span><span class='chatchat'>"+$("#chat_enter_text").val()+"</span></br>";
	    $("#chat_scroll_box").append(txt);
	    $('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);
	    $("#chat_enter_text").val("");    
	};
    };
};
function ReadAllMessages(){
	msg=[];
	msg={"to_user_id":chat_user_id,"from_user_id":chat_opponent_id,command:"ReadAllMessages"};
	console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    
};
function ChatCtrlEnter(event){
   // console.log("Нажато:",event);
 if ((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))){
         ChatSendText();
 }
}
function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
};
function textinput(){
    if (cntwrite==0){
	msg=[];
	msg={command:"textwrite",from_user_id:chat_user_id,to_user_id:chat_opponent_id};
	console.log("--что-то пишем..:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));
	msg=[];	    	
    };
    cntwrite++;
    if (cntwrite>5){cntwrite=0;};
};    
function IAmOnline(){
	msg=[];
	msg={command:"iamonline",chat_user_id:chat_user_id};    
	console.log("--сообщаю серверу что я онлайн:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    
};
function AddMessage(msg){
    tt=msg["txt"];
    txt="<span class='chatloginto'>"+chat_opponent_name+":</span><span class='chatchat'>"+tt+"</span></br>";
    $("#chat_scroll_box").append(txt);    
    if  (typeof $('#chat_scroll_box').html() === 'undefined'){
	//чат свернут
	console.log("--ой, а окно то закрыто..");
	CreateOnlineTabMessage();
	beep();
    } else {
	//открыт част
	$('#chat_scroll_box').scrollTop($('#chat_scroll_box')[0].scrollHeight);
	$("#chat_enter_text").val("");   
	beep();
	ReadAllMessages();
    };
};