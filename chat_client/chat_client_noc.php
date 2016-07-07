<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// данный код - это подгружаемая часть javascript кода,размещаемого на сайте "УЧЁТ ТМЦ и другие плюшки"
// отрисовывает список контактов, и релизует переписку с ними
 
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

//узнаем соответствие user->id==from_user_id
$sql="select * from chat_users where userid=$user->id";
$result = $sqlcn->ExecuteSQL($sql)
		or die('Неверный запрос на получение from_user_id: '.mysqli_error($sqlcn->idsqlconnection));
$from_user_id="";
while ($myrow = mysqli_fetch_array($result)) {
	$from_user_id = $myrow['id'];
};
if ($from_user_id==""){
 $sql="insert into chat_users (id,name,userid) values (null,'$user->login',$user->id)"; 
 $result = $sqlcn->ExecuteSQL($sql) or die('Не смог добавить нового участника чатов!: '.mysqli_error($sqlcn->idsqlconnection));
 $from_user_id=mysqli_insert_id($sqlcn->idsqlconnection); 
};
echo "from_user_id=$from_user_id;";  
?>
//<script>
console.log("--инициализация чата..");
change_to_id="";//кто выбран в списке контак листов
cntwrite=0;	//счетчик нажатий на клавиши. Каждое 5 нажатие - сообщаем что что-то пишем..

console.log('--загружаем звуки');
$( document ).ready(function() {    
    var jqxhr = $.getJSON( "chat_client/sounds2.json", function() {  
    })
    jqxhr.complete(function(data) {
      console.log( "--ok");
      sounds=JSON.parse(data.responseText);
       mainchat();
    });    
    
});   

