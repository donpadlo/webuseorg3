  GetGrid();
  GetSubGrid();

function GetGrid()
{
jQuery("#list2").jqGrid({
   	url:'controller/server/devicescontrol/listgroups.php?orgid='+defaultorgid,
	datatype: "json",
   	colNames:['Id','Наименование группы','Комментарий','Действия'],
   	colModel:[   		
   		{name:'id',index:'id', width:55},
   		{name:'dgname',index:'dgname', width:200,editable:true},
   		{name:'dcomment',index:'dcomment',width:200, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
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
    editurl:"controller/server/devicescontrol/listgroups.php?orgid="+defaultorgid,
    caption:"Группы устройств",
	onSelectRow: function(ids) {
                GetSubGrid();
		if(ids == null) {
			ids=0;
			if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 )
			{
				jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/devicescontrol/listdevices.php?dgid="+ids+"&orgid="+defaultorgid});
				jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/devicescontrol/listdevices.php?dgid="+ids+"&orgid="+defaultorgid})
				.trigger('reloadGrid');				
			}
		} else {			
			jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/devicescontrol/listdevices.php?dgid="+ids+"&orgid="+defaultorgid});
			jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/devicescontrol/listdevices.php?dgid="+ids+"&orgid="+defaultorgid})
			.trigger('reloadGrid');			
		}
	}    
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
function GetSubGrid()
{
var addOptions={
    top: 0, left: 0, width: 500
};    
jQuery("#list10_d").jqGrid({
	height: 100,
	autowidth: true,				
   	url:'controller/server/devicescontrol/listdevices.php',
	datatype: "json",
   	colNames:['Id', 'Устройство', 'Скрипт для выполнения','Цвет кнопки', 'Действия'],        
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'dname',index:'dname', width:200,editable:true},
                {name:'command',width:200, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
                {name:'bcolor',index:'bcolor', width:200,editable:true,
                    edittype: "select",
                    editoptions: {
                             value: "btn-primary:btn-primary;btn-info:btn-info;btn-success:btn-success;btn-warning:btn-warning;btn-danger:btn-danger;btn-inverse:btn-inverse"
                         }
                },
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
   	rowNum:5,   	
   	pager: '#pager10_d',
   	sortname: 'devid',
	scroll:1,
	viewrecords: true,
	sortorder: "asc",        
	caption:"Список устройств"
}).navGrid('#pager10_d',{add:true,edit:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});
};
