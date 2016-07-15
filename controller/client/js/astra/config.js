/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */


GetGrid();

function GetSubGrid(astra_id){
var addOptions={
    top: 0, left: 0, width: 500
};    
jQuery("#list3").jqGrid({
	height: 100,
	autowidth: true,				
   	url:'controller/server/astra/get_page_mon.php?astra_id='+astra_id,
        editurl:'controller/server/astra/get_page_mon.php?astra_id='+astra_id,
	datatype: "json",
   	colNames:['Id', 'Имя', 'Тип', 'URL','Действия'],        
   	colModel:[
   		{name:'id',index:'id', width:55},
                {name:'name',index:'name', width:55,editable:true},
                {name:'type',index:'type', width:55,editable:true,
                    edittype: "select",
                         editoptions: {
                             value: "Мониторинг:Мониторинг;Логи:Логи"
                         }
                },
                {name:'url',index:'url', width:55,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
	onSelectRow: function(ids) {
                    $("#list5").css('visibility','visible');
                    //$("#uploadButton").css('visibility','visible');
                    $("#simple-btn").css('visibility','visible');                    
                    $('#simple-btn').fileapi('data', {'contractid':ids+100000});
                    //$("#loadfiles").html('<div id="uploadButton" class="button">Загрузить</div>');
                    jQuery("#list5").jqGrid('setGridParam',{url:"controller/server/knt/getfilescontrakts.php?idcontract="+(ids+100000)});
                    jQuery("#list5").jqGrid('setGridParam',{editurl:"controller/server/knt/getfilescontrakts.php?idcontract="+(ids+100000)});
                    jQuery("#list5").jqGrid({
                        url:'controller/server/knt/getfilescontrakts.php?idcontract='+(ids+100000),
                        datatype: "json",
                        colNames:['Id','Имя файла','Действия'],
                        colModel:[
                            {name:'id',index:'id', width:55,hidden:true},
                            {name:'filename',index:'filename', width:100},
                            {name:'myac',  width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
        	autowidth: true,		                
                height:100,
                pager: '#pager5',
                sortname: 'id',
                scroll:1,
                viewrecords: true,
                sortorder: "asc",
                editurl:'controller/server/knt/getfilescontrakts.php?idcontract='+(ids+100000),
                caption:"Прикрепленные файлы"
                }).trigger('reloadGrid');	
                jQuery("#list5").jqGrid('navGrid','#pager5',{edit:false,add:false,del:false,search:false});
	    
	},
   	rowNum:5,   	
   	pager: '#pager3',
   	sortname: 'id',
	//scroll:1,
	viewrecords: true,
	sortorder: "asc",        
	caption:"Ссылки на страницы мониторинга"
        
}).navGrid('#pager3',{add:true,edit:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});
jQuery("#list4").jqGrid({
	height: 100,
	autowidth: true,				
   	url:'controller/server/astra/get_chan_mon.php?astra_id='+astra_id,
        editurl:'controller/server/astra/get_chan_mon.php?astra_id='+astra_id,
	datatype: "json",
   	colNames:['Id', 'chanel_id','Имя','group_id','Действия'],        
   	colModel:[
   		{name:'id',index:'id', width:55},
                {name:'chanel_id',index:'chanel_id', width:55,editable:true},
                {name:'name',index:'name', width:55,editable:true},
                {name:'group_id',index:'group_id', width:55,editable:true},
		{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}   		
   	],
   	rowNum:5,   	
   	pager: '#pager4',
   	sortname: 'id',
	//scroll:1,
	viewrecords: true,
	sortorder: "asc",        
	caption:"Список каналов сервера"
        
}).navGrid('#pager4',{add:true,edit:false,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true});

};
function GetGrid(){
jQuery("#list2").jqGrid({
   	url:'controller/server/astra/config.php?orgid='+defaultorgid,
	datatype: "json",
   	colNames:['Id','Имя сервера','IP','Комментарий','Путь','FTP логин','FTP pass','Мониторинг','Действия'],
   	colModel:[   		
   		{name:'id',index:'id', width:55},
   		{name:'sname',index:'sname', width:200,editable:true},
                {name:'host',index:'host', width:200,editable:true},
                {name:'comment',index:'comment', width:200,editable:true},
                {name:'path',index:'path', width:100,editable:true},
                {name:'ftplogin',index:'ftplogin', width:100,editable:true},
                {name:'ftppass',index:'ftppass', width:100,editable:true},
                {name:'monurl',index:'monurl', width:100,editable:true},
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
	onSelectRow: function(ids) {                                       
				$("#list5").css('visibility','hidden');
				$("#simple-btn").css('visibility','hidden');	    
                                GetSubGrid(ids);
                                jQuery("#list3").jqGrid('setGridParam',{url:"controller/server/astra/get_page_mon.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
                                jQuery("#list3").jqGrid('setGridParam',{editurl:"controller/server/astra/get_page_mon.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
                                jQuery("#list4").jqGrid('setGridParam',{url:"controller/server/astra/get_chan_mon.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
                                jQuery("#list4").jqGrid('setGridParam',{editurl:"controller/server/astra/get_chan_mon.php?astra_id="+ids+"&orgid="+defaultorgid}).trigger('reloadGrid');				
                                

	},           
    editurl:"controller/server/astra/config.php?orgid="+defaultorgid,
    caption:"Серверы Astra"  
});

var addOptions={
    top: 0, left: 0, width: 500
};
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<i class=\"fa fa-comments\" aria-hidden=\"true\"></i> Загрузить каналы ",
     title: "Загрузить список каналов с астры..",
    onClickButton:function(){
     var id = jQuery("#list2").jqGrid('getGridParam','selrow');
	if (id)	{
                 var id = jQuery("#list2").jqGrid('getGridParam','selrow');
                 $.get('controller/server/astra/loadkanallist.php?astra_id='+id, function( data ) {
                     if (data!=""){alert(data);};
                  jQuery("#list2").jqGrid().trigger('reloadGrid');                     
                });

       } else {alert("Выберите Астру!!");};                                    
}}); 

$('#simple-btn').fileapi({
                        url: 'controller/server/common/uploadanyfiles.php',
                        data: {'geteqid':0},
                        multiple: true,
                        maxSize: 20 * FileAPI.MB,
                        autoUpload: true,
                         onFileComplete: function (evt, uiEvt){                                                              
                             if (uiEvt.result.msg!="error") {
                                 jQuery("#list5").jqGrid().trigger('reloadGrid');
                             } else {alert("Ошибка загрузки файла!");};                             
                          },                         
                          elements: {
                                size: '.js-size',
                                active: { show: '.js-upload', hide: '.js-browse' },
                                progress: '.js-progress'
                            }
});   