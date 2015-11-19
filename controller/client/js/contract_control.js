jQuery.extend(jQuery.jgrid.defaults, {ajaxSelectOptions: { cache: false }});
jQuery("#list2").jqGrid({
   	url:'controller/server/knt/libre_knt.php?org_status=list',
	datatype: "json",
   	colNames:[' ','Id','Имя','Инн','Кпп','Пок','Прод','К.договор','ERPCode','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20,search: false,hidden:true},
   		{name:'id',index:'id', width:55,search: false,hidden:true},
   		{name:'name',index:'name', width:200,editable:true},
                {name:'INN',index:'INN', width:100,editable:true},
                {name:'KPP',index:'KPP', width:100,editable:true},
                {name:'bayer',index:'bayer', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'supplier',index:'supplier', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'dog',index:'dog', width:50,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'ERPCode',index:'ERPCode', width:100,editable:true,search: false,hidden:true},
   		{name:'comment',index:'comment', width:200,editable:true},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false,hidden:true}
   	],
	autowidth: true,		
   	rowNum:20,	
   	rowList:[20,40,60],
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
        viewrecords: true,
        sortorder: "asc",
        editurl:"controller/server/knt/libre_knt.php?org_status=edit",
        caption:"Справочник контрагентов",        
onSelectRow: function(ids) { 
    $('#info_contract').load("controller/server/knt/info_contract.php?kntid="+ids);
}
}).trigger('reloadGrid');
jQuery("#list2").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});