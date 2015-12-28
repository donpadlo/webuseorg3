<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$eqid = GetDef('eqid');

$SQL = "SELECT * FROM equipment WHERE id='$eqid'";
$result = $sqlcn->ExecuteSQL($SQL)
		or die('Не могу выбрать список фото!'.mysqli_error($sqlcn->idsqlconnection));
$photo = '';
while ($row = mysqli_fetch_array($result)) {
	$photo = $row['photo'];
}
?>
<div class="thumbnails">  
    <a href="#" class="thumbnail">
		<?php
		if ($photo != '') {
			echo "<img src=photos/$photo>";
		} else {
			echo "<img src=controller/client/themes/$cfg->theme/img/noimage.jpg>";
		}
		?>        
	</a>  
</div>
