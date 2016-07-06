<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
?>
<!-- saved from url=(0014)about:internet -->
<!DOCTYPE html>
<html lang="ru-RU">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Учет ТМЦ в организации и другие плюшки">
	<meta name="author" content="(c) 2011-2016 by Gribov Pavel">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $cfg->sitename ?></title>
	<meta name="generator" content="yarus">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/jquery-ui.min.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/jquery.mmenu.all.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/mmenu.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/plugins/ui.multiselect.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/bootstrap.min.css">
<?php if ($cfg->style == 'Bootstrap'): ?>
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/ui.jqgrid-bootstrap.css">
<?php elseif ($cfg->style == 'Normal'): ?>
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/ui.jqgrid.css">
<?php endif; ?>
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/chosen.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/jquery.toastmessage-min.css">
	<link rel="stylesheet" href="controller/client/themes/<?php echo $cfg->theme; ?>/css/font-awesome.min.css">
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/js/jquery-1.11.0.min.js"></script>
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/js/jquery-ui.min.js"></script>
	<script src="js/jquery.mmenu.min.all.js"></script>	
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/plugins/ui.multiselect.js"></script>
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/js/i18n/grid.locale-ru.js"></script>
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/js/jquery.jqGrid.min.js"></script>
	<script src="js/chosen.jquery.min.js"></script>
	<script src="js/jquery.toastmessage-min.js"></script>
	<script src="js/jquery.form.js"></script>
	<script src="controller/client/themes/<?php echo $cfg->theme; ?>/js/bootstrap.min.js"></script>
	<script>
		defaultorgid = <?php echo $cfg->defaultorgid; ?>;
		defaultorgid = <?php echo $cfg->defaultorgid; ?>;
		theme = '<?php echo $cfg->theme; ?>';
		defaultuserid = <?php echo ($user->id != '') ? $user->id : '-1'; ?>;
		route = '<?php echo ($userewrite == 1) ? '/route/' : 'index.php?route=/'; ?>';

		var bootstrapButton = $.fn.button.noConflict();
		$.fn.bootstrapBtn = bootstrapButton;

		$.jgrid.defaults.width = 780;
		$.jgrid.defaults.responsive = true;
<?php if ($cfg->style == 'Bootstrap'): ?>
		$.jgrid.defaults.styleUI = 'Bootstrap';
		$.jgrid.styleUI.Bootstrap.base.headerTable = 'table table-bordered table-condensed';
		$.jgrid.styleUI.Bootstrap.base.rowTable = 'table table-bordered table-condensed';
		$.jgrid.styleUI.Bootstrap.base.footerTable = 'table table-bordered table-condensed';
		$.jgrid.styleUI.Bootstrap.base.pagerTable = 'table table-condensed';
<?php endif; ?>
		var config = {
			'.chosen-select'           : {},
			'.chosen-select-deselect'  : {allow_single_deselect: true},
			'.chosen-select-no-single' : {disable_search_threshold: 4},
			'.chosen-select-no-results': {no_results_text: 'Ничего не найдено!'},
			'.chosen-select-width'     : {width: '95%'}
		}
	</script>
	<style>
		.chosen-container .chosen-results {
			max-height:100px;
		}
	</style>
</head>
<body>
<?php if (!$printable): ?>
	<div class="header">
		<a href="#menu"></a>
	</div>
	<div id="blob" data-placement="bottom" class="quickmenu" rel="popover">
<?php
$mm = '';
for ($i = 0; $i < count($cfg->quickmenu); $i++) {
	$mm .= $cfg->quickmenu[$i];
}
?>
		<strong><?php echo $cfg->sitename; ?></strong>
		<span class="caret"></span>
	</div>
	<script>
		$('#blob').popover({title: 'Быстрые ссылки', delay: {'show': 500, 'hide': 100}, html: true, content: '<?php echo $mm; ?>'});
	</script>
<?php
endif;
