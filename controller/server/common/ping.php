<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции

$orgid=GetDef('orgid');

?>
<table  id="mytable" class="table table-striped">
<thead>
<tr>
<th>Статус</th>
<th>IP (имя)</th>
<th>Название</th>
<th>Группа</th>
<th>Где</th>
</tr>
</thead>
<tbody>
<?php
$SQL = "SELECT places.name as pname,eq3.grname as grname,eq3.ip as ip,eq3.nomename as nomename FROM places INNER JOIN
    (SELECT eq2.placesid as placesid,group_nome.name as grname,eq2.ip as ip,eq2.nomename as nomename FROM group_nome INNER JOIN
    (SELECT eq.placesid as placesid,nome.groupid as groupid,eq.ip as ip,nome.name as nomename FROM nome INNER JOIN 
    (SELECT equipment.placesid as placesid,equipment.nomeid as nomeid,equipment.ip as ip FROM equipment WHERE equipment.active=1 and equipment.ip<>'' and equipment.orgid='$orgid') as eq ON eq.nomeid=nome.id) as eq2 ON eq2.groupid=group_nome.id) as eq3 ON places.id=eq3.placesid";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не получилось выполнить запрос на получение списка номенклатуры!".mysqli_error($sqlcn->idsqlconnection));
 while($row = mysqli_fetch_array($result)) { 
exec("ping $row[ip] -c 1 -w 1 && exit", $output, $retval);     
if ($retval != 0){$res='<i class="icon-remove"></i>';} else {$res='<i class="icon-ok"></i>';};
//print_r($output);
echo "<tr>";
echo "<td>$res</td>";
$ip=$row['ip'];
$nm=$row['nomename'];
$grn=$row['grname'];
$pnm=$row['pname'];
echo "<td>$ip</td>";
echo "<td>$nm</td>";
echo "<td>$grn</td>";
echo "<td>$pnm</td>";
echo "</tr>";
  };
?>

</tbody>
</table>
