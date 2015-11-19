  GetGrid();
  GetSubGrid();

function GetGrid(){
jQuery("#list2").jqGrid({
   	url:'controller/server/places/libre_place.php?orgid='+defaultorgid,
	datatype: "json",
   	colNames:[' ','Id','Подразделение','Наименование','Комментарий','Действия'],
   	colModel:[
   		{name:'active',index:'active', width:20},
   		{name:'id',index:'id', width:55},
                {name:'opgroup',index:'opgroup', width:100,editable:true},
   		{name:'name',index:'name', width:200,editable:true},
   		{name:'comment',index:'comment', width:200,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
                grouping: true,
                groupingView: {
                    groupField: ["opgroup"],
                    groupColumnShow: [true],
                    groupText: ["<b>{0}</b>"],
                    groupOrder: ["asc"],
                    groupSummary: [false],
                    groupCollapse: false
                    
                },        
	autowidth: true,			
   	rowNum:10,	   	
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
	height: 140,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/places/libre_place.php?orgid="+defaultorgid,
    caption:"Помещения",
	onSelectRow: function(ids) {
                GetSubGrid();
		if(ids == null) {
			ids=0;
			if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 ){
				jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/places/libre_place_sub.php?placesid="+ids+"&orgid="+defaultorgid});
				jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/places/libre_place_sub.php?placesid="+ids+"&orgid="+defaultorgid})
				.trigger('reloadGrid');				
			}
		} else {			
			jQuery("#list10_d").jqGrid('setGridParam',{url:"controller/server/places/libre_place_sub.php?placesid="+ids+"&orgid="+defaultorgid});
			jQuery("#list10_d").jqGrid('setGridParam',{editurl:"controller/server/places/libre_place_sub.php?placesid="+ids+"&orgid="+defaultorgid})
			.trigger('reloadGrid');			
		}
	}    
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
function GetSubGrid(){
var addOptions={
    top: 0, left: 0, width: 500
};    
jQuery("#list10_d").jqGrid({
	height: 100,
	autowidth: true,				
   	url:'controller/server/places/libre_place_sub.php',
	datatype: "json",
   	colNames:['Id', 'Человек', 'Действия'],        
   	colModel:[
   		{name:'places_users.id',index:'places_users.id', width:55},
   		{name:'name',index:'name', width:200,editable:true,edittype:"select",editoptions:{
                    editrules: { required: true },
                    dataUrl: 'controller/server/common/getlistusers.php?orgid='+defaultorgid
                    }},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
   	rowNum:5,   	
   	pager: '#pager10_d',
   	sortname: 'places_users.id',
	scroll:1,
	viewrecords: true,
	sortorder: "asc",        
	caption:"Кто здесь сидит"
}).navGrid('#pager10_d',{add:true,edit:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});
};
