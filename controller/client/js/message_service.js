$( document ).ready(function() { 
    if(isMobile.any()){
	console.log("ERROR: на мобильном служба сообщений не доступна!");
    } else {
	console.log("-инициализация службы сообщений..");
	$.post(route+'controller/server/message_service/saveconfig.php',{
		save:false,
	}, function(data){	    
		message_service_config=JSON.parse(data);  
		console.log("-прочитали настройки службы сообщений..",message_service_config);
		StartMessageService(message_service_config);
	});    
    };
});
function StartMessageService(cfg){
	console.log(cfg);
	chatsocket=null;    
      	chatsocket = new WebSocket(cfg["message-wss-url"]);
	chatsocket.onopen = function() {
	    console.log("Соединение с сервером установлено...");
	};      
	chatsocket.onmessage = function(event) {
	    console.log("Получены данные " + event.data);	    
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
			StartMessageService(cfg);
		    }, 10000);	    
	    };
	   };	
    	  //реакция на ошибки
	    chatsocket.onerror = function(error) {
	      console.log("Ошибка " + error.message);
	    };	        
    
};