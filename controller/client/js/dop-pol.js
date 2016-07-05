function list_dop(){
	var addOptions = {
		top: 0, left: 0, width: 500
	};
	jQuery('#list2').jqGrid({
		height: 100,
		autowidth: true,
		url: route + 'controller/server/common/get_dop_pol.php',
		datatype: 'json',
		colNames: ['Id', 'Имя','Идентификатор','Комментарий', 'Действия'],
		colModel: [
			{name: 'id', index: 'id', width: 55, fixed: true},
			{name: 'name', index: 'name', width: 100,editable:true},
			{name: 'name_id', index: 'name_id', width: 100,editable:true},
			{name: 'comment', index: 'comment', width: 100,editable:true},
			{name: 'myac', width: 80, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: true}}
		],
		rowNum: 5,
		pager: '#pager2',
		sortname: 'id',
		scroll: 1,
		viewrecords: true,
		sortorder: 'asc',
		caption: 'Дополнительные поля для разных целей',
		editurl:route + 'controller/server/common/get_dop_pol.php',
	}).navGrid('#pager2', {add: true, edit: false, del: false, search: false}, {}, addOptions, {}, {multipleSearch: false}, {closeOnEscape: true});    
};
$(document).ready(function() {
    list_dop();
});