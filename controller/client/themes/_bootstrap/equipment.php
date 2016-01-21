<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

/*
  Роли:
  1 = 'Полный доступ'
  2 = 'Просмотр финансовых отчетов'
  3 = 'Просмотр'
  4 = 'Добавление'
  5 = 'Редактирование'
  6 = 'Удаление'
  7 = 'Отправка СМС'
  8 = 'Манипуляции с деньгами'
  9 = 'Редактирование карт'
 */

// Есть ли у пользователя одна из ролей?
if ($user->TestRoles('1,3,4,5,6')):
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
