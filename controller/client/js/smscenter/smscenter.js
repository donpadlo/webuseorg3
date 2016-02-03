function GetGrid(){
jQuery("#list2").jqGrid({
   	url:'controller/server/smscenter/smscenter.php?orgid='+defaultorgid,
	datatype: "json",
   	colNames:['Id','Агент','Логин','Пароль','Отправитель','Файл настройки вызова','Порог отправки','Основной','Действия'],
   	colModel:[   		
   		{name:'id',index:'id', width:55},
   		{name:'agname',index:'agname', width:200,editable:true},
                {name:'smslogin',index:'smslogin', width:100,editable:true},
                {name:'smspass',index:'smspass', width:100,editable:true},
                {name:'sender',index:'sender', width:100,editable:true},
                {name:'fileagent',index:'fileagent', width:200,editable:true},
                {name:'smsdiff',index:'smsdiff', width:50,editable:true},
                {name:'sel',index:'sel', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'}},                
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
	autowidth: true,			
   	rowNum:10,	   	
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
	height: 140,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/smscenter/smscenter.php?orgid="+defaultorgid,
    caption:"Агенты отправки СМС"  
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
$(document).ready(function() {
    // загружаем табличку с агентами
      GetGrid();    
    // отображаем когда начинаем отправлять СМС
    $("#time_to_cur_div").load("controller/server/smscenter/getsmsdatesend.php");
    $('#setsendsms').click(function () {
	$("#time_to_cur_div").load("controller/server/smscenter/getsmsdatesend.php?set=true&sec="+$("#time_to").val());
    });
});