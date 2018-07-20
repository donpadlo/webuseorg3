<?php

$kkm= _POST("kkm");
$number_ls= _POST("number_ls");
$tovar_title= _POST("tovar_title");
$tovar_summ= _POST("tovar_summ");
$eorphone= _POST("eorphone");


$sql="insert into online_payments (id,kassaid,numcheck,docdate,summdoc,goodsjson,status,dognum,eorphone) values "
	. "(null,$kkm,'$number_ls',now(),'$tovar_summ','{\"name\":\"$tovar_title\",\"price\":$tovar_summ,\"quiantity\":1,\"summ\":$tovar_summ}',0,'$number_ls','$eorphone');";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу добавить чек!" . mysqli_error($sqlcn->idsqlconnection));

echo "Чек добавлен в очередь!";