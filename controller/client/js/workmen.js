function exportExcel(list,tmc)
        {
            var mya=new Array();
            mya=$(list).getDataIDs(); 
            var data=$(list).getRowData(mya[0]);  
            var colNames=new Array(); 
            var ii=0;
            for (var i in data){colNames[ii++]=i;}
            var html="";            
            headxls="<?xml version='1.0'?><?mso-application progid='Excel.Sheet'?>"+"\n";
            sworkbook="<Workbook xmlns='urn:schemas-microsoft-com:office:spreadsheet' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns:ss='urn:schemas-microsoft-com:office:spreadsheet' xmlns:html='http://www.w3.org/TR/REC-html40'>"+"\n";
            eworkbook="</Workbook>"+"\n";
            styles="<Styles>"+"\n"+
                    "<Style ss:ID='bbbold'>"+"\n"+
                    "<Font ss:Bold='1' /> "+"\n"+
                    "</Style>"+"\n"+
                    
                    "<Style ss:ID='borderedbold'>"+"\n"+
                    "<Borders>"+"\n"+
                    "	  <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "</Borders>"+"\n"+
                    "<Font ss:Bold='1' /> "+"\n"+
                    "</Style>"+"\n"+
                    "<Style ss:ID='bordered'>"+"\n"+
                    "<Borders>"+"\n"+
                    "	  <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "	  <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1' /> "+"\n"+
                    "</Borders>"+"\n"+                    
                    "</Style>"+"\n"+                    
                    "</Styles> "+"\n";
            sworksheet="<Worksheet ss:Name='WorksheetName'>"+"\n";
            eworksheet="</Worksheet>"+"\n";
            stable="<Table>"+"\n";
            etable="</Table>"+"\n";
            columnw=""+
                    "<Column ss:Index='1' ss:AutoFitWidth='0' ss:Width='60' /> "+"\n"+
                    "<Column ss:Index='2' ss:AutoFitWidth='0' ss:Width='200' /> "+"\n"+
                    "<Column ss:Index='3' ss:AutoFitWidth='0' ss:Width='200' /> "+"\n"+
                    "<Column ss:Index='4' ss:AutoFitWidth='0' ss:Width='330' /> "+"\n"+
                    "<Column ss:Index='5' ss:AutoFitWidth='0' ss:Width='130' /> "+"\n"+
                    "<Column ss:Index='6' ss:AutoFitWidth='0' ss:Width='330' /> "+"\n"+                    
                    "<Column ss:Index='5' ss:AutoFitWidth='0' ss:Width='130' /> "+"\n"+
                    "<Column ss:Index='6' ss:AutoFitWidth='0' ss:Width='230' /> "+"\n"+                                        
                    "<Column ss:Index='7' ss:AutoFitWidth='0' ss:Width='230' /> "+"\n"+                                        
                    "<Column ss:Index='8' ss:AutoFitWidth='0' ss:Width='320' /> "+"\n";
            headcolumn=""+
                    	"<Row>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>№</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Начало</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Конец</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Контрагент</Data></Cell>"+"\n"+
                        "   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Стоимость</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Комментарий</Data></Cell>"+"\n"+
                        "   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Статус</Data></Cell>"+"\n"+
                        "   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Отправитель</Data></Cell>"+"\n"+
                        "   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Получатель</Data></Cell>"+"\n"+
                        "   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Документ</Data></Cell>"+"\n"+
			"</Row> "+"\n";
            
            html=html+headxls+sworkbook+styles+sworksheet+stable+columnw;
            html=html+"<Row><Cell ss:StyleID='bbbold'><Data ss:Type='String'>ТМЦ:"+tmc+"</Data></Cell></Row>"+"\n"+headcolumn;
            for(i=0;i<mya.length;i++)
                {
                data=$(list).getRowData(mya[i]); 
                html=html+"<Row>"+"\n";
                for(j=0;j<colNames.length-1;j++)
                    {
                    html=html+"<Cell ss:StyleID='bordered'><Data ss:Type='String'>"+data[colNames[j]]+"</Data></Cell>"+"\n";                        
                    }
                html=html+"</Row>"+"\n";
                };               
            html=html+etable+eworksheet+eworkbook;
            
            document.forms[0].csvBuffer.value=html;
            document.forms[0].method='POST';
            document.forms[0].action='inc/csvExport.php'; 
            document.forms[0].target='_blank';
            document.forms[0].submit();
        };
