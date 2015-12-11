var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid({
   	url:'controller/server/tmc/libre_group.php',
	datatype: "json",
   	colNames:[' ','Id','Имя','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20},
   		{name:'id',index:'id', width:55,hidden:true},
   		{name:'name',index:'name', width:200,editable:true},
   		{name:'comment',index:'comment', width:200,editable:true},
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
    editurl:"controller/server/tmc/libre_group.php",
    caption:"Группы номенклатуры",
	onSelectRow: function(ids) {
		if(ids == null) {
			ids=0;
			if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 )
			{
				jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/tmc/libre_group_sub.php?q=1&groupid="+ids,page:1});
				jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/tmc/libre_group_sub.php?q=1&groupid="+ids,page:1})
				.trigger('reloadGrid');				
			}
		} else {			
			jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/tmc/libre_group_sub.php?q=1&groupid="+ids,page:1});
			jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/tmc/libre_group_sub.php?q=1&groupid="+ids,page:1})
			.trigger('reloadGrid');			
		}
	}    
});
jQuery("#list2").jqGrid('setGridHeight',$(window).innerHeight()/2);
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});

jQuery("#list10_d").jqGrid({
	height: 100,
	autowidth: true,		
   	url:'controller/server/tmc/libre_group_sub.php',
	datatype: "json",
   	colNames:[' ','Id', 'Параметр', 'Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20},
   		{name:'id',index:'id', width:55,hidden:true},
   		{name:'name',index:'name', width:200,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
   	rowNum:5,   	
   	pager: '#pager10_d',
   	sortname: 'id',
	scroll:1,
	viewrecords: true,
	sortorder: "asc",
	caption:"Параметры группы номенклатуры"
}).navGrid('#pager10_d',{add:true,edit:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );