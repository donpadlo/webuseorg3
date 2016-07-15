function LoadTable()
{
 jQuery("#bp_xml").jqGrid({
   	url:'controller/server/bp/bp_xml.php?createdid='+defaultuserid,
	datatype: "json",
   	colNames:['Id','Дата','Заголовок','Статус','Создатель','Узел','Схема',''],
   	colModel:[
               {name:'id',index:'id',width:15,search: false},
               {name:'dt',index:'id',width:40,search: false},
               {name:'title',index:'id',width:255,search: false},
               {name:'status',index:'id',width:55,search: false},               
               {name:'userid',index:'userid',width:55,search: true},               
               {name:'node',index:'node',width:55,search: false},               
               {name:'xml',index:'xml',width:55,search: false},               
               {name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
        ],
                onSelectRow: function(ids) {
                    $("#bp_info").css('visibility','visible');
                    tappet=ids;                    
                    $('#myTab li:eq(0) a').tab('show');
                    $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                            
                    $("#bp_info_view").load("controller/client/view/bp/bp_info.php?bpid="+tappet);                    
                },
                     autowidth: true,	
                     pager: '#bp_xml_footer',
                     sortname: 'dt',
                     scroll:1,
                     shrinkToFit: true,        
                     viewrecords: true,
                     height: 200,
                     sortorder: "desc",
                     editurl:'controller/server/bp/bp_xml.php?createdid='+defaultuserid,
                     caption:"БП Согласование по схеме"
                     });	
                     
    jQuery("#bp_xml").jqGrid('navGrid','#bp_xml_footer',{edit:false,add:false,del:false,search:false});                     
                     
    jQuery("#bp_xml").jqGrid('navButtonAdd','#bp_xml_footer',{caption:"<i class=\"fa fa-plus-circle\" aria-hidden=\"true\"></i>",                              
        title: "Добавить бизнесс-процесс",        
	onClickButton:function(){
            $("#bp_xml_info").dialog({autoOpen: false,height: 480,width: 640,modal:true,title: "Добавление/редактирование БП" });
            $("#bp_xml_info" ).dialog( "open" );            
            $("#bp_xml_info").load("controller/client/view/bp/bp_xml_form.php?mode=add");            
	} 
    });                     
    jQuery("#bp_xml").jqGrid('navButtonAdd','#bp_xml_footer',{caption:"<i class=\"fa fa-pencil-square\" aria-hidden=\"true\"></i>",                              
        title: "Редактировать бизнесс-процесс",        
	onClickButton:function(){
		var gsr = jQuery("#bp_xml").jqGrid('getGridParam','selrow');
		if(gsr){            
                    $("#bp_xml_info").dialog({autoOpen: false,height: 480,width: 640,modal:true,title: "Добавление/редактирование БП" });
                    $("#bp_xml_info" ).dialog( "open" );            
                    $("#bp_xml_info").load("controller/client/view/bp/bp_xml_form.php?mode=edit&bpid="+gsr);            
                } else {alert("Сначала выберите строку!");};
	} 
    });                     
    jQuery("#bp_xml").jqGrid('navButtonAdd','#bp_xml_footer',{caption:"<i class=\"fa fa-codepen\" aria-hidden=\"true\"></i>",                              
        title: "Снять фильтр",        
	onClickButton:function(){
           jQuery("#bp_xml"). setGridParam({url:'controller/server/bp/bp_xml.php?createdid='});
           jQuery("#bp_xml"). setGridParam({editurl:'controller/server/bp/bp_xml.php?createdid='}).trigger("reloadGrid");
	} 
    });                     
    jQuery("#bp_xml").jqGrid('navButtonAdd','#bp_xml_footer',{caption:"<i class=\"fa fa-codepen\" aria-hidden=\"true\"></i>",                              
        title: "Применить фильтр",        
	onClickButton:function(){
           jQuery("#bp_xml"). setGridParam({url:'controller/server/bp/bp_xml.php?createdid='+defaultuserid});
           jQuery("#bp_xml"). setGridParam({editurl:'controller/server/bp/bp_xml.php?createdid='+defaultuserid}).trigger("reloadGrid");
	} 
    });                     
    
 };   
var tappet=1;
LoadTable();  
$('#myTab li:eq(0) a').tab('show');
$('#myTab li:eq(0) a').click(function (e) {
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/bp_info.php?bpid="+tappet);
})
$('#myTab li:eq(1) a').click(function (e) {
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/bp_stepbystep.php?bpid="+tappet);
})
$('#myTab li:eq(2) a').click(function (e) {
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/bp_sx_view.php?bpid="+tappet);
})