<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 *
 */

if ($user->TestRoles("1,4,5,6")==true){
?>
<div class="container-fluid">
    <div class="row">            
	<div class="col-xs-12 col-md-12 col-sm-12">
<?php
echo '<table class="table table-striped table-hover">';
echo '<thead><tr>';
echo '<th>№</th>';
echo '<th>Имя скипта</th>';
echo '<th>Комментарий</th>';
echo '<th>Последнее обновление</th>';
echo '<th>Счетчик</th>';
echo '</tr></thead>';
    $sql="select comment,lastupdatedt,alert_max_count,sms_group_id,(now()-lastupdatedt) as sb,script_name,sms_txt,current_alert_count from script_run_monitoring where active=1 and (now()-lastupdatedt)>alert_max_time and current_alert_count>=alert_max_count";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список скриптов!".mysqli_error($sqlcn->idsqlconnection));
    $cnt=0;
    while($row = mysqli_fetch_array($result)) {
	$script_name=$row["script_name"];
	$alert_max_count=$row["alert_max_count"];
	$current_alert_count=$row["current_alert_count"];
	$lastupdatedt=$row["lastupdatedt"];
	$comment=$row["comment"];
	$cnt++;
	echo "<tr class='danger'><td>$cnt</td><td>$script_name</td><td>$comment</td><td>$lastupdatedt</td><td>$current_alert_count</td></tr>";
    }; 
echo "</table>";
?>
	</div>
    </div>
    <a target="_self"href="index.php?content_page=scriptalert" class="btn btn-primary btn active" role="button">Настройка скриптов мониторинга</a>
</div>    
<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
