function ListEqByPlaces(oid,pid,plpid){
	if ($("#gr").prop("checked") == false){
		jQuery("#list2").jqGrid({
			url: 'controller/server/reports/report.php?curuserid='+plpid+'&curorgid='+oid+'&curplid='+pid+'&tpo='+$("#sel_rep :selected").val()+'&os='+$("#os").prop("checked")+'&mode='+$("#mode").prop("checked")+'&repair='+$("#repair").prop("checked"),
			datatype: "json",
			colNames:['Id','Помещение','Наименование','Группа','Инвентарник','Серийник','Штрихкод','Списан','ОС','Бух.имя'],
			colModel:[
				{name:'id',index:'id', width:20,hidden:true},
				{name:'plname',index:'plname', width:110},
				{name:'namenome',index:'namenome', width:140},
				{name:'grname',index:'grname', width:140},                
				{name:'invnum',index:'invnum', width:100},
				{name:'sernum',index:'sernum', width:100},
				{name:'shtrihkod',index:'shtrihkod', width:100},
				{name:'mode',index:'mode', width:55,formatter: 'checkbox',edittype: 'checkbox'},
				{name:'os',index:'os', width:55,formatter: 'checkbox',edittype: 'checkbox'},
				{name:'buhname',index:'buhname', width:155}
			],
			rownumbers: true,
			autowidth: true,
			height: "auto",
			shrinkToFit: false, 
			pager: '#pager2',
			sortname: 'plname',
			viewrecords: true,
			rowNum:1000,
			scroll:1,
			sortorder: 'asc',
			caption:'Список имущества',
			multiselect: true
		});
	} else {
		jQuery("#list2").jqGrid({
			url:'controller/server/reports/report.php?curuserid='+plpid+'&curorgid='+oid+'&curplid='+pid+'&tpo='+$("#sel_rep :selected").val()+'&os='+$("#os").prop("checked")+'&mode='+$("#mode").prop("checked")+'&repair='+$("#repair").prop("checked"),
			datatype: "json",
			colNames:['Id','Помещение','Наименование','Группа','Инвентарник','Серийник','Штрихкод','Списан','ОС','Бух.имя'],
			colModel:[
				{name:'id',index:'id', width:20,hidden:true},
				{name:'plname',index:'plname', width:110},
				{name:'namenome',index:'namenome', width:140},
				{name:'grname',index:'grname', width:140},                
				{name:'invnum',index:'invnum', width:100},
				{name:'sernum',index:'sernum', width:100},
				{name:'shtrihkod',index:'shtrihkod', width:100},
				{name:'mode',index:'mode', width:55,formatter: 'checkbox',edittype: 'checkbox'},
				{name:'os',index:'os', width:55,formatter: 'checkbox',edittype: 'checkbox'},
				{name:'buhname',index:'buhname', width:155}
			],
			grouping:true,
			groupingView : {
				groupText : ['<b>{0} - {1} Item(s)</b>'],
				groupColumnShow : [false],
				groupField : ['grname']
			},
			rownumbers: true,
			autowidth: true,
			height: "auto",
//			shrinkToFit: false, 
			pager: '#pager2',
			sortname: 'plname',
			viewrecords: true,
			rowNum:1000,
			scroll:1,
			sortorder: "asc",    
			caption:"Список имущества"
		});
	};
	jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
	jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {
		id: 'ExportToCSV',
		caption: 'Экспорт в CSV',
		title: 'Экспорт в CSV',
		onClickButton: function(e){
			exportData(e, '#list2');
		},
		buttonicon: 'ui-icon ui-icon-document',
	});
};

function exportData(e, id){
	var gridid = jQuery(id).getDataIDs();
	//var label = jQuery(id).getRowData(gridid[0]);
	var selRowIds = jQuery(id).jqGrid('getGridParam', 'selarrrow');
	var obj = new Object();
	obj.count = selRowIds.length;
	if (obj.count){
		obj.items = new Array();
		for (elem in selRowIds){
			obj.items.push(jQuery(id).getRowData(selRowIds[elem]));
		}
		var json = JSON.stringify(obj);
		JSONToCSVConvertor(json, 'csv', 1);
	}
}

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel){
	var arrData = (typeof JSONData != 'object') ? JSON.parse(JSONData) : JSONData;
	var CSV = '';
	if (ShowLabel){
		var row = '';
		for (var index in arrData.items[0]){
			row += index + ';';
		}
		row = row.slice(0, -1);
		CSV += row + '\r\n';
	}
	for (var i = 0; i < arrData.items.length; i++){
		var row = '';
		for (var index in arrData.items[i]){
			row += '"' + arrData.items[i][index].replace(/(<([^>]+)>)/ig, '') + '";';
		}
		row.slice(0, row.length - 1);
		CSV += row + '\r\n';
	}
	if (CSV == ''){
		alert('Invalid data');
		return;
	}

	var link = document.createElement('a');
	link.id = 'lnkDwnldLnk';
	document.body.appendChild(link);
	var csv = CSV;
	blob = new Blob([csv], {type: 'text/csv'});
	var myURL = window.URL || window.webkitURL;
	jQuery('#lnkDwnldLnk').attr({
		'download': 'Export.csv',
		'href': myURL.createObjectURL(blob)
	}); 
	jQuery('#lnkDwnldLnk')[0].click();
	document.body.removeChild(link);
}
function UpdateChosen(){
    for (var selector in config) {
        $(selector).chosen({ width: '100%' });
        $(selector).chosen(config[selector]);
    };        
};    

function GetListPlaces(orgid,placesid){
       url= route + "controller/server/common/getlistplaces.php?orgid="+orgid+"&placesid="+placesid+"&addnone=true";
       //$("#sel_pom").load(url);
        $.get(url, function(data){
           $("#sel_pom").html(data);
           UpdateChosen()
       });
       
};

function GetListUsers(orgid,userid){
     //$("#sel_plp").load("controller/server/common/getlistusers.php?orgid="+orgid+"&userid="+userid+"&addnone=true");
        url="controller/server/common/getlistusers.php?orgid="+orgid+"&userid="+userid+"&addnone=true";
        $.get(url, function(data){
           $("#sel_plp").html(data);
           UpdateChosen()
        });
    };

$("#sel_orgid").change(function(){
    GetListUsers($("#sel_orgid :selected").val());
    GetListPlaces($("#sel_orgid :selected").val());
});

$("#sbt").click(function() {// обрабатываем отправку формы
    //jQuery("#list2").GridUnload("#list2");
    $.jgrid.gridUnload("#list2");
    ListEqByPlaces($("#sel_orgid :selected").val(),$("#splaces :selected").val(),$("#suserid :selected").val())
    return false;
});

$("#btprint").click(function() {// обрабатываем отправку формы
var newWin3=window.open('','printWindow3','');
newWin3.focus();
newWin3.document.write('<table id="list222">');
newWin3.document.write($("#list2").html());
newWin3.document.write('</table>');
});

GetListUsers($("#sel_orgid :selected").val(),curuserid);
GetListPlaces($("#sel_orgid :selected").val(),curuserid);