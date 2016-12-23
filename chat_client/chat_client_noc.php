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

$printable=_GET("printable");
if ($printable=="true"){die();};

if ($ssl_pem!=""){$ssl_pem="wss";} else {$ssl_pem="ws";};
if ($ip_chat_server=="" or $ip_chat_port==""){ die("--укажите настройки IP сервера и порта в настройках веб интерфейса чата!\n");};
//узнаем соответствие user->id==from_user_id
$sql="select * from chat_users where userid=$user->id";
$result = $sqlcn->ExecuteSQL($sql) or die('Неверный запрос на получение from_user_id: '.mysqli_error($sqlcn->idsqlconnection));
$from_user_id="";
while ($myrow = mysqli_fetch_array($result)) {
	$from_user_id = $myrow['id'];
};
if ($from_user_id==""){
 $sql="insert into chat_users (id,name,userid) values (null,'$user->login',$user->id)"; 
 $result = $sqlcn->ExecuteSQL($sql) or die('Не смог добавить нового участника чатов!: '.mysqli_error($sqlcn->idsqlconnection));
 $from_user_id=mysqli_insert_id($sqlcn->idsqlconnection); 
};
?>
//<script>
console.log("--инициализация чата..");
opponent_id="";	//кто выбран в списке контак листов
cntwrite=0;	//счетчик нажатий на клавиши. Каждое 5 нажатие - сообщаем что что-то пишем..
hold = "";
blinked=0; //признак "мерцания"
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
function mainchat(){    
	chat_user_id="<?php echo $from_user_id;?>";
	chat_username="<?php echo $user->fio ?>";
	console.log("--данные моего чата ",chat_user_id,chat_username);
	chatsocket=null;    
      	chatsocket = new WebSocket("<?php echo "$chat_wss_url_noc";?>");
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
function IAmOnline(){
	msg=[];
	msg={command:"iamonline",chat_user_id:chat_user_id};    
	console.log("--сообщаю серверу что я онлайн:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    
};

function parsechat(event){
 msg=JSON.parse(event.data);
    if (msg["command"]=="GetOnline"){
	IAmOnline();
    };
    if (msg["command"]=="iamonline"){
	//запрашиваем список контактов
	msg=[];
	msg={command:"GetContactList"};    
	console.log("--запрашиваем список контактов:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    	
	msg=[];
    };    
    //пришло сообщение об обновлении контакт-листа
    if (msg["command"]=="GetContactList"){
	console.log("--пришел контакт лист ",msg["result"]);
	RefreshContactList(msg["result"]); 
	gkontaklist=msg["result"]; //глобальный контакт лист
	console.log("Глобальный контакт лист:",gkontaklist);
    };
    //если пришла история сообщений
    if (msg["command"]=="GetHistory"){
	//console.log(msg["History"]);
	RedrawHistory(msg["History"]);
    };        
    if (msg["command"]=="Message"){
	AddMessage(msg);
    };	   
    if (msg["command"]=="textwrite"){
	Write(msg["from_user_id"]);
	setTimeout(NoWrite,4000,msg["from_user_id"]);
    };    
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
//получить имя из контакт листа по его id
function GetNameById(id){
    name="Noname";
    gkontaklist.forEach(function(entry) {
	if (id==entry["id"]){
	    name=entry["name"];
	};
    });
 return name;
};    
function RefreshContactList(cnt_list){ 
    cnt_html="";
    cnt=0;
    console.log("Перерисовываю список контактов:",cnt_list);
    cnt_list.forEach(function(entry) {
	read="";
	if (entry["read"]==1){
	  if (opponent_id!=entry["id"]){
	     read='<i class="fa fa-comment-o" aria-hidden="true"></i>';
	     cnt++;
	    };
	};
	if (entry["online"]==1){	    
          cnt_html=cnt_html+"<div onclick='ChangeToId("+entry["id"]+",\""+entry["name"]+"\")' class='online_chat_user' id='chatuser"+entry["id"]+"'>"+read+entry["name"]+"</div>";
      } else {
	cnt_html=cnt_html+"<div onclick='ChangeToId("+entry["id"]+",\""+entry["name"]+"\")' class='offline_chat_user' id='chatuser"+entry["id"]+"'>"+read+entry["name"]+"</div>";	  
      };
     //console.log(entry);
    });     
    $("#chat_contactlist_box").html(cnt_html);
    if (opponent_id!=""){$('#chatuser'+opponent_id).css('background-color','#e9ea52')};
 //console.log(cnt_list);
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
 //beep("On");
};
function BlinkStop(){
	blinkTitleStop();
	blinkTitle("<?php echo $cfg->sitename ?>","<?php echo $cfg->sitename ?>",1);    
	blinkTitleStop();
	blinked=0;
};
function ReadAllMessages(){
	msg=[];
	msg={"to_user_id":chat_user_id,"from_user_id":opponent_id,command:"ReadAllMessages"};
	console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    
	blinkTitleStop();
	blinkTitle("<?php echo $cfg->sitename ?>","<?php echo $cfg->sitename ?>",1);    
	blinkTitleStop();
	blinked=0;
	
};
//кого выбрали из списка контактов
function ChangeToId(id,chlogin){
    //рисуем заглушку на время загрузки
    $("#chat_message_box").html("Загружаю диалоги..");
    //высвечиваю выделенным
    if (opponent_id!=""){$('#chatuser'+opponent_id).css('background-color','#fff')};
    $('#chatuser'+id).css('background-color','#e9ea52')
    opponent_id=id;
    console.log("-выбрали пользователя ",id);
    //рисуем заголовок
    $("#chat_user_select").html("Сообщения для: "+chlogin);    
    msg=[];
    msg={command:"GetHistory",chat_user_id:chat_user_id,chat_opponent_id:opponent_id};
    console.log("--спрашиваем, историю сообщений:",JSON.stringify(msg));
    chatsocket.send(JSON.stringify(msg));
    msg=[];	      		  
    NoMessage(id);
};

function blinkTitle(msg1, msg2, delay, isFocus, timeout) {
  if (blinked==0)  {
    if (isFocus == null) {isFocus = false;}
    if (timeout == null) {timeout = false;}
    if(timeout){setTimeout(blinkTitleStop, timeout);}
    document.title = msg1;
    if (isFocus == false) {
        hold = window.setInterval(function() {
            if (document.title == msg1) {document.title = msg2;} else {document.title = msg1;}
        }, delay);
    }
    if (isFocus == true) {
        var onPage = false;
        var testflag = true;
        var initialTitle = document.title;
        window.onfocus = function() {onPage = true;};
        window.onblur = function() {onPage = false;testflag = false;};
        hold = window.setInterval(function() {
            if (onPage == false) {
                if (document.title == msg1) {document.title = msg2;} else {document.title = msg1;}
            }
        }, delay);
    }
    blinked=1;
   };
}
function blinkTitleStop() {
    clearInterval(hold);
    blinked=0;
}
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
    //console.log("Нажато:",event);
 if ((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))){
         ChatSendText();
 }
}

function ChatSendText(){
if ($("#chat_enter_text").val().length>0){    
    if (opponent_id!=""){
	msg=[];
	msg={"to_user_id":opponent_id,"from_user_id":chat_user_id,command:"SendMessage",sendtext:$("#chat_enter_text").val()};
	console.log("---отсылаем на сервер текст:",JSON.stringify(msg));
	chatsocket.send(JSON.stringify(msg));    
	txt="<span class='chatloginfrom'>"+chat_username+":</span><span class='chatchat'>"+$("#chat_enter_text").val()+"</span></br>";
	$("#chat_message_box").append(txt);
	$('#chat_message_box').scrollTop($('#chat_message_box')[0].scrollHeight);
	$("#chat_enter_text").val("");    
    } else {
	$().toastmessage('showWarningToast', 'Не выбран собеседник!');
    };
    } else {
	$().toastmessage('showWarningToast', 'Напишите что-то мудрое..');
    };
};

function textinput(){
    if (opponent_id!=""){    
	if (cntwrite==0){
	    msg=[];
	    msg={command:"textwrite",from_user_id:chat_user_id,to_user_id:opponent_id};
	    console.log("--что-то пишем..:",JSON.stringify(msg));
	    chatsocket.send(JSON.stringify(msg));
	    msg=[];	    
	};
	cntwrite++;
	if (cntwrite>5){cntwrite=0;};
    };	
};    

function RedrawHistory(ha){
    //console.log(ha);  
    //$("#chat_scroll_box").html("");
    ret="";
    for (var key in ha) {	
	//console.log(ha[key]["txt"]);  
	dt=ha[key]["dt"];
	txt=ha[key]["txt"];
	from_name=ha[key]["from_name"];
	if (chat_user_id==ha[key]["from_id"]){cl="chatloginfrom";} else {cl="chatloginto";};
	ret=ret+"<span class='chatdt'>["+dt+"]</span><span class='"+cl+"'>"+from_name+":</span><span class='chatchat'>"+txt+"</span></br>";	
    };      
    $("#chat_message_box").html(ret);
    $('#chat_message_box').scrollTop($('#chat_message_box')[0].scrollHeight);
    //помечаем, что все сообщения прочитаны..
    ReadAllMessages();
    BlinkStop();
};
function NoMessage(to_id){
  ht=$('#chatuser'+to_id).html();  
  ht=ht.replace('<i class="fa fa-comment-o" aria-hidden="true"></i>',"");  
  $('#chatuser'+to_id).html(ht)  
};
function YetMessage(to_id){
  ht=$('#chatuser'+to_id).html();  
  ht=ht.replace('<i class="fa fa-comment-o" aria-hidden="true"></i>',"");
  ht='<i class="fa fa-comment-o" aria-hidden="true"></i>'+ht;
  $('#chatuser'+to_id).html(ht)
  console.log("-отображаем что есть не прочитанные сообщения у ",to_id,ht);
};

function AddMessage(msg){    
    if (msg["from_user_id"]==opponent_id){
	tt=msg["txt"];
	txt="<span class='chatloginto'>"+GetNameById(opponent_id)+":</span><span class='chatchat'>"+tt+"</span></br>";
	$("#chat_message_box").append(txt);    	
	$('#chat_message_box').scrollTop($('#chat_message_box')[0].scrollHeight);
	$("#chat_enter_text").val("");   	
	    if ($("#chat_box").is(":visible")===false){
		ht=$('#chat_message_tab').html();  
		ht=ht.replace('<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>',"");
		ht='<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>'+ht;
		$('#chat_message_tab').html(ht); 	    
		blinkTitle("Внимание!","Новое сообщение!",500);	
		$().toastmessage('showSuccessToast', msg["txt"]);
		beep("New");	    
	    } else {
		//сообщаем что сообщения прочитаны..
		ReadAllMessages();
	    };
    } else {
	$().toastmessage('showSuccessToast', msg["txt"]);
	YetMessage(msg["from_user_id"]);	
	blinkTitle("Внимание!","Новое сообщение!",500);	
	beep("New");
	if ($("#chat_box").is(":visible")===false){
	    ht=$('#chat_message_tab').html();  
	    ht=ht.replace('<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>',"");
	    ht='<i class="fa fa-comment-o fa-3x" aria-hidden="true"></i>'+ht;
	    $('#chat_message_tab').html(ht); 
	};
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

function SendShowOnline(){
    	    msg=[];
	    msg={command:"showonlinetoconsole",from_user_id:chat_user_id,to_user_id:opponent_id};
	    chatsocket.send(JSON.stringify(msg));
}; 