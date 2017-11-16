<?php

/*
 * (с) 2015 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */
include_once ("inc/lbfunc.php"); // загружаем функции LB
if ($user->TestRoles("1") === True) {
    $md=new Tmod; // обьявляем переменную для работы с классом модуля
    if ($md->IsActive("lanbilling")==0) {$style="style=\"display:none;\"";} else {$style="";};
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<table id="list2"></table>
			<div id="pager2"></div>
			<?php echo "<div $style>";?>
			<h3>Привязка поля к пользователю</h3>			
				    <label>Выберите пользователя:</label>
				    <?php
					$sql = "select users.id,users_profile.fio,org.name from users inner join org on org.id=users.orgid inner join users_profile on users_profile.usersid=users.id where users.active=1";
					$sts = "<select  class='chosen-select' name=chosenmanager id=chosenmanager>";
					$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список пользователей!" . mysqli_error($lb->idsqlconnection));
					$sts = $sts . "<option selected value='-1'>Не выбрано</option>";
					while ($row = mysqli_fetch_array($result)) {
					    $id = $row["id"];
					    $fio = $row["fio"];
					    $sts = $sts . "<option value='$id'>$fio</option>";
					};
					$sts = $sts . "</select>";
					echo "$sts";					
				    ?>				    
				    <table id="list3"></table>
				    <div id="pager3"></div>				
			<?php echo "</div>";?>
		</div>
	</div>
</div>
<script type="text/javascript" src="controller/client/js/dop-pol.js"></script>

<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
}

?>