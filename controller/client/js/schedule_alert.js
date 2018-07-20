$(document).ready(function() {
    schedule_get_ready=0;
    var timerId2 = setInterval(function() {     
    //не спешно загружаем уведомления о запланированных задачах.
     $.get(route+'controller/server/schedule/schedule_allert_info.php&random_id='+Math.random(), function( data ) {
	 if (data!=""){	    
	     if (schedule_get_ready==1){
		 $('#schedule_alert_info').html(data);	    	
	     } else {
		 txt = '<div id="schedule_alert_info">';
		 txt = txt+data;
		 txt = txt + '</div>';	    		
		 $('#common_messages_for_all').append(txt);	    
		 $('#schedule_alert_info').html(data);	   
		 schedule_get_ready=1;
	 }    	
	 } else {
	   $('#schedule_alert_info').html(data);	  
	 };
     });
    }, 10000);
});    