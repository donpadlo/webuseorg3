jQuery("#list2").jqGrid({
   	url:route+'controller/server/devicescontrol/scriptalert.php',
	datatype: "json",
   	colNames:['Активно?','Id','Группа','Скрипт','Комментарий','Ошибок','Интервал',''],
   	colModel:[
   		{name:'active',index:'active', width:20,search: false,editable:true,
		     stype: 'select',				
		     formatter: 'checkbox', edittype: 'checkbox', editoptions: {value: 'Yes:No'}, editable: true
		},
                {name:'id',index:'id', width:55,hidden:false,search: false},
   		{name:'group_name',index:'group_name', width:200,editable:true},
   		{name:'script_name',index:'script_name', width:200,editable:true},
   		{name:'comment',index:'comment', width:200,editable:true,search: false},		
		{name:'current_alert_count',index:'current_alert_count', width:200,search: false},
		{name:'lastupdatedt',index:'lastupdatedt', width:200,search: false},
		{name:'myac', width:70, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
	onSelectRow: function(ids) {	    
	  $('#common_edit').load(route + 'controller/client/view/devicescontrol/scriptalert.php&sid=' + ids);  
	},
	autowidth: true,		
	height: 200,	
   	grouping:true,
   	groupingView : {
            groupText : ['<b>{0} - {1} Item(s)</b>'],
	    groupCollapse : true,
            groupField : ['group_name']	    
   	},
   	pager: '#pager2',
   	sortname: 'id',
    viewrecords: true,
    autowidth: true,	
    rowNum:1000,
    //shrinkToFit: false,        
    scroll:1,
    sortorder: "asc",
    editurl:route+'controller/server/devicescontrol/scriptalert.php',
    caption:"Мониторинг выполнения скриптов"
});
jQuery("#list2").jqGrid('setGridHeight',$(window).innerHeight()/2);
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:true,del:true,search:true});
jQuery("#list2").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

