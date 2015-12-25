<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

if ($user->TestRoles('1,4,5,6')):
?>
<div class="well">
	<select name="orgs" id="orgs">
<?php
$morgs = GetArrayOrgs(); // список активных организаций
for ($i = 0; $i < count($morgs); $i++) {
	$idorg = $morgs[$i]['id'];
	$nameorg = $morgs[$i]['name'];
	$selected = ($idorg == $cfg->defaultorgid) ? 'selected' : '';
	echo "<option $selected value=\"$idorg\">$nameorg</option>";
}
?>
	</select>
	<table id="tbl_equpment"></table>
	<div id="pg_nav"></div>
	<div id="pg_add_edit"></div>
	<div class="row-fluid">
		<div class="span2">
			<div id=photoid></div>
		</div>
		<div class="span10">
			<table id="tbl_move"></table>
			<div id="mv_nav"></div>
			<table id="tbl_rep"></table>
			<div id="rp_nav"></div>
		</div>
	</div>
</div>
<script src="controller/client/js/equipment.js"></script>
<?php else: ?>
<div class="alert alert-error">
	У вас нет доступа в данный раздел!
</div>
<?php endif; ?>
