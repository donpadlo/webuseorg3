<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// данный код - это подгружаемая часть javascript кода,размещаемого на сайте "УЧЁТ ТМЦ и другие плюшки"
// отрисовывает список контактов, и релизует переписку с ними
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

include_once (WUO_ROOT . '/../service/message_func.php'); // Загружаем рутинные функции для чата
                                                
// читаю настройки сервиса сообщений
$ip_message_port = $cfg->GetByParam("message-port");
$ip_message_server = $cfg->GetByParam("message-server");
$message_wss_url = $cfg->GetByParam("message-wss-url"); //        

$printable = _GET("printable");
if ($printable == "true") {die();};

if ($ip_message_server == "" or $ip_message_port == "" or $message_wss_url == "") {
    die("--укажите настройки IP сервера и порта в настройках веб интерфейса службы сообщений!\n");
};

//if ($user->id==1){
?>
//<script>    
 $( document ).ready(function() {        
     //массив сообщений заббикс
    if (localStorage["zbinfo"]!=undefined) {
	old_zabbix_packet=JSON.parse(localStorage["zbinfo"]);
    } else {
	old_zabbix_packet=[]; //массив с сообщениями от заббикс
    };    
    //подготавливаю zabbix
    $('<audio id="zabbix_sound"><source src="/media/notify.ogg" type="audio/ogg"><source src="/media/notify.mp3" type="audio/mpeg"><source src="/media/notify.wav" type="audio/wav"></audio>').appendTo('body');
    txt = '<div id="zabbix_mod_button" style="left:100%;margin-left:-30px;position:absolute;top:10px;">';
    txt = txt + '<img id="zab_img" src="controller/client/themes/bootstrap/img/zabbix.gif">';
    txt = txt + '</div>';
    $('body').append(txt);
    $('body').append('<div id="zabbix_mod_win" title="Окно сообщений Zabbix">События Zabbix загружаются.. Подождите несколько секунд!Настройка подписок <a href="?content_page=zabbix_mon">тут</a></div>');    
    
    //если в заббиксе чтото есть, ругаюсь рамкой..
    cnt=0;
    for (pz in old_zabbix_packet) {cnt++;};
    if (cnt==0){
	$('#zab_img').css('border', 'green 1px solid');
	$('#zabbix_mod_win').html('На текущий момент проблем в работе сети не наблюдается..Настройка подписок <a href="?content_page=zabbix_mon">тут</a>');
    } else {
	$('#zab_img').css('border', 'red 3px solid');
    };
    
     //массив сообщений sbss
    if (localStorage["sbssinfo"]!=undefined) {
	old_sbssinfo_packet=JSON.parse(localStorage["sbssinfo"]);
    } else {
	old_sbssinfo_packet=[]; //массив с сообщениями от SBSS
    };    
    
    
    //загружаю звук звонка
    $('<audio id="call_sound"><source src="/media/scall.ogg" type="audio/ogg"><source src="/media/scall.mp3" type="audio/mpeg"><source src="/media/scall.wav" type="audio/wav"></audio>').appendTo('body');    

    console.log("[m]-инициализация службы сообщений..");    
    console.log("[m]--загружаю звуки..");
	var jqxhr = $.getJSON( "chat_client/sounds2.json", function() {  
	});
	
	jqxhr.complete(function(data) {
	  console.log( "[m]--загрузил");
	  sounds=JSON.parse(data.responseText);
	  message_service_start();
	});    
    
    function message_service_start(){ 
	console.log( "[m]--пробую стартовать сервис..");
	msocket=null;    
      	msocket = new WebSocket("<?php echo "$message_wss_url";?>");
	msocket.onopen = function() {
	    console.log( "[m]---соединение установлено!");
	};      
	msocket.onmessage = function(event) {
	    console.log( "[m]---получены данные с сервиса сообщений!");
	    command_run(event);
	};
	  //реакция на закрытие соединения
	  msocket.onclose = function(event) {	    	    	      
	    if (event.wasClean) {
		console.log( "[m]---соединение закрыто!");
	    } 
	    else {
		console.log( "[m]---обрыв соединения!");
		console.log('Код: ' + event.code + ' причина: ' + event.reason);
		    //ну и снова пробуем начать всё с начала...	  
		    msocket=null;		    
		    timerId3=setTimeout(function() {		
			clearTimeout(timerId3);
			message_service_start();
		    }, 10000);	    
	    };
	   };	
    	  //реакция на ошибки
	    msocket.onerror = function(error) {
	      console.log("[m]---ошибка:" + error.message);
	    };	
	
    };     
    function command_run(event){
	console.log(event);
	msg=JSON.parse(event.data);	
	if (msg["command"]=="whois"){ItsMeMario();};
	if (msg["command"]=="message"){
	    $().toastmessage('showToast', {
		text     : msg["body"],
		sticky   : msg["sticky"],
		position : 'top-right',
		type     : msg["type"]
	    });	
	    var snd = new Audio(sounds["New"]);  
	    snd.play();
	};
	if (msg["command"]=="zabbix_packet_to_noc"){	    
		console.log("-пришли сообщения от заббикс",msg["packet"]);
		if (JSON.stringify(old_zabbix_packet)!=JSON.stringify(msg["packet"])){
		    ShowMessageZabbix(msg["packet"]);
		    old_zabbix_packet=msg["packet"];
		    localStorage.setItem("zbinfo", JSON.stringify(old_zabbix_packet));
		};	   
	};
	if (msg["command"]=="sbss_packet_to_noc"){	    
		console.log("-пришли сообщения от sbss",msg["packet"]);
		if (JSON.stringify(old_sbssinfo_packet)!=JSON.stringify(msg["packet"])){
		    FillingSBSSRun();
		    console.log(msg["packet"].cnt_new,old_sbssinfo_packet.cnt_new);
		    if (Number(msg["packet"].cnt_new)>Number(old_sbssinfo_packet.cnt_new)){
			var snd = new Audio(sounds["On"]);  
			snd.play();
			$().toastmessage('showToast', {
			    text     : "Для вас новый тикет!",
			    sticky   : "true",
			    position : 'top-right',
			    type     : "warning"
			});			
		    };
		    if (msg["packet"].new_in_kol>old_sbssinfo_packet.new_in_kol){
			var snd = new Audio(sounds["On"]);  
			snd.play();
			$().toastmessage('showToast', {
			    text     : "В Вашем подразделении новый тикет!",
			    sticky   : "true",
			    position : 'top-right',
			    type     : "warning"
			});			
		    };
		    
		    old_sbssinfo_packet=msg["packet"];
		    localStorage.setItem("sbssinfo", JSON.stringify(old_sbssinfo_packet));
		};	   
	};
	
	if (msg["command"]=="call_to_noc"){	    
	    console.log("-пришел звонок в НОС!",msg["packet"]);
	    ShowCallNoc(msg["packet"]);
	};
    };
    function ItsMeMario(){
	msg=[];
	msg={command:"iam",user_id:defaultuserid};    
	console.log("--сообщаю серверу кто я:",JSON.stringify(msg));
	msocket.send(JSON.stringify(msg));    
    };    
    function ShowCallNoc(packet){
	html="<strong>Внимание звонок!</strong><br/>";
	for (bill in packet) {
	    for (call in packet[bill]) {
		html=html+packet[bill][call].name+"<br/>";
		html=html+"Договор:"+packet[bill][call].number+",баланс "+packet[bill][call].balance+"<br/>";
		html=html+packet[bill][call].address+"</br>";
		html=html+'<i class="fa fa-phone"></i> '+packet[bill][call].phone+"</br>";
		str=document.location.href;
		if(str.indexOf('https://noc.yarteleservice.ru/index.php?content_page=lanbilling/sos') + 1) {
		    html=html+"<button onclick='EventCall("+bill+","+packet[bill][call].number+");' class=\"btn btn-primary btn-xs\" type='button'>Перейти в карточку</button><hr/>";
		} else {
		    html=html+"<a class=\"btn btn-primary btn-xs\" href=\"https://noc.yarteleservice.ru/index.php?content_page=lanbilling/sos&billing_id="+bill+"&number="+packet[bill][call].number+"\">Перейти в карточку</a><hr/>";
		};
		console.log(packet[bill][call]);
	    };
	};
	$('#call_sound')[0].play();
	    $().toastmessage('showToast', {
		text     : html,
		sticky   : "true",
		position : 'top-right',
		type     : "success"
	    });
    };
    function ShowMessageZabbix(packet){	
	ht = '<table class="table table-striped table-hover table-condensed">';
	ht = ht + '<thead><tr><th>Группа</th><th>Хост</th><th>Проблема</th><th>Время</th><th>Приоритет</th><th>Комментарий</th><tr></thead><tbody>';
	$('#zabbix_mod_win').html('');
	cnt=0;
	for (pz in packet) {		    	     
	pd = 'success';
	cnt++;
	switch (packet[pz].prinum) {		
		case '0':
			pd = 'success';
			break;
		case '1':
			pd = 'info';
			break;
		case '2':
			pd = 'warning';
			break;
		case '3':
		case '4':
		case '5':
			pd = 'error';
			break;
	};
	    ht = ht + '<tr class=' + pd + '><td>' + packet[pz].group_name + '</td><td>' + packet[pz].hosterr + '</td><td>' + packet[pz].description + '</td><td>' + packet[pz].lastchange + '</td><td>' + packet[pz].priority + '</td><td>' +packet[pz].comment + '</td></tr>';		    
	};					
	ht = ht + '</tbody></table></br>Настройка подписок <a href="?content_page=zabbix_mon">тут</a>';
	$('#zabbix_mod_win').html(ht);	
	$('#zabbix_sound')[0].play();
	$('#zabbix_mod_win').dialog('open');	
	if (cnt==0){
	    $('#zab_img').css('border', 'green 1px solid');
	    $('#zabbix_mod_win').html('На текущий момент проблем в работе сети не наблюдается..Настройка подписок <a href="?content_page=zabbix_mon">тут</a>');
	} else {
	    $('#zab_img').css('border', 'red 3px solid');
	};
    };
    $('#zab_img').click(function() {
	ShowMessageZabbix(old_zabbix_packet);
    });
    //окошко сообщения заббикс
    $('#zabbix_mod_win').dialog({
	    autoOpen: false,
	    resizable: true,
	    height: 440,
	    width: 640,
	    modal: true,
	    buttons: {
		    'Ok': function() {
			    $(this).dialog('close');
		    }
	    }
    });
    
 });
//</script>
<?php
//};
?>