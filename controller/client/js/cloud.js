/*
 * Данный код создан и распространяется по лицензии GPL v3
 * Разработчики:
 *   Грибов Павел,
 *   Сергей Солодягин (solodyagin@gmail.com)
 *   (добавляйте себя если что-то делали)
 * http://грибовы.рф
 */

function ViewFileList(keyme) {
	//$('#cloud_files').jqGrid('GridUnload');
	$.jgrid.gridUnload('#cloud_files');
	jQuery('#cloud_files').jqGrid({
		url: 'controller/server/cloud/listfiles.php?cloud_dirs_id=' + keyme,
		datatype: 'json',
		colNames: ['Id', 'Скачать', 'Наименование документа', 'Файл', 'Дата', 'Размер', 'Действия'],
		colModel: [
			{name: 'id', index: 'id', width: 25, hidden: true},
			{name: 'ico', index: 'ico', width: 25, align: "center"},
			{name: 'title', index: 'title', width: 265, editable: true},
			{name: 'filename', index: 'filename', width: 120, hidden: true},
			{name: 'dt', index: 'dt', width: 36},
			{name: 'sz', index: 'sz', width: 90, hidden: true},
			{name: 'myac', width: 80, fixed: true, sortable: false, resize: false,
				formatter: 'actions', formatoptions: {keys: true}}

		],
		autowidth: true,
		pager: '#cloud_files_pager',
		sortname: 'dt',
		scroll: 1,
		shrinkToFit: true,
		viewrecords: true,
		height: 200,
		sortorder: 'desc',
		editurl: 'controller/server/cloud/listfiles.php?cloud_dirs_id=' + keyme,
		caption: 'Файлы для просмотра'
	});
}

$('#simple-btn').fileapi({
	url: route + 'controller/server/cloud/uploadfiles.php?',
	data: {'geteqid': 0},
	multiple: true,
	maxSize: 20 * FileAPI.MB,
	autoUpload: true,
	onFileComplete: function(evt, uiEvt) {
		if (uiEvt.result.msg != 'error') {
			jQuery('#cloud_files').jqGrid().trigger('reloadGrid');
		}
	},
	elements: {
		size: '.js-size',
		active: {show: '.js-upload', hide: '.js-browse'},
		progress: '.js-progress'
	}
});

function GetTree() {
	// --- Initialize first Dynatree -------------------------------------------
	$('#tree').dynatree({
		autoCollapse: false,
		minExpandLevel: 3,
		initAjax: {
			url: 'controller/server/cloud/gettree.php'
		},
		onActivate: function(node) {
			selectedkey = node.data.key;
			ViewFileList(selectedkey);
			$('#simple-btn').fileapi('data', {'selectedkey': selectedkey});
			$("#simple-btn").css('visibility', 'visible');
		},
		onLazyRead: function(node) {
			// Mockup a slow reqeuest ...
			node.appendAjax({
				url: 'sample-data2.json',
				debugLazyDelay: 750 // don't do this in production code
			});
		},
		dnd: {
			onDragStart: function(node) {
				/** This function MUST be defined to enable dragging for the tree.
				 *  Return false to cancel dragging of node.
				 */
				logMsg('tree.onDragStart(%o)', node);
				return true;
			},
			onDragStop: function(node) {
				// This function is optional.
				logMsg('tree.onDragStop(%o)', node);
			},
			autoExpandMS: 1000,
			preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			onDragEnter: function(node, sourceNode) {
				/** sourceNode may be null for non-dynatree droppables.
				 *  Return false to disallow dropping on node. In this case
				 *  onDragOver and onDragLeave are not called.
				 *  Return 'over', 'before, or 'after' to force a hitMode.
				 *  Return ['before', 'after'] to restrict available hitModes.
				 *  Any other return value will calc the hitMode from the cursor position.
				 */
				logMsg('tree.onDragEnter(%o, %o)', node, sourceNode);
				return true;
			},
			onDragOver: function(node, sourceNode, hitMode) {
				/** Return false to disallow dropping this node.
				 *
				 */
				logMsg('tree.onDragOver(%o, %o, %o)', node, sourceNode, hitMode);
				// Prevent dropping a parent below it's own child
				if (node.isDescendantOf(sourceNode)) {
					return false;
				}
				// Prohibit creating childs in non-folders (only sorting allowed)
				if (!node.data.isFolder && hitMode === 'over') {
					return 'after';
				}
			},
			onDrop: function(node, sourceNode, hitMode, ui, draggable) {
				/** This function MUST be defined to enable dropping of items on
				 * the tree.
				 */
				logMsg('tree.onDrop(%o, %o, %s)', node, sourceNode, hitMode);
				sourceNode.move(node, hitMode);
				$.get('controller/server/cloud/movefolder.php?nodekey=' + node.data.key + '&srnodekey=' + sourceNode.data.key, function(data) {
					if (data != '') {
						alert(data);
					}
				});

				//SaveAllNodes(node, sourceNode);
				// expand the drop target
//        sourceNode.expand(true);
			},
			onDragLeave: function(node, sourceNode) {
				logMsg('tree.onDragLeave(%o, %o)', node, sourceNode);
			}
		}
	});
}

selectedkey = '';
GetTree();

$('#newfolder').click(function() {
	if ($('#foldername').val() == '') {
		//alert('Введите имя папки!');
		$().toastmessage('showWarningToast', 'Введите имя папки!');
	} else {
		$('#tree').dynatree('destroy');
		$.get('controller/server/cloud/addfolder.php?foldername=' + $('#foldername').val(), function(data) {
			if (data != '') {
				//alert(data);
				$().toastmessage('showWarningToast', data);
			}
			GetTree();
		});
	}
});

$('#delfolder').click(function() {
	if (selectedkey == '') {
		//alert('Не выбрана папка!');
		$().toastmessage('showWarningToast', 'Не выбрана папка!');
	} else {
		if (confirm('Вы подтверждаете удаление?')) {
			$('#tree').dynatree('destroy');
			$.get('controller/server/cloud/delfolder.php?folderkey=' + selectedkey, function(data) {
				if (data != '') {
					//alert(data);
					$().toastmessage('showWarningToast', data);
				}
				GetTree();
			});
		}
	}
});
