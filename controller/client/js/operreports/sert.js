function exportExcel(list)
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
                    "<Column ss:Index='4' ss:AutoFitWidth='0' ss:Width='130' /> "+"\n"+
                    "<Column ss:Index='5' ss:AutoFitWidth='0' ss:Width='130' /> "+"\n"+
                    "<Column ss:Index='6' ss:AutoFitWidth='0' ss:Width='120' /> "+"\n";
            headcolumn=""+
                    	"<Row>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Номер</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Склад</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Номенклатура</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Серия</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Дата окончания</Data></Cell>"+"\n"+
			"   <Cell ss:StyleID='borderedbold'><Data ss:Type='String'>Количество</Data></Cell>"+"\n"+
			"</Row> "+"\n";
            
            html=html+headxls+sworkbook+styles+sworksheet+stable+columnw+headcolumn;
            
            for(i=0;i<mya.length;i++)
                {
                data=$(list).getRowData(mya[i]); 
                html=html+"<Row>"+"\n";
                for(j=0;j<colNames.length;j++)
                    {
                    html=html+"<Cell ss:StyleID='bordered'><Data ss:Type='String'>"+data[colNames[j]]+"</Data></Cell>"+"\n";                        
                    }
                html=html+"</Row>"+"\n";
                }
            html=html+etable+eworksheet+eworkbook;
            
            document.forms[0].csvBuffer.value=html;
            document.forms[0].method='POST';
            document.forms[0].action='inc/csvExport.php'; 
            document.forms[0].target='_blank';
            document.forms[0].submit();
        };
function ViewReports(list,pager){
jQuery(list).jqGrid({
   	url:'controller/server/operreports/viewremainssert.php',
	datatype: "json",
   	colNames:['Id','Склад','Номенклатура','Серия','Дата окончания','Количество'],
   	colModel:[
   		{name:'id',index:'id', width:20},
                {name:'sklad',index:'sklad', width:155, width:155,summaryType:'count', summaryTpl : '({0}) Всего:'},
   		{name:'namenome',index:'namenome', width:100},
		{name:'seria',index:'seria', width:100},
   		{name:'dtend',index:'dtend', width:100},
                {name:'kol',index:'kol', width:100,sorttype:'number',formatter:'number', summaryType:'sum'}
   	],
        grouping: true,
   	groupingView : {
   		groupField : ['sklad'],
   		groupText : ['<b>{0}</b>'],
                groupCollapse : true,
		groupSummary : true,
   	},        
        
        viewrecords: true,
   	height: 'auto',        
	autowidth: true,	
        shrinkToFit: true, 
   	pager: pager,
   	sortname: 'sklad',
    viewrecords: true,
    rowNum:1000,
    scroll:1,
    sortorder: "asc",    
    caption:"Просоченные сертификаты"
}); 
jQuery(list).jqGrid('navGrid',pager,{edit:false,add:false,del:false,search:false});
jQuery(list).jqGrid('navButtonAdd',pager,{caption:"<i class=\"fa fa-floppy-o\" aria-hidden=\"true\"></i>",                              
        title: "Экспорт в Excel",
	onClickButton:function(){
            exportExcel(list);
	} 
});
};

   ViewReports("#tbl_rep","#pg_nav");

     $("#viewwork").click(function(){
           jQuery("#tbl_rep").GridUnload("#tbl_rep");
           ViewReports("#tbl_rep","#pg_nav");
    });
    