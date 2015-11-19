$("#orgs").change(function(){
	var exdate=new Date();
        exdate.setDate(exdate.getDate() + 365);        
        orgid=$("#orgs :selected").val();
        defaultorgid=orgid;
        document.cookie="defaultorgid="+orgid+"; path=/; expires="+exdate.toUTCString();
        $('#tbl_equpment').jqGrid('GridUnload');
        LoadTable();
});

//jQuery.extend(jQuery.jgrid.defaults, {ajaxSelectOptions: { cache: false }});

function LoadTable()
{
 var lastsel3;
 jQuery("#tbl_equpment").jqGrid({
   	url:'controller/server/equipment/equipment.php?sorgider='+defaultorgid,
	datatype: "json",
   	colNames:[' ','Id','Помещение','Номенклатура','Группа','В пути','Производитель','Имя по бухгалтерии',
                  'Сер.№','Инв.№','Штрихкод','Организация','Мат.отв.','Оприходовано',
                  'Стоимость','Тек. стоимость','ОС','Списано','Карта','Комментарий','Ремонт','Гар.срок','Поставщик',''],
   	colModel:[
   		{name:'active',index:'active', width:20, search: false,frozen : true},
   		{name:'equipment.id',index:'equipment.id', width:55, search: false,frozen : true,hidden:true},
                {name:'placesid',index:'placesid', width:155,stype:'select',frozen : true,                
                     searchoptions:{dataUrl: 'controller/server/equipment/getlistplaces.php?addnone=true'}},                
                {name:'nomename',index:'getvendorandgroup.nomename', width:155,frozen : true},                
                {name:'getvendorandgroup.groupname',index:'getvendorandgroup.grnomeid', width:100,stype:'select',                
                     searchoptions:{dataUrl: 'controller/server/equipment/getlistgroupname.php?addnone=true'}},
                {name:'tmcgo',index:'tmcgo', width:80,search: true,stype:'select',                
                     searchoptions:{dataUrl: 'controller/server/equipment/getlisttmcgo.php?addnone=true'},
                     formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},editable:true
                 },                
                {name:'getvendorandgroup.vendorname',index:'getvendorandgroup.vendorname', width:60},
                {name:'buhname',index:'buhname', width:155,editable:true},
                {name:'sernum',index:'sernum', width:100,editable:true},
                {name:'invnum',index:'invnum', width:100,editable:true},
                {name:'shtrihkod',index:'shtrihkod', width:100,editable:true},
                {name:'org.name',index:'org.name', width:155,hidden:true},
                {name:'users.login',index:'users.login', width:100},                
                {name:'datepost',index:'datepost', width:80},
                {name:'cost',index:'cost', width:55,editable:true,hidden:true},
                {name:'currentcost',index:'currentcost', width:55,editable:true,hidden:true},
                {name:'os',index:'os', width:35,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'mode',index:'equipment.mode', width:55,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'eqmapyet',index:'eqmapyet', width:55,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false,hidden:true},                
   		{name:'comment',index:'equipment.comment', width:200,editable:true,edittype:"textarea", editoptions:{rows:"3",cols:"10"},search: false},
                {name:'eqrepair',hidden:true,index:'eqrepair',width:35,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: 'Yes:No'},search: false},
                {name:'dtendgar',index:'dtendgar', width:55,editable:false,hidden:true,search: false},
                {name:'kntname',index:'kntname', width:55,editable:false,hidden:true,search: false},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}
   	],
        onSelectRow: function(ids) {
               // $( "#infobox" ).draggable();
                $("#photoid").load("controller/server/equipment/getphoto.php?eqid="+ids);
                //$("#moveid").load("controller/server/getmoveinfo.php?eqid="+ids);
                jQuery("#tbl_move").jqGrid('setGridParam',{url:"controller/server/equipment/getmoveinfo.php?eqid="+ids});
                jQuery("#tbl_move").jqGrid({
                     url:'controller/server/equipment/getmoveinfo.php?eqid='+ids,
                     datatype: "json",
                     colNames:['Id','Дата','Организация','Помещение','Человек','Организация','Помещение','Человек','','Комментарий',''],
                     colModel:[
                     	{name:'id',index:'id', width:25,hidden:true},
                        {name:'dt',index:'dt', width:95},
                     	{name:'orgname1',index:'orgname1', width:120,hidden:true},
                        {name:'place1',index:'place1', width:80},
                        {name:'user1',index:'user1', width:90},
                     	{name:'orgname2',index:'orgname2', width:120,hidden:true},
                        {name:'place2',index:'place2', width:80},
                        {name:'user2',index:'user2', width:90},                        
                        {name:'name',index:'name', width:90,hidden:true},                        
                     	{name:'comment',index:'comment', width:200,editable:true},
                     	{name: 'myac', width:60, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
                     ],
                     autowidth: true,	
                     pager: '#mv_nav',
                     sortname: 'dt',
                     scroll:1,
                     shrinkToFit: true,        
                     viewrecords: true,
                     height: 200,
                     sortorder: "desc",
                     editurl:"controller/server/equipment/getmoveinfo.php?eqid="+ids,
                     caption:"История перемещений"
                     }).trigger('reloadGrid');	
              jQuery("#tbl_move").jqGrid('destroyGroupHeader');
              jQuery("#tbl_move").jqGrid('setGroupHeaders', {
              useColSpanStyle: true, 
              groupHeaders:[
              	{startColumnName: 'orgname1', numberOfColumns: 3, titleText: 'Откуда'},
              	{startColumnName: 'orgname2', numberOfColumns: 3, titleText: 'Куда'}
                ]	
              });              
                $('#tbl_rep').jqGrid('GridUnload');
                jQuery("#tbl_rep").jqGrid('setGridParam',{url:"controller/server/equipment/getrepinfo.php?eqid="+ids});              
                jQuery("#tbl_rep").jqGrid({
                     url:'controller/server/equipment/getrepinfo.php?eqid='+ids,
                     datatype: "json",
                     colNames:['Id','Дата начала','Дата окончания','Организация','Стоимость','Комментарий','Статус',''],
                     colModel:[
                     	{name:'id',index:'id', width:25,editable:false},
                        {name:'dt',index:'dt', width:95,editable:true,sorttype:"date",editoptions:{size:20, 
                                dataInit:function(el){ 
                                    vl=$(el).val();
                                    $(el).datepicker(); 
                                    $(el).datepicker("option", "dateFormat", "dd.mm.yy"); 
                                    $(el).datepicker( "setDate" , vl);                                    
                                }}
                        },
                        {name:'dtend',index:'dtend', width:95,editable:true,editoptions:{size:20, 
                                dataInit:function(el){ 
                                    vl=$(el).val();
                                    $(el).datepicker(); 
                                    $(el).datepicker("option", "dateFormat", "dd.mm.yy"); 
                                    $(el).datepicker( "setDate" , vl);                                    
                                }}
                        },
                     	{name:'kntname',index:'kntname', width:120},
                        {name:'cost',index:'cost', width:80,editable:true,editoptions:{size:20, 
                                dataInit:function(el){ 
                                    $(el).focus();
                                }}
                        },
                     	{name:'comment',index:'comment', width:200,editable:true},
                        {name:'status',index:'status', width:80,editable:true,edittype:"select",editoptions:{value:"1:Ремонт;0:Сделано"}},
                     	{name: 'myac', width:60, fixed:true, sortable:false, resize:false, formatter:'actions',
                            formatoptions:{keys:true,
                                            afterSave:function(rowid){
                                               jQuery("#tbl_equpment").jqGrid().trigger('reloadGrid');                                                
                                            }
                                          }}
                     ],
//                        onSelectRow: function(id){
//                        if(id && id!==lastsel3){                            
//                            jQuery('#tbl_rep').jqGrid('restoreRow',lastsel3);
//                            pickdates;
//                            //jQuery('#tbl_rep').jqGrid('editRow',id,true,pickdates);
//                            lastsel3=id;
//                                function pickdates(id){
//                                    //alert(id);
//                                    $("#"+id+"_cost","#tbl_rep").focus();
//                                    vl1=$("#"+id+"_dt","#tbl_rep").val();
//                                    jQuery("#"+id+"_dt","#tbl_rep").datepicker();
//                                    jQuery("#"+id+"_dt","#tbl_rep").datepicker( "option", "dateFormat", "dd.mm.yy");
//                                    $("#"+id+"_dt","#tbl_rep").datepicker( "setDate" , vl1);
//                                    vl2=$("#"+id+"_dtend","#tbl_rep").val();
//                                    jQuery("#"+id+"_dtend","#tbl_rep").datepicker();
//                                    jQuery("#"+id+"_dtend","#tbl_rep").datepicker( "option", "dateFormat", "dd.mm.yy");
//                                    $("#"+id+"_dtend","#tbl_rep").datepicker( "setDate" , vl2);
//                                };                            
//                        }},         
                     autowidth: true,	
                     pager: '#rp_nav',
                     sortname: 'dt',
                     scroll:1,
                     shrinkToFit: true,        
                     viewrecords: true,
                     height: 200,
                     sortorder: "desc",
                     editurl:"controller/server/equipment/getrepinfo.php?eqid="+ids,
                     caption:"История ремонтов"
                     }).trigger('reloadGrid');       
                        jQuery("#tbl_rep").jqGrid('navGrid','#rp_nav',{edit:false,add:false,del:false,search:false});                     
                        jQuery("#tbl_rep").jqGrid('navButtonAdd','#rp_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_error.png'>",                              
                        title: "Отдать в ремонт ТМЦ",
                            onClickButton:function(){
                                var id = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
                                    if (id)	{ // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
                                        var ret = jQuery("#tbl_equpment").jqGrid('getRowData',id);
                                                $("#pg_add_edit").dialog({autoOpen: false,height: 380,width: 620,modal:true,title: "Ремонт имущества" });
                                                $("#pg_add_edit" ).dialog( "open" );                                   
                                                $("#pg_add_edit").load("controller/client/view/equipment/repair.php?step=add&eqid="+id);                    
                                    } else { alert("Выберите ТМЦ для ремонта!");}	
                                    } 
                        });                     
              
	},
        subGridRowExpanded: function(subgrid_id, row_id) {
		// we pass two parameters
		// subgrid_id is a id of the div tag created whitin a table data
		// the id of this elemenet is a combination of the "sg_" + id of the row
		// the row_id is the id of the row
		// If we wan to pass additinal parameters to the url we can use
		// a method getRowData(row_id) - which returns associative array in type name-value
		// here we can easy construct the flowing                
		var subgrid_table_id, pager_id;
		subgrid_table_id = subgrid_id+"_t";
		pager_id = "p_"+subgrid_table_id;
		$("#"+subgrid_id).html("<table border=1 id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
                //alert(subgrid_id+"!"+subgrid_table_id+"!"+pager_id);
		jQuery("#"+subgrid_table_id).jqGrid({
			url:"controller/server/equipment/paramlist.php?eqid="+row_id,
			datatype: "json",
			colNames: ['Id','Наименование','Параметр',''],
			colModel: [
				{name:"id",index:"num",width:80,key:true},
				{name:"name",index:"item",width:130},
				{name:"param",index:"qty",width:130,editable:true},
                                {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
			],
                        editurl:"controller/server/equipment/paramlist.php?eqid="+row_id,
		   	pager: pager_id,
		   	sortname: 'name',
		    sortorder: "asc",
                    scroll:1,
		    height: 'auto'
		});
		//jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:true,add:false,del:false,search:false})
	},
	subGridRowColapsed: function(subgrid_id, row_id) {
		// this function is called before removing the data
		var subgrid_table_id;
		subgrid_table_id = subgrid_id+"_t";
		jQuery("#"+subgrid_table_id).remove();
	},        
    subGrid: true,
    multiselect: true,
    height: 'auto',
    autowidth : true,
    shrinkToFit: false,                
    pager: '#pg_nav',
    sortname: 'equipment.id',
    rowNum:20,
    //loadonce: true,
    //scroll:1,
    viewrecords: true,
    sortorder: "asc",
    editurl:"controller/server/equipment/equipment.php?sorgider="+defaultorgid,
    caption:"Оргтехника"
});

