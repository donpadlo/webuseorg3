<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if (($user->mode == 1) or ($user->TestRoles('1,3'))) {
    ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<table id="list2"></table>
			<div id="pager2"></div>
			<div id="add_edit"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controller/client/js/libre_nome.js"></script>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}

?>