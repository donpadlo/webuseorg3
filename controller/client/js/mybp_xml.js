function LoadTable()
{
 jQuery("#bp_xml").jqGrid({
   	url:'controller/server/bp/bp_xml_list.php?curuserid='+defaultuserid,
	datatype: "json",
   	colNames:['Id','БПId','Дата','Заголовок','Создатель','Время'],
   	colModel:[
               {name:'id',index:'uid',width:15,search: false},
               {name:'bpid',index:'bpid',width:15,search: false},               
               {name:'dt',index:'dtstart',width:40,search: false},
               {name:'title',index:'title',width:255,search: false},               
               {name:'userid',index:'crid',width:55,search: false},
               {name:'time',index:'ctt',width:20,search: false}                              
               
        ],        
                onSelectRow: function(ids) {
                    $("#bp_info").css('visibility','visible');
                    var myGrid = $('#bp_xml'),
                    selRowId = myGrid.jqGrid ('getGridParam', 'selrow');
                    celValue = myGrid.jqGrid ('getCell', selRowId, 'bpid');                    
                    tappet=celValue;                    
                    tappetmybp=ids;                    
                    $('#myTab li:eq(0) a').tab('show');
                    $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                            
                    $("#bp_info_view").load("controller/client/view/bp/mybp_xml_info.php?mybpid="+ids);                    
                },
                     autowidth: true,	
                     pager: '#bp_xml_footer',
                     sortname: 'dt',
                     scroll:1,
                     shrinkToFit: true,        
                     viewrecords: true,
                     height: 200,
                     sortorder: "desc",
                     editurl:"controller/server/bp/bp_xml_list.php",
                     caption:"БП Согласование по схеме"
                     });	
                     
    jQuery("#bp_xml").jqGrid('navGrid','#bp_xml_footer',{edit:false,add:false,del:false,search:false});                     
                                             
 };            
            
LoadTable();        

$('#myTab li:eq(0) a').tab('show');
$('#myTab li:eq(0) a').click(function (e) {    
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/mybp_xml_info.php?mybpid="+tappetmybp);
})
$('#myTab li:eq(1) a').click(function (e) {
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/bp_stepbystep.php?bpid="+tappet);
})
$('#myTab li:eq(2) a').click(function (e) {
  $("#bp_info_view").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>Построение может занять время..");                                
  $("#bp_info_view").load("controller/client/view/bp/bp_sx_view.php?bpid="+tappet);
})