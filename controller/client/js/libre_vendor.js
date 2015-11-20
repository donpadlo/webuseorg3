jQuery("#list2").jqGrid({
   	url:'controller/server/tmc/libre_vendor.php',
	datatype: "json",
   	colNames:[' ','Id','Имя','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20},
   		{name:'id',index:'id', width:55},
   		{name:'name',index:'name', width:200,editable:true},
   		{name:'comment',index:'comment', width:200,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
	autowidth: true,		
   	rowNum:10,	
   	rowList:[10,20,30],
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/tmc/libre_vendor.php",
    caption:"Справочник производителей"
});
var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('setGridHeight',$(window).innerHeight()/2);
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});