function beep(tp) {
    if (tp=="New"){	
	var snd = new Audio(sounds["New"]);  
	snd.play();
    };
    if (tp=="On"){	
	var snd = new Audio(sounds["On"]);  
	snd.play();
    };
    if (tp=="Msg"){	
	var snd = new Audio(sounds["Msg"]);  
	snd.play();
    };        
};
function ChatCtrlEnter(event){
    console.log("Нажато:",event);
 if ((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))){
         ChatSendText();
 }
}
function textinput(){
    if (change_to_id!=""){    
	if (cntwrite==0){
	    msg=[];
	    msg={client:"noc",command:"textwrite",from_user_id:from_user_id,to_user_id:change_to_id};
	    console.log("--что-то пишем..:",JSON.stringify(msg));
	    chatsocket.send(JSON.stringify(msg));
	    msg=[];	    
	};
	cntwrite++;
	if (cntwrite>5){cntwrite=0;};
    };	
};    
function ChatSendText(){
    if (change_to_id!=""){
	msg=[];
	msg={to_user_id:change_to_id,client:"noc",command:"SendMessage",from_user_id:from_user_id,sendtext:$("#chat_enter_text").val()};
	console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));
	$("#chat_enter_text").val("");
	msg=[];	      		  
    };
};
function YetMessage(to_id){
  ht=$('#chatuser'+to_id).html();  
  ht=ht.replace('<i class="fa fa-comment-o" aria-hidden="true"></i>',"");
  ht='<i class="fa fa-comment-o" aria-hidden="true"></i>'+ht;
  $('#chatuser'+to_id).html(ht)
  console.log("-отображаем что есть не прочитанные сообщения у ",to_id,ht);
};
function HideMessenger(){
    $('#chat_box').hide();    
};
function ViewContactListBox(){
    ht=$('#chat_message_tab').html();  
    ht=ht.replace('<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>',"");    
    $('#chat_message_tab').html(ht);          
    $('#chat_box').show();        
};
function AddMessageToChat(txt){
 console.log("--добавляем в окошко",txt);
 $("#chat_message_box").append(txt);
};
//кого выбрали из списка контактов
function ChangeToId(id,chlogin){
    //рисуем заглушку на время загрузки
    $("#chat_message_box").html("Загружаю диалоги..");
    //
    if (change_to_id!=""){$('#chatuser'+change_to_id).css('background-color','#fff')};
    $('#chatuser'+id).css('background-color','#e9ea52')
    change_to_id=id;
    console.log("-выбрали пользователя ",id);
    //рисуем заголовок
    $("#chat_user_select").html("Сообщения для: "+chlogin);
    msg=[];
    msg={client:"client",command:"GetHistory",from_user_id:from_user_id,to_user_id:id};
    console.log("--спрашиваем, историю сообщений:",JSON.stringify(msg));
    chatsocket.send(JSON.stringify(msg));
    msg=[];	      		  
    blinkTitleStop();    
    //запрашиваем историю переписки
};
function RefreshContactList(cnt_list){ 
    cnt_html="";
    cnt=0;
    cnt_list.forEach(function(entry) {
	read="";
	if (entry["read"]==1){
	  if (change_to_id!=entry["id"]){
	     read='<i class="fa fa-comment-o" aria-hidden="true"></i>';
	     cnt++;
	    };
	};
	if (entry["online"]==1){	    
          cnt_html=cnt_html+"<div onclick='ChangeToId("+entry["id"]+",\""+entry["name"]+"\")' class='online_chat_user' id='chatuser"+entry["id"]+"'>"+read+entry["name"]+"</div>";
      } else {
	cnt_html=cnt_html+"<div onclick='ChangeToId("+entry["id"]+",\""+entry["name"]+"\")' class='offline_chat_user' id='chatuser"+entry["id"]+"'>"+read+entry["name"]+"</div>";	  
      };
     console.log(entry);
    });     
    $("#chat_contactlist_box").html(cnt_html);
    if (change_to_id!=""){$('#chatuser'+change_to_id).css('background-color','#e9ea52')};
 console.log(cnt_list);
 //если есть хотяб 1 не прочитанное сообщение, на лепестке выдаем конвертик
 if (cnt>0){
    ht=$('#chat_message_tab').html();  
    ht=ht.replace('<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>',"");
    ht='<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>'+ht;
    $('#chat_message_tab').html(ht); 
 } else {
    ht=$('#chat_message_tab').html();  
    ht=ht.replace('<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>',"");    
    $('#chat_message_tab').html(ht);      
 };
};
function Write(to_id){
    console.log("--говорю что пишет ",to_id);
    ht=$('#chatuser'+to_id).html();  
    ht=ht.replace('<i class="fa fa-pencil" aria-hidden="true"></i>',"");
    ht='<i class="fa fa-pencil" aria-hidden="true"></i>'+ht;
    console.log(ht);
    $('#chatuser'+to_id).html(ht);     
};
function NoWrite(to_id){
    console.log("--говорю что уже не пишет ",to_id);
    ht=$('#chatuser'+to_id).html();  
    ht=ht.replace('<i class="fa fa-pencil" aria-hidden="true"></i>',"");
    $('#chatuser'+to_id).html(ht);         
};    
function mainchat(){
 $( document ).ready(function() {         
    if (typeof chatsocket == 'undefined') {    
	//соединяемся с сервером $chat_wss_url_noc
	chatsocket = new WebSocket("<?php echo "$chat_wss_url_noc"; ?>");
	chatsocket.onopen = function() {
	    console.log("Соединение с сервером установлено...");
	    console.log("-рисую лепесток месенджера");
	    $('#chat_message_tab').draggable();
	    zhtml = document.documentElement;
	    $('#chat_message_tab').css("top",zhtml.clientHeight/2);
	    $('#chat_message_tab').fadeIn({duration: 2400});
	    $('#chat_box').draggable();
	    zhtml = document.documentElement;
	    $('#chat_box').css("top",zhtml.clientHeight/2);
	    $('#chat_box').css("left",(zhtml.clientWidth-500)/2);
	    
	};
	  //реакция на закрытие соединения
	  chatsocket.onclose = function(event) {
	    if (event.wasClean) {console.log('Соединение закрыто чисто');} else {console.log('Обрыв соединения'); // например, "убит" процесс сервера}
	    console.log('Код: ' + event.code + ' причина: ' + event.reason);
	    //ну и снова пробуем начать всё с начала...	    
	    mainchat();
	  };};
	  //реакция на входящее сообщения
	  chatsocket.onmessage = function(event) {
	    console.log("Получены данные " + event.data);
	    msg=JSON.parse(event.data);
	    if (msg["command"]=="Hello"){
		console.log("--сервер сказал Hello, значит запрашиваем всякие данные у него..");
		msg=[];
		msg={client:"noc",command:"GetContactList",from_user_id:from_user_id};
		console.log("---сначала спросим список контактов:",JSON.stringify(msg));
		chatsocket.send(JSON.stringify(msg));
		msg=[];	      		  		
	    };
	    //получили список контактов..
	    if (msg["command"]=="GetContactList"){
	       RefreshContactList(msg["result"]); 
	    };
	    //обновляем список контактов
	    if (msg["command"]=="RefreshContactList"){
	       RefreshContactList(msg["result"]); 
	    };
	    if (msg["command"]=="GetHistory"){
		  texthist=msg["txt"];
		  console.log("--получили историю сообщений:",texthist);	
		  $("#chat_message_box").html("");
		  AddMessageToChat(msg["txt"]);	  	      	      
		  $('#chat_message_box').scrollTop($('#chat_message_box')[0].scrollHeight);
		    console.log("--помечаю что все сообщения от этого пользователя прочитаны..");
		    msg=[];
		    msg={client:"noc",command:"AllMessagesRead",from_user_id:from_user_id,to_user_id:change_to_id};
		    console.log("---прочитано:",JSON.stringify(msg));
		    chatsocket.send(JSON.stringify(msg));
		    msg=[];	      		  		
		    ht=$('#chatuser'+change_to_id).html();  
		    ht=ht.replace('<i class="fa fa-comment-o" aria-hidden="true"></i>',"");
		    $('#chatuser'+change_to_id).html(ht)
	    };	    
	    if (msg["command"]=="textwrite"){
		Write(msg["from_user_id"]);
		setTimeout(NoWrite,4000,msg["from_user_id"]);
	    };
	    if (msg["command"]=="AddEchoMessageToChat"){	      
		console.log("--AddEchoMessageToChat=",msg["txt"])	      	      	      
		if ((msg["from_user_id"]==change_to_id)||(msg["from_user_id"]==from_user_id)){
		    console.log("--вывожу на экран");
		    AddMessageToChat(msg["txt"]);	      
		    $('#chat_message_box').scrollTop($('#chat_message_box')[0].scrollHeight);	      
		    //помечаю что все сообщения от этого пользователя прочитаны..
		    console.log("--помечаю что все сообщения от этого пользователя прочитаны..");
		    msg=[];
		    msg={client:"noc",command:"AllMessagesRead",from_user_id:from_user_id,to_user_id:change_to_id};
		    console.log("---прочитано:",JSON.stringify(msg));
		    chatsocket.send(JSON.stringify(msg));
		    msg=[];	      		  		
		    
		} else {
		    console.log("--тоаст мессадже");
		    $().toastmessage('showSuccessToast', msg["txt"]);
		    //и в списке контактов помечаю что есть сообщения...
		    YetMessage(msg["from_user_id"]);
		    beep("Msg");
		    blinkTitle("Внимание!","Новое сообщение!",500);
		};		
		msg=[];
	    };   	    
	  };  
    	  //реакция на ошибки
	    chatsocket.onerror = function(error) {
	      console.log("Ошибка " + error.message);
	    };	  
	 //раз в минуту - запрос на обновление справочника	
	    var timerId = setInterval(function() {
			msg=[];
			msg={client:"noc",command:"GetContactList",from_user_id:from_user_id};
			console.log("---- запрос на обновление справочника:",JSON.stringify(msg));
			chatsocket.send(JSON.stringify(msg));
			msg=[];	      		  		
			chatsocket.send(JSON.stringify(msg));		    
	    }, 60000);	
	 
    }
 });
};    