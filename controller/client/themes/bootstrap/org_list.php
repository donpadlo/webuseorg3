<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";
if ($user->mode == 1) {
    ?>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/FileAPI/FileAPI.exif.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/jcrop/jquery.Jcrop.min.js"></script>
<script src="js/statics/jquery.modal.js"></script>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<table id="o_list"></table>
			<div id="o_pager"></div>
			<div id="simple-btn" class="btn btn-success js-fileapi-wrapper"
				style="visibility: hidden">
				<div class="js-browse">
					<span class="btn-txt">Загрузить схему в формате PNG</span> <input
						type="file" name="filedata">
				</div>
				<div class="js-upload" style="display: none">
					<div class="progress progress-success">
						<div class="js-progress bar"></div>
					</div>
					<span class="btn-txt">Загрузка... (<span class="js-size"></span>)
					</span>
				</div>
			</div>
			<div id="pg_add_edit"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controller/client/js/org_list.js"></script>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}

?>