<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<div class="well form-inline">
				<select name="tmc_groups" id="tmc_groups">
				</select>
				<button type="submit" class="btn" id="viewwork" name="viewwork">Построить
					отчет</button>
			</div>
			<table id="tbl_rep"></table>
			<div id="pg_nav"></div>
			<div id="pg_add_edit"></div>
		</div>
	</div>
</div>
<form method="post" action="inc/csvExport.php">
	<input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
</form>
<script type="text/javascript"
	src="controller/client/js/operreports/operreports.js"></script>