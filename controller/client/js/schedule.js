addOptions={
    top: 100, left: 100, width: 500
};   

function ViewConfigs(){    
    jQuery("#list2").jqGrid({
            url:route+'controller/server/schedule/schedule.php',
            datatype: "json",
            colNames:['Id','Начало','Конец','Заголовок','СМС','Почта','Сообщение','Комментарий','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'dtstart',index:'dtstart', width:100,editable:false},
		    {name:'dtend',index:'dtend', width:100,editable:false},
                    {name:'title',index:'title', width:100,editable:true},
		    {name:'sms',index:'sms', width:100,editable:true},
		    {name:'mail',index:'mail', width:100,editable:true},
		    {name:'view',index:'view', width:100,editable:true},		    
		    {name:'comment',index:'comment', width:100,hidden:true},		    
                    {name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager2',
            sortname: 'id',
            scroll:1,
            height: 400,
        viewrecords: true,
        sortorder: "asc",
        editurl:route+'controller/server/schedule/schedule.php',
        caption:"Расписание"  
    });   
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-plus\"></i> Добавить ",                              
        title: "Добавить расписание",
        buttonicon: "none",
        position:"last",
	onClickButton:function(){
	       schedule_mode="add";
	       $("#schedule-title").val("");
	       $("#schedule_dtstart").val("");
	       $("#schedule_dtend").val("");
	       $("#schedule-sms").prop("checked",false);
	       $("#schedule-mail").prop("checked",false);
	       $("#schedule-message").prop("checked",false);
	       $("#schedule-comment").val("");
               $("#schedule-dialog" ).dialog("open" );                             
	} 
    });        
    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-edit\"></i> Изменить ",                              
        title: "Изменит расписание",
        buttonicon: "none",
        position:"last",
	onClickButton:function(){
            var id = jQuery("#list2").jqGrid('getGridParam','selrow');	    
	    if (id){	    
	       ret=jQuery("#list2").jqGrid('getRowData',id);
	       $("#schedule-title").val(ret.title);
	       $("#schedule_dtstart").val(ret.dtstart);
	       $("#schedule_dtend").val(ret.dtend);	   
	       if (ret.sms==1) {$("#schedule-sms").prop("checked",true);} else {$("#schedule-sms").prop("checked",false);};
	       if (ret.mail==1) {$("#schedule-mail").prop("checked",true);} else {$("#schedule-mail").prop("checked",false);};
	       if (ret.view==1) {$("#schedule-message").prop("checked",true);} else {$("#schedule-message").prop("checked",false);};
	       $("#schedule-comment").val(ret.comment);
	       
	       schedule_mode="edit";
               $("#schedule-dialog" ).dialog("open" );                             
	   } else {
	       $().toastmessage('showWarningToast', 'Не выбрана строка для редактирования!');
	   };
	} 
    });            
};
$( document ).ready(function() {
    jQuery.datetimepicker.setLocale('ru');
    $("#schedule_dtstart").datetimepicker({
	format:'Y-m-d H:i:00',		    
	dayOfWeekStart: 1,		
    });
    $("#schedule_dtend").datetimepicker({
	format:'Y-m-d H:i:00',		    
	dayOfWeekStart: 1,		
    });    
    $("#schedule-dialog" ).dialog({
      autoOpen: false,        
      resizable: false,      
      minHeight:"auto",      
      width: 640,
      modal: true,
      buttons: {
	    "Ok": function() {				
		  if ($("#schedule-title").val()!=""){
		    if ($("#schedule-comment").val()!=""){
			if ($("#schedule_dtstart").val()!=""){
				$.post(route+"controller/server/schedule/schedule_add_edit.php",{
				    schedule_mode:schedule_mode,
				    id:jQuery("#list2").jqGrid('getGridParam','selrow'),
				    schedule_title:$("#schedule-title").val(),	    
				    schedule_comment:$("#schedule-comment").val(),	    
				    schedule_dtstart:$("#schedule_dtstart").val(),
				    schedule_dtend:$("#schedule_dtend").val(),
				    schedule_sms:$("#schedule-sms").prop("checked"),
				    schedule_mail:$("#schedule-mail").prop("checked"),
				    schedule_messaqe:$("#schedule-message").prop("checked"),
				},
				function(data){
				    $("#schedule-dialog" ).dialog("close"); 
				    $().toastmessage('showWarningToast',data);      
				    jQuery("#list2").jqGrid().trigger('reloadGrid');                    				    				    
				});			    
			} else {
			  $().toastmessage('showWarningToast', 'Дата начала должна быть заполнена!');      
			};		      
		    } else {
		      $().toastmessage('showWarningToast', 'Комментарий должен быть заполнен!');      
		    };		      
		  } else {
		    $().toastmessage('showWarningToast', 'Заголовок должен быть заполнен!');      
		  };		
	    }   
	}
    });
    ViewConfigs();    
});