jQuery("#tbl_equpment").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#tbl_equpment").jqGrid('bindKeys',''); 
jQuery("#tbl_equpment").jqGrid('navGrid','#pg_nav',{edit:false,add:false,del:false,search:false});
jQuery("#tbl_equpment").jqGrid('setFrozenColumns');

jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{
    caption: "<img src='controller/client/themes/"+theme+"/ico/tag.png'>",
    title: "Выбор колонок",
    onClickButton : function (){
        jQuery("#tbl_equpment").jqGrid('columnChooser');
    }
});
jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_add.png'>",                              
        title: "Добавить ТМЦ",
	onClickButton:function(){
            //$("#pg_add_edit" ).dialog( "destroy" );
            $("#pg_add_edit").dialog({autoOpen: false,height: 700,width: 780,modal:true,title: "Добавление имущества" });
            $("#pg_add_edit" ).dialog( "open" );
            $("#pg_add_edit").load("controller/client/view/equipment/equipment.php?step=add&id=");					
	} 
});
jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_edit.png'>",
        title: "Редактировать ТМЦ",
	onClickButton:function(){
		var gsr = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
		if(gsr){
                          //$("#pg_add_edit" ).dialog( "destroy" );
                          $("#pg_add_edit").dialog({autoOpen: false,height: 700,width: 780,modal:true,title: "Редактирование имущества" });
                          $("#pg_add_edit" ).dialog( "open" );                     
			  $("#pg_add_edit").load("controller/client/view/equipment/equipment.php?step=edit&id="+gsr);
		} else {
			alert("Сначала выберите строку!")
		}							
	} 
});
jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_go.png'>",                              
        title: "Переместить ТМЦ",
	onClickButton:function(){
              var gsr = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
		if(gsr){                                            
                       $("#pg_add_edit").dialog({autoOpen: false,height: 440,width: 620,modal:true,title: "Перемещение имущества" });
                       $("#pg_add_edit" ).dialog( "open" );                                   
                       $("#pg_add_edit").load("controller/client/view/equipment/move.php?step=move&id="+gsr);
                       } else {
			alert("Сначала выберите строку!")
		}							
	} 	
});
jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_error.png'>",                              
        title: "Отдать в ремонт ТМЦ",
	onClickButton:function(){
        var id = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
	if (id)	{ // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
		var ret = jQuery("#tbl_equpment").jqGrid('getRowData',id);
//                if (ret.eqrepair=="Yes") {
//                    $("#pg_add_edit").dialog({autoOpen: false,height: 400,width: 620,modal:true,title: "Ремонты ТМЦ" });
//                    $("#pg_add_edit" ).dialog( "open" );                         
//                    var gsr = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
//                    $("#pg_add_edit").load("controller/client/view/equipment/repairlist.php?step=list&id="+gsr);                            
//                } else { // иначе открываем диалог добавления ТМЦ в Ремонт
                       $("#pg_add_edit").dialog({autoOpen: false,height: 380,width: 620,modal:true,title: "Ремонт имущества" });
                       $("#pg_add_edit" ).dialog( "open" );                                   
                       $("#pg_add_edit").load("controller/client/view/equipment/repair.php?step=add&eqid="+id);                    
                //};
	} else { // если нажата кнопка "ремонт" но не выбрана строка, то отображаем полный список ремонтов
//                $("#pg_add_edit").dialog({autoOpen: false,height: 400,width: 620,modal:true,title: "Ремонты ТМЦ" });
//                $("#pg_add_edit" ).dialog( "open" );                                   
//                $("#pg_add_edit").load("controller/client/view/equipment/repairlist.php?step=list");        
                alert("Сначала выберите строку!");
        }	
    } 
});

jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/table.png'>",                              
       title: "Вывести штрихкоды ТМЦ",
	onClickButton:function(){
              var gsr = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
		if(gsr){
                   //var id = jQuery("#tbl_equpment").jqGrid('getGridParam','selrow');
                   //var ret = jQuery("#tbl_equpment").jqGrid('getRowData',id);
                   //alert("id="+ret.id+" invdate="+ret.invdate+"...");
                var s;
                  s = jQuery("#tbl_equpment").jqGrid('getGridParam','selarrrow');
                  newWin=window.open('inc/ean13print.php?mass='+s,'printWindow'); 
                       } else {
			alert("Сначала выберите строку!")
		}							
	} 	
});

jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/report.png'>",                              
        title: "Отчеты",
	onClickButton:function(){
                  newWin2=window.open('?content_page=report_tmc','printWindow2'); 
		}								 	
});

jQuery("#tbl_equpment").jqGrid('navButtonAdd','#pg_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/disk.png'>",                              
        title: "Экспорт XML",
	onClickButton:function(){
                  newWin2=window.open('controller/server/equipment/export_xml.php','printWindow4'); 
		}								 	
});

jQuery("#tbl_equpment").jqGrid('setFrozenColumns');

};
LoadTable();

    function GetListUsers(orgid,userid){
     $("#susers").load("controller/server/getlistusers.php?orgid="+orgid+"&userid="+userid);
    };
    function GetListPlaces(orgid,placesid){
       url="controller/server/getlistplaces.php?orgid="+orgid+"&placesid="+placesid;
       $("#splaces").load(url);
    };

//    $("#orgs").click(function(){       
//      $("#splaces").html="идет загрузка.."; // заглушка. Зачем?? каналы счас быстрые
//      $("#susers").html="идет загрузка..";
//      GetListPlaces($("#orgs :selected").val(),''); // перегружаем список помещений организации
//      GetListUsers($("#orgs :selected").val(),'') // перегружаем пользователей организации
//    });

//$("#orgs").change(function(){
//        //jQuery("#tbl_equpment").jqGrid('setRowData',0,{buhname:"sss"});
//        //jQuery("#tbl_equpment").html="";
//        jQuery("#tbl_equpment").GridUnload("#tbl_equpment");
//        LoadTable();
//        //jQuery("#tbl_equpment").jqGrid('setGridParam',{url:'controller/server/equipment.php?sorgider='+$("#orgs :selected").val()}).trigger('reloadGrid');        
//	//alert("s");
//       });

