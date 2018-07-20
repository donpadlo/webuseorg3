<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
if ($user->mode == 1) :
    ?>
<div class="well">
	<button name="bdel" id="bdel" class="btn btn-primary">Начать удаление</button>
	</p>
	<div id="infoblock"></div>
</div>
<script>
$('#bdel').click(function(){
	$('#infoblock').load(route+'controller/server/delete/delete.php');
	return false;
});
</script>
<?php else: ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php endif; ?>
