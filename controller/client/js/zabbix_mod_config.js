/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */


GetGrid();
function GetGrid(){
jQuery("#list2").jqGrid({
   	url:'controller/server/zabbix/config.php?orgid='+defaultorgid,
	datatype: "json",
   	colNames:['Id','Имя сервера','Сервер MySQL','Имя базы','Имя пользователя','Пароль','Действия'],
   	colModel:[   		
   		{name:'id',index:'id', width:55},
   		{name:'sname',index:'sname', width:50,editable:true},
                {name:'host',index:'host', width:50,editable:true},
                {name:'basename',index:'basename', width:50,editable:true},
                {name:'username',index:'username', width:50,editable:true},
                {name:'pass',index:'pass', width:50,editable:true},                
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
    editurl:'controller/server/zabbix/config.php?orgid='+defaultorgid,
    caption:"Сервера ZABBIX"  
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
