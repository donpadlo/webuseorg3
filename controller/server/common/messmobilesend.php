<?php
$titlemobile=mysqli_real_escape_string($sqlcn->idsqlconnection,GetDef("titlemobile"));

$mobiletext=mysqli_real_escape_string($sqlcn->idsqlconnection,GetDef("mobiletext"));
$idtosend=mysqli_real_escape_string($sqlcn->idsqlconnection,GetDef("userid"));

$sql="insert into mobilemessages (id,userid,title,body,dtwrite,dtread) values (null,$idtosend,'$titlemobile','$mobiletext',now(),null);";
$result = $sqlcn->ExecuteSQL($sql) or die("Не получилось выполнить запрос на отправку сообщения!".mysqli_error($sqlcn->idsqlconnection));

echo "Сообщение успешно отправлено..";