jQuery("#workmen").jqGrid({
   	url:'controller/server/tmc/workmen.php',
	datatype: "json",
   	colNames:['Статус','Организация','Помещение','Группа','Id','Инв.№','ТМЦ','Ответственный','За месяц','За год'],
   	colModel:[
   		{name:'repair',index:'repair', width:100,search: false},
                {name:'orgname',index:'orgname', width:155,stype:'select',
                searchoptions:{dataUrl: 'controller/server/common/getlistorgs.php?addnone=true'}},
                {name:'placename',index:'placename', width:150,search: false},
   		{name:'groupnomename',index:'groupnomename', width:150,stype:'select',
                    searchoptions:{dataUrl: route + 'controller/server/equipment/getlistgroupname.php?addnone=true'}},
                {name:'idnome',index:'idnome', width:50},
                {name:'invnum',index:'invnum', width:100},
   		{name:'nomename',index:'nomename', width:200},
   		{name:'fio',index:'fio', width:200,search: false},
                {name:'bymonth',index:'bymonth', width:50,search: false},
                {name:'byear',index:'byear', width:50,search: false}		
   	],
        onSelectRow: function(ids) {        
         $("#photoid").load(route + "controller/server/equipment/getphoto.php?eqid="+ids);         
                //$('#tbl_rep').jqGrid('GridUnload');
                $.jgrid.gridUnload("#tbl_rep");
                jQuery("#tbl_rep").jqGrid('setGridParam',{url:"controller/server/equipment/getrepinfo.php?eqid="+ids});              
                jQuery("#tbl_rep").jqGrid({
                     url:'controller/server/equipment/getrepinfo.php?eqid='+ids,
                     datatype: "json",
                     colNames:['Id','Дата начала','Дата окончания','Организация','Стоимость','Комментарий','Статус','Отправитель','Получатель','Документ',''],
                     colModel:[
                     	{name:'id',index:'id', width:25,editable:false,hidden:true},
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
                        {name:'status',index:'status', width:80,editable:true,edittype:"select",editoptions:{value:"1:В сервисе;0:Работает;2:Есть заявка;3:Списать"}},
                        {name:'userfrom',index:'userfrom', width:200},
                        {name:'userto',index:'userto', width:200},
                        {name:'doc',index:'doc', width:200,editable:true},
                     	{name: 'myac', width:60, fixed:true, sortable:false, resize:false, formatter:'actions',
                            formatoptions:{keys:true,
                                            afterSave:function(rowid){
                                               jQuery("#workmen").jqGrid().trigger('reloadGrid');                                                
                                            }
                                          }}
                     ],
                     autowidth: true,	
                     pager: '#rp_nav',
                     sortname: 'id',
                     scroll:1,
                     shrinkToFit: true,        
                     viewrecords: true,
                     height: 200,
                     sortorder: "desc",
                     editurl:"controller/server/equipment/getrepinfo.php?eqid="+ids,
                     caption:"История ремонтов"
                     }).trigger('reloadGrid');       
                        jQuery("#tbl_rep").jqGrid('navGrid','#rp_nav',{edit:false,add:false,del:false,search:false});                     
                        jQuery("#tbl_rep").jqGrid('navButtonAdd','#rp_nav',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_edit.png'>",                              
                        title: "Изменить статус ремонта",
                        buttonicon: 'none',
                            onClickButton:function(){
                                var id = jQuery("#tbl_rep").jqGrid('getGridParam','selrow');
                                    if (id)	{ 
                                        var ret = jQuery("#tbl_rep").jqGrid('getRowData',id);
                                                $("#pg_add_edit").dialog({autoOpen: false,height: 480,width: 620,modal:true,title: "Ремонт имущества" });
                                                $("#pg_add_edit" ).dialog( "open" );                                   
                                                $("#pg_add_edit").load("controller/client/view/equipment/service.php?step=edit&eqid="+id);                    
                                    } else { alert("Выберите ТМЦ для изменения статуса ремонта!");}	
                                    } 
                        });                         
                        jQuery("#tbl_rep").jqGrid('navButtonAdd',"#rp_nav",{caption:"<img src='controller/client/themes/"+theme+"/ico/disk.png'>",                              
                            title: "Экспорт в Excel",
                            buttonicon: 'none',
                                onClickButton:function(){
                                     var id = jQuery("#workmen").jqGrid('getGridParam','selrow');
                                    if (id)	{ // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
                                        var ret = jQuery("#workmen").jqGrid('getRowData',id);
                                        tmc=ret.nomename+" инвентарный №"+ret.invnum;
                                        exportExcel("#tbl_rep",tmc);
                                    } else { alert("Выберите ТМЦ для вывода отчета!");}	
                                } 
                        });         
         
        },
	autowidth: true,
	shrinkToFit: true,		
	height: 200,	
   	grouping:true,
   	groupingView : {
            groupText : ['<b>{0} - {1} Item(s)</b>'],
	    groupCollapse : false,
            groupField : ['repair']	    
   	},
   	pager: '#workmen_footer',
   	sortname: 'orgname',
    viewrecords: true,
    rowNum:1000,    
    scroll:1,
    sortorder: "asc",
    editurl:"controller/server/tmc/workmen.php",
    caption:"Сервисное обслуживание ТМЦ"
});
jQuery("#workmen").jqGrid('navGrid','#workmen_footer',{edit:false,add:false,del:false,search:false});
jQuery("#workmen").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

jQuery("#workmen").jqGrid('navButtonAdd','#workmen_footer',{caption:"<img src='controller/client/themes/"+theme+"/ico/computer_error.png'>",                              
title: "Отдать в ремонт ТМЦ",
buttonicon: 'none',
     onClickButton:function(){
                                var id = jQuery("#workmen").jqGrid('getGridParam','selrow');
                                    if (id)	{ // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
                                        var ret = jQuery("#workmen").jqGrid('getRowData',id);
                                                $("#pg_add_edit").dialog({autoOpen: false,height: 480,width: 620,modal:true,title: "Ремонт имущества" });
                                                $("#pg_add_edit" ).dialog( "open" );                                   
                                                $("#pg_add_edit").load("controller/client/view/equipment/service.php?step=add&eqid="+id);                    
                                    } else { alert("Выберите ТМЦ для ремонта!");}	
                                    } 
                        }); 
