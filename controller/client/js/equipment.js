$('#orgs').change(function() {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + 365);
	orgid = $('#orgs :selected').val();
	defaultorgid = orgid;
	document.cookie = 'defaultorgid=' + orgid + '; path=/; expires=' + exdate.toUTCString();
	//$('#tbl_equpment').jqGrid('GridUnload');
	$.jgrid.gridUnload("#tbl_equpment");
	LoadTable();
});

//jQuery.extend(jQuery.jgrid.defaults, {ajaxSelectOptions: {cache: false}});

function LoadTable() {
	if (localStorage["tblq_multiselect"]!=undefined) {	    
	    if (localStorage["tblq_multiselect"]=='false'){
		multiselect=false;
	    } else {
		multiselect=true;
	    };	    
	} else {multiselect=true;};
	console.log("multiselect:",multiselect);
	jQuery('#tbl_equpment').jqGrid({
		url: route + 'controller/server/equipment/equipment.php&sorgider=' + $('#orgs :selected').val(),
		datatype: 'json',
		colNames: [' ', 'Id', 'IP', 'Помещение', 'Номенклатура', 'Группа', 'В пути',
			'Производитель', 'Имя по бухгалтерии', 'Сер.№', 'Инв.№',
			'Штрихкод', 'Организация', 'Мат.отв.', 'Оприходовано', 'Стоимость',
			'Тек. стоимость', 'ОС', 'Списано', 'Карта', 'Комментарий', 'Ремонт',
			'Гар.срок', 'Поставщик','Подразделение',"Перемещено из", 'Инструменты'],
		colModel: [
			{name: 'active', index: 'active', width: 20, search: false, frozen: true,fixed:true},
			{name: 'equipment.id', index: 'equipment.id', width: 55, search: false, frozen: true, hidden: true,fixed:true},
			{name: 'ip', index: 'ip', width: 100, hidden: true,fixed:true},
			{name: 'placesid', index: 'placesid', width: 155, stype: 'select',fixed:true,
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlistplaces.php&addnone=true&selorgid='+ $('#orgs :selected').val()}},
			{name: 'nomename', index: 'getvendorandgroup.nomename', width: 155,fixed:true},
			{name: 'getvendorandgroup.groupname', index: 'getvendorandgroup.grnomeid', width: 100, stype: 'select',fixed:true,
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlistgroupname.php&addnone=true'}},
			{name: 'tmcgo', index: 'tmcgo', width: 80, search: true, stype: 'select',fixed:true,
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlisttmcgo.php&addnone=true'},
				formatter: 'checkbox', edittype: 'checkbox', editoptions: {value: 'Yes:No'}, editable: true,hiddem:true
			},
			{name: 'getvendorandgroup.vendorname', index: 'getvendorandgroup.vendorname', width: 60,fixed:true},
			{name: 'buhname', index: 'buhname', width: 155, editable: true,fixed:true},
			{name: 'sernum', index: 'sernum', width: 100, editable: true,fixed:true},
			{name: 'invnum', index: 'invnum', width: 100, editable: true,fixed:true},
			{name: 'shtrihkod', index: 'shtrihkod', width: 100, editable: true,fixed:true},
			{name: 'org.name', index: 'org.name', width: 155, hidden: true,fixed:true},
			{name: 'fio', index: 'fio', width: 100,fixed:true},
			{name: 'datepost', index: 'datepost', width: 80,fixed:true},
			{name: 'cost', index: 'cost', width: 55, editable: true, hidden: true,fixed:true},
			{name: 'currentcost', index: 'currentcost', width: 55, editable: true, hidden: true,fixed:true},
			{name: 'os', index: 'os', width: 35, editable: true, formatter: 'checkbox', edittype: 'checkbox',fixed:true,
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'mode', index: 'equipment.mode', width: 55, editable: true, formatter: 'checkbox', edittype: 'checkbox',fixed:true,
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'eqmapyet', index: 'eqmapyet', width: 55, editable: true, formatter: 'checkbox', edittype: 'checkbox',fixed:true,
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'comment', index: 'equipment.comment', width: 200, editable: true, edittype: 'textarea',
				editoptions: {rows: '3', cols: '10'}, search: false, hidden: true},
			{name: 'eqrepair', hidden: true, index: 'eqrepair', width: 35, editable: true, formatter: 'checkbox', edittype: 'checkbox',
				editoptions: {value: 'Yes:No'}, search: false,fixed:true},
			{name: 'dtendgar', index: 'dtendgar', width: 55, editable: false, hidden: true, search: false,fixed:true},
			{name: 'kntname', index: 'kntname', width: 55, editable: false, hidden: true, search: false,fixed:true},
			{name: 'opgroup', index: 'places.opgroup', width: 55, editable: false, hidden: true, search: true,fixed:true},
			{name: 'comefrom', index: 'comefrom', width: 100, editable: false, hidden: true, search: false},
			{name: 'myac', width: 80, fixed: true, sortable: false, resize: false, formatter: 'actions',
				formatoptions: {keys: true}, search: false}
		],		
		gridComplete : function() {
		    $("#tbl_equpment").loadCommonParam("tbleq");		    
		    jQuery('#tbl_equpment').jqGrid('setGridWidth',$(window).innerWidth()-20);
		    
		},
		resizeStop: function() {
		    $("#tbl_equpment").saveCommonParam("tbleq");
		},
		onSelectRow: function(ids) {	
			s = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selarrrow');
			jQuery('#tbl_equpment').jqGrid("setCaption", "Оргтехника ("+s.length+")");
			$('#photoid').load(route + 'controller/server/equipment/getphoto.php&eqid=' + ids);
			jQuery('#tbl_move').jqGrid('setGridParam', {url: route + 'controller/server/equipment/getmoveinfo.php&eqid=' + ids});
			jQuery('#tbl_move').jqGrid({
				url: route + 'controller/server/equipment/getmoveinfo.php&eqid=' + ids,
				datatype: 'json',
				colNames: ['Id', 'Дата', 'Организация', 'Помещение',
					'Сотрудник', 'Организация', 'Помещение', 'Сотрудник', '',
					'Комментарий', ''],
				colModel: [
					{name: 'id', index: 'id', width: 25, hidden: true},
					{name: 'dt', index: 'dt', width: 95},
					{name: 'orgname1', index: 'orgname1', width: 120, hidden: true},
					{name: 'place1', index: 'place1', width: 80},
					{name: 'user1', index: 'user1', width: 90},
					{name: 'orgname2', index: 'orgname2', width: 120, hidden: true},
					{name: 'place2', index: 'place2', width: 80},
					{name: 'user2', index: 'user2', width: 90},
					{name: 'name', index: 'name', width: 90, hidden: true},
					{name: 'comment', index: 'comment', width: 200, editable: true},
					{name: 'myac', width: 60, fixed: true, sortable: false, resize: false,
						formatter: 'actions', formatoptions: {keys: true}}
				],
				autowidth: true,
				pager: '#mv_nav',
				sortname: 'dt',
				scroll: 1,
				shrinkToFit: true,
				viewrecords: true,
				height: 200,
				sortorder: 'desc',
				editurl: route + 'controller/server/equipment/getmoveinfo.php&eqid=' + ids,
				caption: 'История перемещений'
			}).trigger('reloadGrid');
			jQuery('#tbl_move').jqGrid('destroyGroupHeader');
			jQuery('#tbl_move').jqGrid('setGroupHeaders', {
				useColSpanStyle: true,
				groupHeaders: [
					{startColumnName: 'orgname1', numberOfColumns: 3, titleText: 'Откуда'},
					{startColumnName: 'orgname2', numberOfColumns: 3, titleText: 'Куда'}
				]
			});
			//$('#tbl_rep').jqGrid('GridUnload');
			$.jgrid.gridUnload("#tbl_rep");
			jQuery("#tbl_rep").jqGrid('setGridParam', {url: route+'controller/server/equipment/getrepinfo.php&eqid=' + ids});
			jQuery("#tbl_rep").jqGrid({
				url: route+'controller/server/equipment/getrepinfo.php&eqid=' + ids,
				datatype: 'json',
				colNames: ['Id', 'Дата начала', 'Дата окончания', 'Организация', 'Стоимость', 'Комментарий', 'Статус', ''],
				colModel: [
					{name: 'id', index: 'id', width: 25, editable: false},
					{name: 'dt', index: 'dt', width: 95, editable: true, sorttype: "date", editoptions: {size: 20,
							dataInit: function(el) {
								vl = $(el).val();
								$(el).datepicker();
								$(el).datepicker('option', 'dateFormat', 'dd.mm.yy');
								$(el).datepicker('setDate', vl);
							}}
					},
					{name: 'dtend', index: 'dtend', width: 95, editable: true, editoptions: {size: 20,
							dataInit: function(el) {
								vl = $(el).val();
								$(el).datepicker();
								$(el).datepicker('option', 'dateFormat', 'dd.mm.yy');
								$(el).datepicker('setDate', vl);
							}}
					},
					{name: 'kntname', index: 'kntname', width: 120},
					{name: 'cost', index: 'cost', width: 80, editable: true, editoptions: {size: 20,
							dataInit: function(el) {
								$(el).focus();
							}}
					},
					{name: 'comment', index: 'comment', width: 200, editable: true},
					{name: 'status', index: 'status', width: 80, editable: true, edittype: 'select',
						editoptions: {value: '1:Ремонт;0:Сделано'}},
					{name: 'myac', width: 60, fixed: true, sortable: false, resize: false, formatter: 'actions',
						formatoptions: {keys: true,
							afterSave: function() {
								jQuery('#tbl_equpment').jqGrid().trigger('reloadGrid');
							}
						}}
				],
				autowidth: true,
				pager: '#rp_nav',
				sortname: 'dt',
				scroll: 1,
				//shrinkToFit: true,
				viewrecords: true,
				height: 200,
				sortorder: 'desc',
				editurl: route+'controller/server/equipment/getrepinfo.php?eqid=' + ids,
				caption: 'История ремонтов'
			}).trigger('reloadGrid');
			jQuery('#tbl_rep').jqGrid('navGrid', '#rp_nav', {edit: false, add: false, del: false, search: false});
			jQuery('#tbl_rep').jqGrid('navButtonAdd', '#rp_nav', {
				caption: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>',
				title: 'Отдать в ремонт ТМЦ',
				buttonicon: 'none',
				onClickButton: function() {
					var id = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
					if (id) { // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
						jQuery('#tbl_equpment').jqGrid('getRowData', id);
						$('#pg_add_edit').dialog({autoOpen: false, height: 380, width: 620, modal: true, title: 'Ремонт имущества'});
						$('#pg_add_edit').dialog('open');
						$('#pg_add_edit').load('controller/client/view/equipment/repair.php?step=add&eqid=' + id);
					} else {
						//alert('Выберите ТМЦ для ремонта!');
						$().toastmessage('showWarningToast', 'Выберите ТМЦ для ремонта!');
					}
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
			subgrid_table_id = subgrid_id + '_t';
			pager_id = 'p_' + subgrid_table_id;
			$('#' + subgrid_id).html('<table border="1" id="' + subgrid_table_id +
					'" class="scroll"></table><div id="' + pager_id + '" class="scroll"></div>');
			jQuery('#' + subgrid_table_id).jqGrid({
				url: 'controller/server/equipment/paramlist.php?eqid=' + row_id,
				datatype: 'json',
				colNames: ['Id', 'Наименование', 'Параметр', ''],
				colModel: [
					{name: 'id', index: 'num', width: 60, key: true},
					{name: 'name', index: 'item', width: 150},
					{name: 'param', index: 'qty', width: 310, editable: true},
					{name: 'myac', width: 80, fixed: true, sortable: false, resize: false,
						formatter: 'actions', formatoptions: {keys: true}}
				],
				editurl: 'controller/server/equipment/paramlist.php?eqid=' + row_id,
				pager: pager_id,
				sortname: 'name',
				sortorder: 'asc',
				scroll: 1,
				height: 'auto'
			});
		},
		subGridRowColapsed: function(subgrid_id, row_id) {
			// this function is called before removing the data
			var subgrid_table_id;
			subgrid_table_id = subgrid_id + '_t';
			jQuery('#' + subgrid_table_id).remove();
		},
		rownumbers: true, // show row numbers
                rownumWidth: 25, // the width of the row numbers columns		
		subGrid: true,
		multiselect: multiselect,		
		autowidth: true,
		shrinkToFit: true,
		pager: '#pg_nav',
		sortname: 'equipment.id',
		rowNum: 40,
		//loadonce: true,
		//scroll: 1,
		viewrecords: true,
		sortorder: 'asc',
		editurl: route + 'controller/server/equipment/equipment.php&sorgider=' + $('#orgs :selected').val(),
		caption: 'Оргтехника'
	});
	jQuery('#tbl_equpment').jqGrid('setGridHeight', $(window).innerHeight()-285);
	jQuery('#tbl_equpment').jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
	jQuery('#tbl_equpment').jqGrid('bindKeys', '');
	jQuery('#tbl_equpment').jqGrid('navGrid', '#pg_nav', {edit: false, add: false, del: false, search: false});
	jQuery('#tbl_equpment').jqGrid('setFrozenColumns');
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-tag" aria-hidden="true"></i>',
		title: 'Выбор колонок',
		buttonicon: 'none',
		onClickButton: function() {
		    jQuery('#tbl_equpment').jqGrid('columnChooser', {
				done: function(perm) {
				    $("#tbl_equpment").saveCommonParam("tbleq");
				},
				width: 550,
				dialog_opts: {
					modal: true,
					minWidth: 470,
					height: 470
				},
				msel_opts: {
					dividerLocation: 0.5
				}
			});
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-object-group " aria-hidden="true"></i>',
		title: 'Мультиселект',
		buttonicon: 'none',
		onClickButton: function() {
		    console.log("multiselect save:",multiselect);
		    if (multiselect==false){
			localStorage.setItem("tblq_multiselect", true); } else {
			localStorage.setItem("tblq_multiselect", false);
		    };		    
		    $.jgrid.gridUnload("#tbl_equpment");
		    LoadTable();
		}
	});	
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-plus-circle" aria-hidden="true"></i>',
		title: "Добавить ТМЦ",
		buttonicon: 'none',
		onClickButton: function() {
			$('#pg_add_edit').dialog({autoOpen: false, height: 600, width: 780, modal: true, title: 'Добавление имущества'});
			$('#pg_add_edit').dialog('open');
			$('#pg_add_edit').load('controller/client/view/equipment/equipment.php?step=add&id=');
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
		title: 'Редактировать ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var gsr = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (gsr) {
				$('#pg_add_edit').dialog({autoOpen: false, height: 600, width: 780, modal: true, title: 'Редактирование имущества'});
				$('#pg_add_edit').dialog('open');
				$('#pg_add_edit').load('controller/client/view/equipment/equipment.php?step=edit&id=' + gsr);
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-arrows" aria-hidden="true"></i>',
		title: 'Переместить ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var gsr = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (gsr) {
				$('#pg_add_edit').dialog({autoOpen: false, height: 440, width: 620, modal: true, title: 'Перемещение имущества'});
				$('#pg_add_edit').dialog('open');
				if (multiselect==true){
				    s = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selarrrow');
				} else {
				    s = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
				};
				$('#pg_add_edit').load('controller/client/view/equipment/move.php?step=move&id=' + s);
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery("#tbl_equpment").jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>',
		title: 'Отдать в ремонт ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var id = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (id) { // если выбрана строка ТМЦ который уже в ремонте, открываем список с фильтром по этому ТМЦ
				jQuery('#tbl_equpment').jqGrid('getRowData', id);
				$('#pg_add_edit').dialog({autoOpen: false, height: 380, width: 620, modal: true, title: 'Ремонт имущества'});
				$('#pg_add_edit').dialog('open');
				$('#pg_add_edit').load('controller/client/view/equipment/repair.php?step=add&eqid=' + id);
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-table" aria-hidden="true"></i>',
		title: 'Вывести штрихкоды ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var gsr = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (gsr) {
			    if (multiselect==true){
				var s;
				s = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selarrrow');
			    } else {
				s = gsr;
			    };
				newWin = window.open('inc/ean13print.php?mass=' + s, 'printWindow');
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-print" aria-hidden="true"></i>',
		title: 'Печатная версия списка',
		buttonicon: 'none',
		onClickButton: function() {
			Printable();
		}
	});	
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-floppy-o" aria-hidden="true"></i> xml',
		title: 'Экспорт XML',
		buttonicon: 'none',
		onClickButton: function() {
			newWin2 = window.open(route + 'controller/server/equipment/export_xml.php', 'printWindow4');
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<i class="fa fa-floppy-o" aria-hidden="true"></i> csv',
		title: 'Экспорт CSV',
		buttonicon: 'none',
		onClickButton: function() {
			newWin2 = window.open(route + 'controller/server/equipment/export_xml.php&mode=csv', 'printWindow4');
		}
	});
	
	jQuery('#tbl_equpment').jqGrid('setFrozenColumns');	
}

function GetListUsers(orgid, userid) {
	$('#susers').load('controller/server/getlistusers.php?orgid=' + orgid + '&userid=' + userid);
}

function GetListPlaces(orgid, placesid) {    
	$('#splaces').load(route + 'controller/server/getlistplaces.php&orgid=' + orgid + '&placesid=' + placesid);
}

function Printable(){
    start=1;
    if (multiselect===true){start=2};
    var newWin3=window.open('','Печатная форма','');
    newWin3.focus();
    newWin3.document.write('<html>'); 
    newWin3.document.write("<script>printable=true;\x3C/script>"); 
    newWin3.document.write($("#idheader").html());		    
    newWin3.document.write('<body>');     
    //newWin3.document.write($("#gview_tbl_equpment").html());    
    colNames=jQuery("#tbl_equpment").jqGrid('getGridParam',"colNames"); //названия колонок
    colModel=jQuery("#tbl_equpment").jqGrid('getGridParam',"colModel"); //параметры колонок
    dataids=$("#tbl_equpment").getDataIDs(); //идентификаторы данных в таблице
    //zxc=$("#tbl_equpment").getRowData("400"); данные
    
    //1) Рисуем табличку
    table='<table class="table table-striped table-bordered table-condensed">';
    table=table+'<thead><tr>';
    for(i=start;i<colModel.length-1;i++){
	//если колонка не скрыта - рисуем заголовок
	if (colModel[i].hidden==false){
	    table=table+'<th>'+colNames[i]+'</th>';
	};
    };
    table=table+'</tr></thead>';
    //заполняем данными
    for(z=0;z<dataids.length;z++){
	dat=$("#tbl_equpment").getRowData(dataids[z]); //данные
	table=table+'<tr>';
	for(i=start;i<colModel.length-1;i++){
	    if (colModel[i].hidden==false){
		table=table+'<td>'+dat[colModel[i].name]+'</td>';
		//console.log(dat[i]);
	    };
	};
	table=table+'</tr>';
    };
    
    table=table+"</table>";
    newWin3.document.write(table);
    newWin3.document.write('</body></html>');  
    newWin3.document.close();
};
$(document).ready(function() {
	for (var selector in config) {
		$(selector).chosen(config[selector]);
	}	
	LoadTable();
});
