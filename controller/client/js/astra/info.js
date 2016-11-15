/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

function getRandomInt(min, max) {

return Math.floor(Math.random()*(max + 1 - min)) + min;

}

function GetSubGrid(astra_id){
var addOptions={
    top: 0, left: 0, width: 500
};    
jQuery("#frames").jqGrid({
	height: 100,
	autowidth: true,				
   	url:'controller/server/astra/get_page_select.php?astra_id='+astra_id+"&rand"+getRandomInt(1,200),
        editurl:'controller/server/astra/get_page_select.php?astra_id='+astra_id+"&rand"+getRandomInt(1,200),
	datatype: "json",
   	colNames:['Id', 'Действия'],        
   	colModel:[
   		{name:'id',index:'id', width:55},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
   	rowNum:5,   	
   	pager: '#pager_frames',
   	sortname: 'id',
	scroll:1,
	viewrecords: true,
	sortorder: "asc",        
	caption:"Страницы",
        onSelectRow: function(ids) {
          $("#frames_info").load('controller/client/view/astra/get_frame.php?frame_id='+ids+"&rand"+getRandomInt(1,200));  
        }
        
}).navGrid('#pager_frames',{add:true,edit:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});
};


function GetGrid(){
jQuery("#list2").jqGrid({
   	url:'controller/server/astra/config.php?orgid='+defaultorgid+"&rand"+getRandomInt(1,200),
	datatype: "json",
   	colNames:['Id','Имя сервера'],
   	colModel:[   		
   		{name:'id',index:'id', width:55,hidden:false},
   		{name:'sname',index:'sname', width:200,editable:true}
   	],
	autowidth: true,			
   	rowNum:10,	   	
   	pager: '#pager2',
   	sortname: 'id',
	scroll:1,
	autoheight: true,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/astra/config.php?orgid="+defaultorgid+"&rand"+getRandomInt(1,200),
    caption:"Серверы Astra" ,
	onSelectRow: function(ids) {       
                                $("#frames_info").html("");
                                $("#pl").html("<img class='img-responsive img-thumbnail' src=controller/server/astra/pic.php?astra_id="+ids+"&r="+getRandomInt(0,100)+" >");
                                GetSubGrid(ids);
				jQuery("#frames").jqGrid('setGridParam',{url:"controller/server/astra/get_page_select.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
				jQuery("#frames").jqGrid('setGridParam',{editurl:"controller/server/astra/get_page_select.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
	}        
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );

    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-television\" aria-hidden=\"true\"></i>",                              
        title: "Собрать ролик",
	buttonicon: 'none',
	onClickButton:function(){
            var id = jQuery("#list2").jqGrid('getGridParam','selrow');
	if (id)	{
            $("#console").html("<img src=controller/client/themes/"+theme+"/img/loading.gif>");        
            $("#console").load("controller/server/astra/creatempg.php?astra_id="+id+"&q="+$("#kkode").val()+"&rand"+getRandomInt(1,200));          
       } else {alert("Выберите астру!");};
	} 
});    
    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-play\" aria-hidden=\"true\"></i>",                              
        title: "Опубликовать инфоканал",
	buttonicon: 'none',
	onClickButton:function(){
            var id = jQuery("#list2").jqGrid('getGridParam','selrow');
	if (id)	{
            $("#console").html("<img src=controller/client/themes/"+theme+"/img/loading.gif>");        
            $("#console").load("controller/server/astra/moveftp.php?astra_id="+id+"&rand"+getRandomInt(1,200));          
       } else {alert("Выберите астру!");};
	} 
});    
};


GetGrid();
