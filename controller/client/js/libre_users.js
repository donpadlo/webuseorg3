jQuery('#list2').jqGrid({
	url: route + 'controller/server/users/libre_users.php?org_status=list',
	datatype: 'json',
	colNames: [' ', 'Id', 'Организация', 'Логин', 'Пароль', 'E-mail', 'Админ', ''],
	colModel: [
		{name: 'active', index: 'active', width: 10, search: false},
		{name: 'users.id', index: 'users.id', width: 55, hidden: true},
		{name: 'org.id', index: 'org.id', width: 100},
		{name: 'login', index: 'login', width: 45, editable: true},
		{name: 'pass', index: 'pass', width: 45, editable: true, edittype: 'password', search: false},
		{name: 'email', index: 'email', width: 45, editable: true},
		{name: 'mode', index: 'mode', width: 45, editable: true, edittype: 'checkbox', editoptions: {value: 'Да:Нет'}, search: false},
		{name: 'myac', width: 60, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: true}, search: false}
	],
	onSelectRow: function(ids) {
		if (ids == null) {
			ids = 0;
			if (jQuery('#list3').jqGrid('getGridParam', 'records') > 0) {
				jQuery('#list3').jqGrid('setGridParam', {
					url: route + 'controller/server/users/usersroles.php?userid=' + ids + '&orgid=' + defaultorgid
				});
				jQuery('#list3').jqGrid('setGridParam', {
					editurl: route + 'controller/server/users/usersroles.php?userid=' + ids + '&orgid=' + defaultorgid
				}).trigger('reloadGrid');
				GetSubGrid();
			}
		} else {
			jQuery('#list3').jqGrid('setGridParam', {
				url: route + 'controller/server/users/usersroles.php?userid=' + ids + '&orgid=' + defaultorgid
			});
			jQuery('#list3').jqGrid('setGridParam', {
				editurl: route + 'controller/server/users/usersroles.php?userid=' + ids + '&orgid=' + defaultorgid
			}).trigger('reloadGrid');
			GetSubGrid();
		}
	},
	autowidth: true,
	scroll: 1,
	rowNum: 200,
	rowList: [10, 20, 30],
	pager: '#pager2',
	sortname: 'id',
	multiselect: true,
	viewrecords: true,
	sortorder: 'asc',
	editurl: route + 'controller/server/users/libre_users.php?org_status=edit',
	caption: 'Справочник пользователей'
});
jQuery('#list2').jqGrid('setGridHeight', $(window).innerHeight() / 2);
jQuery('#list2').jqGrid('navGrid', '#pager2', {edit: false, add: false, del: false, search: false});
jQuery('#list2').jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {
	caption: '<img src="controller/client/themes/' + theme + '/ico/tag.png">',
	title: 'Выбор колонок',
	buttonicon: 'none',
	onClickButton: function() {
		jQuery('#list2').jqGrid('columnChooser');
	}
});
jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {caption: '<img src="controller/client/themes/' + theme + '/ico/user_add.png">',
	title: 'Добавить',
	buttonicon: 'none',
	onClickButton: function() {
		$('#add_edit').dialog({autoOpen: false, height: 420, width: 400, modal: true, title: 'Добавление пользователя'});
		$('#add_edit').dialog('open');
		$('#add_edit').load('controller/client/view/users/user_add.php');
	}
});
jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {caption: '<img src="controller/client/themes/' + theme + '/ico/user_edit.png">',
	title: 'Изменить данные',
	buttonicon: 'none',
	onClickButton: function() {
		var gsr = jQuery('#list2').jqGrid('getGridParam', 'selrow');
		if (gsr) {
			$('#add_edit').dialog({autoOpen: false, height: 420, width: 400, modal: true, title: 'Редактирование пользователя'});
			$('#add_edit').dialog('open');
			$('#add_edit').load('controller/client/view/users/user_edit.php?id=' + gsr);
		} else {
			//alert('Сначала выберите строку!');
			$().toastmessage('showWarningToast', 'Сначала выберите строку!');
		}
	}
});
jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {caption: '<img src="controller/client/themes/' + theme + '/ico/user_comment.png">',
	title: 'Профиль',
	buttonicon: 'none',
	onClickButton: function() {
		var gsr = jQuery('#list2').jqGrid('getGridParam', 'selrow');
		if (gsr) {
			$('#add_edit').dialog({autoOpen: false, height: 440, width: 550, modal: true, title: 'Редактирование профиля'});
			$('#add_edit').dialog('open');
			$('#add_edit').load('index.php?route=/controller/client/view/users/profile_add_edit.php?userid=' + gsr);
		} else {
			//alert('Сначала выберите строку!');
			$().toastmessage('showWarningToast', 'Сначала выберите строку!');
		}
	}
});
jQuery('#list2').jqGrid('navButtonAdd', '#pager2', {caption: '<img src="controller/client/themes/' + theme + '/ico/vcard.png">',
	title: 'Бейджик',
	buttonicon: 'none',
	onClickButton: function() {
		var gsr = jQuery('#list2').jqGrid('getGridParam', 'selrow');
		if (gsr) {
			var s = jQuery('#list2').jqGrid('getGridParam', 'selarrrow');
			newWin = window.open('inc/stikerprint.php?mass=' + s, 'printWindow');
		} else {
			//alert('Сначала выберите строку!');
			$().toastmessage('showWarningToast', 'Сначала выберите строку!');
		}
	}
});

function GetSubGrid() {
	var addOptions = {
		top: 0, left: 0, width: 500
	};
	jQuery('#list3').jqGrid({
		height: 100,
		autowidth: true,
		url: route + 'controller/server/users/usersroles.php?userid=',
		datatype: 'json',
		colNames: ['Id', 'Роль', 'Действия'],
		colModel: [
			{name: 'places_users.id', index: 'places_users.id', width: 55},
			{name: 'role', index: 'role', width: 200, editable: true, edittype: 'select', editoptions: {
					editrules: {required: true},
					dataUrl: 'controller/server/users/getlistroles.php?orgid=' + defaultorgid
				}},
			{name: 'myac', width: 80, fixed: true, sortable: false, resize: false, formatter: 'actions', formatoptions: {keys: true}}
		],
		rowNum: 5,
		pager: '#pager3',
		sortname: 'id',
		scroll: 1,
		viewrecords: true,
		sortorder: 'asc',
		caption: 'Роли пользователя'
	}).navGrid('#pager3', {add: true, edit: false, del: false, search: false}, {}, addOptions, {}, {multipleSearch: false}, {closeOnEscape: true});
}
