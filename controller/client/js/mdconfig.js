jQuery("#list2").jqGrid({
   	url:'controller/server/config/md.php',
	datatype: "json",
   	colNames:['Id','Имя','Комментарий','Автор','Включено','Действия'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false},   		
   		{name:'name',index:'name', width:100,editable:false},
                {name:'comment',index:'comment', width:200,editable:false},
                {name:'copy',index:'copy', width:120,editable:false},
		{name:'active',index:'active', width:20,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: '1:0'}},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
	autowidth: true,		
   	pager: '#pager2',
   	sortname: 'id',	
	rowNum: 30,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/config/md.php",
    caption:"Модули системы"
});
// загружаем навигационную панель
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
