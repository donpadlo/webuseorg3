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
	jQuery('#tbl_equpment').jqGrid({
		url: route + 'controller/server/equipment/equipment.php?sorgider=' + defaultorgid,
		datatype: 'json',
		colNames: [' ', 'Id', 'Помещение', 'Номенклатура', 'Группа', 'В пути',
			'Производитель', 'Имя по бухгалтерии', 'Сер.№', 'Инв.№',
			'Штрихкод', 'Организация', 'Мат.отв.', 'Оприходовано', 'Стоимость',
			'Тек. стоимость', 'ОС', 'Списано', 'Карта', 'Комментарий', 'Ремонт',
			'Гар.срок', 'Поставщик', ''],
		colModel: [
			{name: 'active', index: 'active', width: 20, search: false, frozen: true},
			{name: 'equipment.id', index: 'equipment.id', width: 55, search: false, frozen: true, hidden: true},
			{name: 'placesid', index: 'placesid', width: 155, stype: 'select', frozen: true,
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlistplaces.php?addnone=true'}},
			{name: 'nomename', index: 'getvendorandgroup.nomename', width: 155, frozen: true},
			{name: 'getvendorandgroup.groupname', index: 'getvendorandgroup.grnomeid', width: 100, stype: 'select',
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlistgroupname.php?addnone=true'}},
			{name: 'tmcgo', index: 'tmcgo', width: 80, search: true, stype: 'select',
				searchoptions: {dataUrl: route + 'controller/server/equipment/getlisttmcgo.php?addnone=true'},
				formatter: 'checkbox', edittype: 'checkbox', editoptions: {value: 'Yes:No'}, editable: true,hiddem:true
			},
			{name: 'getvendorandgroup.vendorname', index: 'getvendorandgroup.vendorname', width: 60},
			{name: 'buhname', index: 'buhname', width: 155, editable: true},
			{name: 'sernum', index: 'sernum', width: 100, editable: true},
			{name: 'invnum', index: 'invnum', width: 100, editable: true},
			{name: 'shtrihkod', index: 'shtrihkod', width: 100, editable: true},
			{name: 'org.name', index: 'org.name', width: 155, hidden: true},
			{name: 'fio', index: 'fio', width: 100},
			{name: 'datepost', index: 'datepost', width: 80},
			{name: 'cost', index: 'cost', width: 55, editable: true, hidden: true},
			{name: 'currentcost', index: 'currentcost', width: 55, editable: true, hidden: true},
			{name: 'os', index: 'os', width: 35, editable: true, formatter: 'checkbox', edittype: 'checkbox',
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'mode', index: 'equipment.mode', width: 55, editable: true, formatter: 'checkbox', edittype: 'checkbox',
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'eqmapyet', index: 'eqmapyet', width: 55, editable: true, formatter: 'checkbox', edittype: 'checkbox',
				editoptions: {value: 'Yes:No'}, search: false, hidden: true},
			{name: 'comment', index: 'equipment.comment', width: 200, editable: true, edittype: 'textarea',
				editoptions: {rows: '3', cols: '10'}, search: false, hidden: true},
			{name: 'eqrepair', hidden: true, index: 'eqrepair', width: 35, editable: true, formatter: 'checkbox', edittype: 'checkbox',
				editoptions: {value: 'Yes:No'}, search: false},
			{name: 'dtendgar', index: 'dtendgar', width: 55, editable: false, hidden: true, search: false},
			{name: 'kntname', index: 'kntname', width: 55, editable: false, hidden: true, search: false},
			{name: 'myac', width: 80, fixed: true, sortable: false, resize: false, formatter: 'actions',
				formatoptions: {keys: true}, search: false}
		],
		onSelectRow: function(ids) {
			$('#photoid').load(route + 'controller/server/equipment/getphoto.php?eqid=' + ids);
			jQuery('#tbl_move').jqGrid('setGridParam', {url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids});
			jQuery('#tbl_move').jqGrid({
				url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids,
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
				editurl: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids,
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
			jQuery("#tbl_rep").jqGrid('setGridParam', {url: 'controller/server/equipment/getrepinfo.php?eqid=' + ids});
			jQuery("#tbl_rep").jqGrid({
				url: 'controller/server/equipment/getrepinfo.php?eqid=' + ids,
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
				editurl: 'controller/server/equipment/getrepinfo.php?eqid=' + ids,
				caption: 'История ремонтов'
			}).trigger('reloadGrid');
			jQuery('#tbl_rep').jqGrid('navGrid', '#rp_nav', {edit: false, add: false, del: false, search: false});
			jQuery('#tbl_rep').jqGrid('navButtonAdd', '#rp_nav', {
				caption: '<img src="controller/client/themes/' + theme + '/ico/computer_error.png">',
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
		subGrid: true,
		multiselect: true,		
		autowidth: true,
		shrinkToFit: true,
		pager: '#pg_nav',
		sortname: 'equipment.id',
		rowNum: 20,
		//loadonce: true,
		//scroll: 1,
		viewrecords: true,
		sortorder: 'asc',
		editurl: route + 'controller/server/equipment/equipment.php?sorgider=' + defaultorgid,
		caption: 'Оргтехника'
	});
	jQuery('#tbl_equpment').jqGrid('setGridHeight', $(window).innerHeight() / 3);
	jQuery('#tbl_equpment').jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
	jQuery('#tbl_equpment').jqGrid('bindKeys', '');
	jQuery('#tbl_equpment').jqGrid('navGrid', '#pg_nav', {edit: false, add: false, del: false, search: false});
	jQuery('#tbl_equpment').jqGrid('setFrozenColumns');
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/tag.png">',
		title: 'Выбор колонок',
		buttonicon: 'none',
		onClickButton: function() {
			jQuery('#tbl_equpment').jqGrid('columnChooser');
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/computer_add.png">',
		title: "Добавить ТМЦ",
		buttonicon: 'none',
		onClickButton: function() {
			$('#pg_add_edit').dialog({autoOpen: false, height: 600, width: 780, modal: true, title: 'Добавление имущества'});
			$('#pg_add_edit').dialog('open');
			$('#pg_add_edit').load('controller/client/view/equipment/equipment.php?step=add&id=');
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/computer_edit.png">',
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
		caption: '<img src="controller/client/themes/' + theme + '/ico/computer_go.png">',
		title: 'Переместить ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var gsr = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (gsr) {
				$('#pg_add_edit').dialog({autoOpen: false, height: 440, width: 620, modal: true, title: 'Перемещение имущества'});
				$('#pg_add_edit').dialog('open');
				$('#pg_add_edit').load('controller/client/view/equipment/move.php?step=move&id=' + gsr);
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery("#tbl_equpment").jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/computer_error.png">',
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
		caption: '<img src="controller/client/themes/' + theme + '/ico/table.png">',
		title: 'Вывести штрихкоды ТМЦ',
		buttonicon: 'none',
		onClickButton: function() {
			var gsr = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selrow');
			if (gsr) {
				var s;
				s = jQuery('#tbl_equpment').jqGrid('getGridParam', 'selarrrow');
				newWin = window.open('inc/ean13print.php?mass=' + s, 'printWindow');
			} else {
				//alert('Сначала выберите строку!');
				$().toastmessage('showWarningToast', 'Сначала выберите строку!');
			}
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/report.png">',
		title: 'Отчеты',
		buttonicon: 'none',
		onClickButton: function() {
			newWin2 = window.open('?content_page=report_tmc', 'printWindow2');
		}
	});
	jQuery('#tbl_equpment').jqGrid('navButtonAdd', '#pg_nav', {
		caption: '<img src="controller/client/themes/' + theme + '/ico/disk.png">',
		title: 'Экспорт XML',
		buttonicon: 'none',
		onClickButton: function() {
			newWin2 = window.open(route + 'controller/server/equipment/export_xml.php', 'printWindow4');
		}
	});
	jQuery('#tbl_equpment').jqGrid('setFrozenColumns');
}

function GetListUsers(orgid, userid) {
	$('#susers').load('controller/server/getlistusers.php?orgid=' + orgid + '&userid=' + userid);
}

function GetListPlaces(orgid, placesid) {
	$('#splaces').load(route + 'controller/server/getlistplaces.php?orgid=' + orgid + '&placesid=' + placesid);
}

$(document).ready(function() {
	for (var selector in config) {
		$(selector).chosen(config[selector]);
	}
	LoadTable();
});
