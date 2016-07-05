<?php
/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
include_once(WUO_ROOT.'/class/logs.php'); 
$id=_GET("astra_id");
$lg=new Tlog();
$sql="select * from astra_mon where id=$id";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать ссылку!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $url=$row["url"];
};
`/usr/local/bin/curl -X POST -d '{ "cmd": "reload" }' $url/control/`;
$lg->Save(101,"--пользователь ".$user->login." перезагрузил астру $id, $url");
unset($lg);
?>
	    