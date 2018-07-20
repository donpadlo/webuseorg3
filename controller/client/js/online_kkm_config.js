function GetGrid(){
jQuery("#list2").jqGrid({
   	url:route+'controller/server/config_online.php',
	datatype: "json",
   	colNames:['Id','Название','ИНН','Действия'],
   	colModel:[   		
   		{name:'id',index:'id', width:25},
   		{name:'kname',index:'kname', width:50,editable:true},
                {name:'inn',index:'inn', width:50,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
		onSelectRow: function(ids) {
                  $.get(route+'controller/client/view/config_dop_online.php&idkkm='+ids, function(data) {
                    $("#config_online").html(data);                            
                  });
		},
	autowidth: true,			
   	rowNum:50,	   	
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
	height: 400,
    viewrecords: true,
    sortorder: "asc",
    editurl:route+"controller/server/config_online.php&orgid="+defaultorgid,
    caption:"Онлайн кассы Атол"  
});
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:true,del:true,search:false},{},{top: 0, left: 0, width: 500},{},{multipleSearch:false},{closeOnEscape:true} );
};

GetGrid();