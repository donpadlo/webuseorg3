<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// (добавляйте себя если что-то делали)
// http://грибовы.рф
$alert_max_count = _POST("alert_max_count");
$alert_max_time = _POST("alert_max_time");
$sms_txt = _POST("sms_txt");
$sid = _POST("sid");
$sms_group_id = _POST("sms_group_id");
$sms_txt = mysqli_real_escape_string($sqlcn->idsqlconnection, $sms_txt);
$sql = "update script_run_monitoring set sms_group_id=$sms_group_id,sms_txt='$sms_txt',alert_max_time=$alert_max_time,alert_max_count=$alert_max_count where id=$sid";
$result = $sqlcn->ExecuteSQL($sql);
?>