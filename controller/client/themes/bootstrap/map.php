<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
$morgs = GetArrayOrgs();
if ($user->mode == 1) {
    ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-4 col-md-4 col-sm-4">
			<div class="form-group">
				<label> <input type="checkbox" checked id=grpom name=grpom>
					Группировка по помещению
				</label>
				<div name="sel_pom" id="sel_pom"></div>
				<div name="sel_tmc" id="sel_tmc"></div>
			</div>
			<div class="form-group">
				<input type="checkbox" id=moveme name=moveme> Двигать ТМЦ</br> <input
					type="checkbox" checked id=stmetka name=stmetka> Стиль - метки
			</div>
		</div>
		<div class="col-xs-8 col-md-8 col-sm-8" id="map"
			style="height: 600px; width: 800px;"></div>
	</div>
</div>
<div id=msgid></div>
<div id=myConfig name=myConfig></div>
<script
	src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"
	type="text/javascript"></script>
<script type="text/javascript" src="controller/client/js/mapsplaces.js"></script>
<script type="text/javascript" src="controller/client/js/maps.js"></script>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}

?>