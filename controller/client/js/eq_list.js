function parseGET(url) {
	if (!url || url == '')
		url = document.location.search;
	if (url.indexOf('?') < 0)
		return Array();

	url = url.split('?');
	url = url[1];

	var GET = [];
	var params = [];
	var keyval = [];

	if (url.indexOf('#') != -1) {
		anchor = url.substr(url.indexOf('#') + 1);
		url = url.substr(0, url.indexOf('#'));
	}

	if (url.indexOf('&') > -1)
		params = url.split('&');
	else
		params[0] = url;

	for (i = 0; i < params.length; i++) {
		if (params[i].indexOf('=') > -1)
			keyval = params[i].split('=');
		else {
			keyval[0] = params[i];
			keyval[1] = true;
		}
		GET[keyval[0]] = keyval[1];
	}

	return (GET);
}

function ListEqByPlaces(list, pager) {
	$_GET = parseGET();
	tmp = $_GET['usid'];
	if (typeof (tmp) != 'undefined') {
		curuserid = tmp;
	} else {
		curuserid = defaultuserid;
	}
	//$('#prof_user').load('controller/server/getprofileinfo.php?curuserid=' + curuserid);
	jQuery(list).jqGrid({
		url: route + 'controller/server/equipment/eq_list.php?curuserid=' + curuserid,
		datatype: 'json',
		colNames: ['Id', 'Помещение', 'Наименование', 'Группа', 'Инвентарник', 'Серийник', 'Штрихкод', 'Списан'],
		colModel: [
			{name: 'id', index: 'id', width: 20},
			{name: 'plname', index: 'plname', width: 55, hidden: true, viewable: false},
			{name: 'namenome', index: 'namenome', width: 100},
			{name: 'grname', index: 'grname', width: 100},
			{name: 'invnum', index: 'invnum', width: 100},
			{name: 'sernum', index: 'sernum', width: 100},
			{name: 'shtrihkod', index: 'shtrihkod', width: 100},
			{name: 'mode', index: 'mode', width: 55, formatter: 'checkbox', edittype: 'checkbox'}
		],
		onSelectRow: function(ids) {
			$('#photoid').load(route + 'controller/server/equipment/getphoto.php?eqid=' + ids);
			$('#geteqid').val(ids);
			jQuery('#tbl_move').jqGrid('setGridParam', {url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids});
			jQuery('#tbl_move').jqGrid({
				url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids,
				datatype: 'json',
				colNames: ['Id', 'Дата', 'Организация', 'Помещение', 'Сотрудник', 'Организация', 'Помещение', 'Сотрудник', 'Комментарий'],
				colModel: [
					{name: 'id', index: 'id', width: 25},
					{name: 'dt', index: 'dt', width: 95},
					{name: 'orgname1', index: 'orgname1', width: 120},
					{name: 'place1', index: 'place1', width: 80},
					{name: 'user1', index: 'user1', width: 90},
					{name: 'orgname2', index: 'orgname2', width: 120},
					{name: 'place2', index: 'place2', width: 80},
					{name: 'user2', index: 'user2', width: 90},
					{name: 'comment', index: 'comment', width: 200, editable: true},
				],
				autowidth: true,
				pager: '#pager4',
				sortname: 'dt',
				scroll: 1,
				shrinkToFit: false,
				viewrecords: true,
				height: 'auto',
				sortorder: 'desc',
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
		},
		autowidth: true,
		height: 200,
		shrinkToFit: true,
		grouping: true,
		groupingView: {
			groupText: ['<b>{0} - {1} Item(s)</b>'],
			groupColumnShow: [false],
			groupField: ['plname']
		},
		pager: pager,
		sortname: 'namenome',
		viewrecords: true,
		rowNum: 1000,
		scroll: 1,
		sortorder: 'asc',
		caption: 'Список имущества'
	});
}

function ListEqByMat(list, pager) {
	var $_GET = parseGET();
	tmp = $_GET['usid'];
	if (typeof (tmp) != 'undefined') {
		curuserid = tmp;
	}
	//alert(tmp);
	jQuery(list).jqGrid({
		url: route + 'controller/server/equipment/eq_list_mat.php?curuserid=' + curuserid,
		datatype: 'json',
		colNames: ['Id', 'Помещение', 'Наименование', 'Группа', 'Инвентарник', 'Серийник', 'Штрихкод', 'Списан', 'ОС', 'Цена', 'Тек.стоим', 'Бух.имя'],
		colModel: [
			{name: 'id', index: 'id', width: 20, frozen: true},
			{name: 'plname', index: 'plname', width: 55, hidden: true, viewable: false, frozen: true},
			{name: 'namenome', index: 'namenome', width: 100, frozen: true},
			{name: 'grname', index: 'grname', width: 100},
			{name: 'invnum', index: 'invnum', width: 100},
			{name: 'sernum', index: 'sernum', width: 100},
			{name: 'shtrihkod', index: 'shtrihkod', width: 100},
			{name: 'mode', index: 'mode', width: 55, formatter: 'checkbox', edittype: 'checkbox'},
			{name: 'os', index: 'os', width: 55, formatter: 'checkbox', edittype: 'checkbox'},
			{name: 'cs', index: 'cs', width: 100},
			{name: 'curc', index: 'curc', width: 100},
			{name: 'bn', index: 'bn', width: 100}
		],
		onSelectRow: function(ids) {
			$('#photoid').load(route + 'controller/server/equipment/getphoto.php?eqid=' + ids);
			$('#geteqid').val(ids);
			jQuery('#tbl_move').jqGrid('setGridParam', {url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids});
			jQuery('#tbl_move').jqGrid({
				url: route + 'controller/server/equipment/getmoveinfo.php?eqid=' + ids,
				datatype: 'json',
				colNames: ['Id', 'Дата', 'Организация', 'Помещение', 'Человек', 'Организация', 'Помещение', 'Человек', 'Комментарий'],
				colModel: [
					{name: 'id', index: 'id', width: 25},
					{name: 'dt', index: 'dt', width: 95},
					{name: 'orgname1', index: 'orgname1', width: 120},
					{name: 'place1', index: 'place1', width: 80},
					{name: 'user1', index: 'user1', width: 90},
					{name: 'orgname2', index: 'orgname2', width: 120},
					{name: 'place2', index: 'place2', width: 80},
					{name: 'user2', index: 'user2', width: 90},
					{name: 'comment', index: 'comment', width: 200, editable: true},
				],
				autowidth: true,
				pager: '#pager4',
				sortname: 'dt',
				scroll: 1,
				shrinkToFit: false,
				viewrecords: true,
				height: 'auto',
				sortorder: 'desc',
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
		},
		autowidth: true,
		height: 200,
		shrinkToFit: true,
		grouping: true,
		groupingView: {
			groupText: ['<b>{0} - {1} Item(s)</b>'],
			groupCollapse: true,
			groupColumnShow: [false],
			groupField: ['plname']
		},
		pager: pager,
		sortname: 'namenome',
		viewrecords: true,
		rowNum: 1000,
		scroll: 1,
		sortorder: 'asc',
		caption: 'Список имущества (материальная ответственность)'
	});
	jQuery('#list2').jqGrid('setFrozenColumns');
}

$('#myTab a:first').click(function(e) {
	//jQuery("#list2").GridUnload("#list2");
	$.jgrid.gridUnload('#list2');
	ListEqByPlaces('#list2', 'pager2');
});

$('#myTab a:last').click(function(e) {
	//jQuery("#list2").GridUnload("#list2");
	$.jgrid.gridUnload('#list2');
	ListEqByMat('#list2', 'pager2');
});

$('#myTab a:first').tab('show'); // Выбор первой вкладки

ListEqByPlaces('#list2', 'pager2');
