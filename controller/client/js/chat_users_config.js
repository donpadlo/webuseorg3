// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф


jQuery("#list_chat_users").jqGrid({
   	url:'controller/server/chat/get_sites_users.php',
	datatype: "json",
   	colNames:['Id','Имя','Логин','Привязка к сайтам','Действия'],
   	colModel:[
   		{name:'id',index:'id', width:55,hidden:true},
   		{name:'name',index:'name', width:100,editable:false},
   		{name:'login',index:'login', width:100,editable:false},
                {name:'params',index:'params', width:200,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
	autowidth: true,		
   	pager: '#pager_chat_users',
   	sortname: 'id',
	scroll:1,
    viewrecords: true,
    sortorder: "asc",
    editurl:'controller/server/chat/get_sites_users.php',
    caption:"Привязка пользователей к сайтам"
});
var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list_chat_users").jqGrid('setGridHeight',$(window).innerHeight()/2);
jQuery("#list_chat_users").jqGrid('navGrid','#pager_chat_users',{edit:true,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});