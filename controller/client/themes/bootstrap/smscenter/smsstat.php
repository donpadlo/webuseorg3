<?php
if ($user->mode == 1) {
    ?>
<link rel='stylesheet' type='text/css'
	href='controller/client/themes/bootstrap/css/upload.css'>
<link href="js/jcrop/jquery.Jcrop.min.css" rel="stylesheet"
	type="text/css" />

<div class="row-fluid">
	<div class="well">
		<div id="simple-btn" class="btn btn-success js-fileapi-wrapper">
			<div class="js-browse">
				<span class="btn-txt">Выберите файл</span> <input type="file"
					name="filedata">
			</div>
			<div class="js-upload" style="display: none">
				<div class="progress progress-success">
					<div class="js-progress bar"></div>
				</div>
				<span class="btn-txt">Загружаю.. (<span class="js-size"></span>)
				</span>
			</div>
		</div>
	</div>

	<table id="list2"></table>
	<div id="pager2"></div>

	<script type="text/javascript"
		src="controller/client/js/lanbilling/smsstat.js"></script>

</div>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/statics/jquery.modal.js"></script>
<script>
$('#simple-btn').fileapi({
   url: 'controller/server/lanbilling/uploadsmscsv.php',
   multiple: true,
   maxSize: 20 * FileAPI.MB,
   autoUpload: true,
   elements: {
      size: '.js-size',
      active: { show: '.js-upload', hide: '.js-browse' },
      progress: '.js-progress'
   }
});
</script>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}
